<?php
/******************************************************
 * carregar_estoque.php — versão com wrapper da lista
 * - GET  : retorna <div class="estoque-list">[cards]</div>
 * - POST : excluir / editar (fotos + panorama360)
 ******************************************************/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$dbname = "u953537988_concessionaria";
$user = "u953537988_concessionaria";
$pass = "13579012Victor)";

try {
  $conn = new mysqli($host, $user, $pass, $dbname);
  $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
  http_response_code(500);
  echo "Erro de conexão";
  exit;
}

/* -------- Helpers -------- */
function money_to_decimal($v) {
  $v = trim(str_replace(['R$', ' '], '', (string)$v));
  if ($v === '') return 0.0;
  $hasDot   = strpos($v, '.') !== false;
  $hasComma = strpos($v, ',') !== false;
  if ($hasDot && $hasComma) {
    if (strrpos($v, ',') > strrpos($v, '.')) { $v = str_replace('.', '', $v); $v = str_replace(',', '.', $v); }
    else { $v = str_replace(',', '', $v); }
  } elseif ($hasComma) { $v = str_replace('.', '', $v); $v = str_replace(',', '.', $v); }
  else {
    $parts = explode('.', $v);
    if (count($parts) > 1 && strlen(end($parts)) === 3) $v = str_replace('.', '', $v);
  }
  return (float)$v;
}
function only_digits($s) { return (int)preg_replace('/\D+/', '', (string)$s); }
function safe_name($name){ return preg_replace('/[^a-zA-Z0-9._-]/', '_', (string)$name); }

/* -------- POST: excluir -------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
  header('Content-Type: application/json; charset=utf-8');
  try {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) throw new Exception('ID inválido');

    $stmt = $conn->prepare("SELECT fotos, panorama360 FROM veiculos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      $fotos = array_filter(explode(',', (string)$row['fotos']));
      foreach ($fotos as $f) {
        $path = __DIR__ . "/../uploads/fotos/" . $f;
        if ($f && is_file($path)) @unlink($path);
      }
      if (!empty($row['panorama360'])) {
        $pan = __DIR__ . "/../uploads/panorama/" . $row['panorama360'];
        if (is_file($pan)) @unlink($pan);
      }
    }

    $del = $conn->prepare("DELETE FROM veiculos WHERE id=?");
    $del->bind_param("i", $id);
    $del->execute();

    echo json_encode(['status' => 'ok']);
  } catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['status' => 'erro', 'msg' => $e->getMessage()]);
  }
  exit;
}

/* -------- POST: editar -------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {
  header('Content-Type: application/json; charset=utf-8');
  try {
    $id          = (int)($_POST['id'] ?? 0);
    if (!$id) throw new Exception('ID inválido');

    $marca       = trim($_POST['marca'] ?? '');
    $modelo      = trim($_POST['modelo'] ?? '');
    $ano         = (int)($_POST['ano'] ?? 0);
    $km          = only_digits($_POST['quilometragem'] ?? '0');
    $cambio      = trim($_POST['cambio'] ?? '');
    $combustivel = trim($_POST['combustivel'] ?? '');
    $historico   = trim($_POST['historico'] ?? '');
    $preco       = money_to_decimal($_POST['preco'] ?? '0');

    $stmt = $conn->prepare("
      UPDATE veiculos
         SET marca=?, modelo=?, ano=?, quilometragem=?, cambio=?, combustivel=?, historico=?, preco=?
       WHERE id=?
    ");
    $stmt->bind_param("ssissssdi", $marca, $modelo, $ano, $km, $cambio, $combustivel, $historico, $preco, $id);
    $stmt->execute();

    // Fotos (substituição)
    if (!empty($_FILES['fotos']['name'][0])) {
      $get = $conn->prepare("SELECT fotos FROM veiculos WHERE id=?");
      $get->bind_param("i", $id);
      $get->execute();
      $res = $get->get_result();
      if ($old = $res->fetch_assoc()) {
        $antigas = array_filter(explode(',', (string)$old['fotos']));
        foreach ($antigas as $f) {
          $path = __DIR__ . "/../uploads/fotos/" . $f;
          if ($f && is_file($path)) @unlink($path);
        }
      }

      $dirFotos = __DIR__ . "/../uploads/fotos/";
      if (!is_dir($dirFotos)) @mkdir($dirFotos, 0775, true);

      $novas = [];
      for ($i = 0; $i < count($_FILES['fotos']['name']); $i++) {
        if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
          $orig = safe_name(basename($_FILES['fotos']['name'][$i]));
          $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
          if (!in_array($ext, ['jpg','jpeg','png','webp'])) continue;
          $nome = uniqid('car_', true) . '.' . $ext;
          move_uploaded_file($_FILES['fotos']['tmp_name'][$i], $dirFotos . $nome);
          $novas[] = $nome;
        }
      }
      $novasStr = implode(',', $novas);
      $up = $conn->prepare("UPDATE veiculos SET fotos=? WHERE id=?");
      $up->bind_param("si", $novasStr, $id);
      $up->execute();
    }

    // Panorama 360 (substituição)
    if (!empty($_FILES['panorama360']['name']) && $_FILES['panorama360']['error'] === UPLOAD_ERR_OK) {
      $getp = $conn->prepare("SELECT panorama360 FROM veiculos WHERE id=?");
      $getp->bind_param("i", $id);
      $getp->execute();
      $rp = $getp->get_result();
      if ($op = $rp->fetch_assoc()) {
        if (!empty($op['panorama360'])) {
          $oldPan = __DIR__ . "/../uploads/panorama/" . $op['panorama360'];
          if (is_file($oldPan)) @unlink($oldPan);
        }
      }

      $dirPan = __DIR__ . "/../uploads/panorama/";
      if (!is_dir($dirPan)) @mkdir($dirPan, 0775, true);

      $orig = safe_name(basename($_FILES['panorama360']['name']));
      $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg','jpeg','png','webp'])) {
        $nome = uniqid('pano_', true) . '.' . $ext;
        move_uploaded_file($_FILES['panorama360']['tmp_name'], $dirPan . $nome);
        $upp = $conn->prepare("UPDATE veiculos SET panorama360=? WHERE id=?");
        $upp->bind_param("si", $nome, $id);
        $upp->execute();
      }
    }

    echo json_encode(['status' => 'ok']);
  } catch (Throwable $e) {
    http_response_code(400);
    echo json_encode(['status' => 'erro', 'msg' => $e->getMessage()]);
  }
  exit;
}

/* -------- GET: lista por tipo (com wrapper .estoque-list) -------- */
header('Content-Type: text/html; charset=utf-8');

