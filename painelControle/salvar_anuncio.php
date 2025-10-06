<?php
$host = "localhost";
$dbname = "u953537988_concessionaria";
$user = "u953537988_concessionaria";
$pass = "13579012Victor)";

// Conecta ao banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

function money_to_decimal($v) {
  $v = trim(str_replace(['R$', ' '], '', (string)$v));
  if ($v === '') return 0.0;

  $hasDot   = strpos($v, '.') !== false;
  $hasComma = strpos($v, ',') !== false;

  if ($hasDot && $hasComma) {
    // Qual é o separador decimal? O último que aparece
    if (strrpos($v, ',') > strrpos($v, '.')) {
      $v = str_replace('.', '', $v);
      $v = str_replace(',', '.', $v);
    } else {
      $v = str_replace(',', '', $v);
      // ponto já é decimal
    }
  } elseif ($hasComma) {
    // Formato BR: 135.000,00 ou 135,00
    $v = str_replace('.', '', $v);
    $v = str_replace(',', '.', $v);
  } elseif ($hasDot) {
    // Só ponto: se a parte final tiver 3 dígitos, é milhar -> remove pontos
    $parts = explode('.', $v);
    if (count($parts) > 1 && strlen(end($parts)) === 3) {
      $v = str_replace('.', '', $v);
    }
    // senão, já está como decimal com ponto
  }
  return (float)$v;
}


// Captura dados do formulário
$tipo = $_POST['tipo'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$ano = $_POST['ano'];
$quilometragem = $_POST['quilometragem'];
$cambio = isset($_POST['cambio']) ? $_POST['cambio'] : null;
$combustivel = $_POST['combustivel'];
$historico = $_POST['historico'];
$preco = $_POST['preco'];

// Define condição com base na quilometragem
$km_numerico = (int) filter_var($quilometragem, FILTER_SANITIZE_NUMBER_INT);
$condicao = $km_numerico === 0 ? "novo" : "usado";

// Cria pastas se não existirem
if (!is_dir("../uploads/fotos")) {
  mkdir("../uploads/fotos", 0777, true);
}
if (!is_dir("../uploads/panoramas")) {
  mkdir("../uploads/panoramas", 0777, true);
}

// Primeiro, insere o registro com os dados principais
$stmtInsert = $conn->prepare("
  INSERT INTO veiculos (tipo, marca, modelo, ano, quilometragem, cambio, combustivel, condicao, historico, preco)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmtInsert->bind_param("ssssssssss", $tipo, $marca, $modelo, $ano, $quilometragem, $cambio, $combustivel, $condicao, $historico, $preco);
$stmtInsert->execute();
$id_veiculo = $stmtInsert->insert_id;
$stmtInsert->close();

// Upload imagem 360°
$panoramaNome = null;
if (isset($_FILES['panorama360']) && $_FILES['panorama360']['error'] === 0) {
  $panoramaNome = "veiculo_" . $id_veiculo . "_360_" . basename($_FILES['panorama360']['name']);
  move_uploaded_file($_FILES['panorama360']['tmp_name'], "../uploads/panoramas/" . $panoramaNome);
}

// Upload das fotos
$fotosNomes = [];
if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'])) {
  $fileCount = is_array($_FILES['fotos']['name']) ? count($_FILES['fotos']['name']) : 1;

  for ($i = 0; $i < $fileCount; $i++) {
    $nomeOriginal = is_array($_FILES['fotos']['name']) ? $_FILES['fotos']['name'][$i] : $_FILES['fotos']['name'];
    $nomeTemporario = is_array($_FILES['fotos']['tmp_name']) ? $_FILES['fotos']['tmp_name'][$i] : $_FILES['fotos']['tmp_name'];
    $erro = is_array($_FILES['fotos']['error']) ? $_FILES['fotos']['error'][$i] : $_FILES['fotos']['error'];

    if ($erro === 0) {
      $nomeUnico = "veiculo_" . $id_veiculo . "_foto_" . $i . "_" . basename($nomeOriginal);
      $destino = "../uploads/fotos/" . $nomeUnico;

      if (move_uploaded_file($nomeTemporario, $destino)) {
        $fotosNomes[] = $nomeUnico;
      }
    }
  }
}

$fotosString = implode(",", $fotosNomes);

// Atualiza com as imagens
$stmtUpdate = $conn->prepare("UPDATE veiculos SET fotos = ?, panorama360 = ? WHERE id = ?");
$stmtUpdate->bind_param("ssi", $fotosString, $panoramaNome, $id_veiculo);

if ($stmtUpdate->execute()) {
  echo "Anúncio cadastrado com sucesso!";
} else {
  echo "Erro ao salvar imagens: " . $stmtUpdate->error;
}

$stmtUpdate->close();
$conn->close();
?>