$tipo = strtolower($_GET['tipo'] ?? 'carro');
if (!in_array($tipo, ['carro','moto'], true)) $tipo = 'carro';

$stmt = $conn->prepare("
  SELECT id, marca, modelo, ano, quilometragem, cambio, combustivel, historico, preco, fotos
    FROM veiculos
   WHERE LOWER(tipo) = ?
   ORDER BY id DESC
");
$stmt->bind_param("s", $tipo);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
  echo '<div class="estoque-list"><p style="font-style:italic">Nenhum veículo encontrado.</p></div>';
  exit;
}

echo '<div class="estoque-list">';

while ($v = $res->fetch_assoc()) {
  $fotos = array_filter(explode(',', (string)$v['fotos']));
  $img   = count($fotos) ? '../uploads/fotos/'.htmlspecialchars($fotos[0]) : 'img/sem-imagem.jpg';
  $titulo= htmlspecialchars($v['marca'].' '.$v['modelo']);
  $ano   = (int)$v['ano'];
  $km    = (int)$v['quilometragem'];

  echo '
  <div class="estoque-item" data-tipo="'.htmlspecialchars($tipo).'" data-id="'.$v['id'].'">
    <img src="'.$img.'" alt="'.$titulo.'">
    <div class="estoque-info">
      <h3>'.$titulo.'</h3>
      <p>Ano: '.$ano.'</p>
      <p>Quilometragem: '.$km.'</p>
    </div>
    <div class="estoque-actions">
      <i class="fa fa-trash action-delete" title="Excluir" data-id="'.$v['id'].'"></i>
      <i class="fa fa-pen action-edit" title="Editar"
         data-id="'.$v['id'].'"
         data-marca="'.htmlspecialchars($v['marca']).'"
         data-modelo="'.htmlspecialchars($v['modelo']).'"
         data-ano="'.$ano.'"
         data-km="'.$km.'"
         data-cambio="'.htmlspecialchars($v['cambio']).'"
         data-combustivel="'.htmlspecialchars($v['combustivel']).'"
         data-historico="'.htmlspecialchars($v['historico']).'"
         data-preco="'.htmlspecialchars($v['preco']).'"></i>
    </div>
  </div>';
}

echo '</div>';

$conn->close();
