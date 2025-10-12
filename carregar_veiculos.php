<?php
$host = "localhost";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

// 🔎 AUTOCOMPLETE - SUGESTÕES ENQUANTO DIGITA
if (isset($_GET['sugestao']) && !empty($_GET['sugestao'])) {
  $busca = '%' . $_GET['sugestao'] . '%';
  $stmt = $conn->prepare("SELECT * FROM veiculos WHERE modelo LIKE ? OR marca LIKE ? LIMIT 5");
  $stmt->bind_param("ss", $busca, $busca);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($veiculo = $result->fetch_assoc()) {
    $foto = explode(",", $veiculo['fotos'])[0];
    $modelo = htmlspecialchars($veiculo['modelo']);
    $marca = htmlspecialchars($veiculo['marca']);
    $preco = number_format((float)$veiculo['preco'], 2, ',', '.');

    echo '<li class="sugestao-item" data-busca="' . $marca . ' ' . $modelo . '">';
    echo '  <img src="uploads/fotos/' . htmlspecialchars($foto) . '" alt="' . $modelo . '">';
    echo '  <div class="sugestao-info">';
    echo '    <strong>' . $marca . ' ' . $modelo . '</strong>';
    echo '    <span>R$ ' . $preco . '</span>';
    echo '  </div>';
    echo '</li>';
  }

  $stmt->close();
  $conn->close();
  exit;
}

// 🔁 EXIBIÇÃO PADRÃO DE VEÍCULOS COM FILTRO
function renderCards($tipo, $cambio = null, $filtro = null) {
  global $conn;

  $query = "SELECT * FROM veiculos WHERE tipo = ?";
  $param_types = "s";
  $params = [$tipo];

  if ($cambio) {
    $query .= " AND cambio = ?";
    $param_types .= "s";
    $params[] = $cambio;
  }

  if ($filtro === 'novos') {
    $query .= " AND quilometragem = 0";
  } elseif ($filtro === 'usados') {
    $query .= " AND quilometragem > 0";
  }

  $stmt = $conn->prepare($query);
  $stmt->bind_param($param_types, ...$params);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    echo '<p class="mensagem-vazia" style="font-style: italic;">Nenhum veículo encontrado nesta categoria.</p>';
  } else {
    while ($veiculo = $result->fetch_assoc()) {
      $fotos = explode(",", $veiculo['fotos']);
      $ano = htmlspecialchars($veiculo['ano']);
      $km = number_format((int)$veiculo['quilometragem'], 0, '', '.');
      $preco = number_format((float)$veiculo['preco'], 2, ',', '.');
      $modelo = htmlspecialchars($veiculo['modelo']);
      $marca = htmlspecialchars($veiculo['marca']);
      $descricao = htmlspecialchars($veiculo['historico']);
      $status = ($veiculo['quilometragem'] == 0) ? 'novo' : 'usado';

      echo '<div class="card" data-name="' . strtolower($marca . ' ' . $modelo) . '" data-status="' . $status . '">';
      echo '  <div class="card-carousel">';
      echo '    <div class="carousel-inner">';
      foreach ($fotos as $foto) {
        if (!empty($foto)) {
          echo '<img src="uploads/fotos/' . htmlspecialchars($foto) . '" alt="' . $modelo . '">';
        }
      }
      echo '    </div>';
      echo '    <button class="carousel-btn prev-btn">&#10094;</button>';
      echo '    <button class="carousel-btn next-btn">&#10095;</button>';
      echo '  </div>';
      echo '  <div class="card-text">';
      echo '    <h3>' . $marca . ' - ' . $modelo . '</h3>';
      echo '    <div class="car-info">';
      echo '      <span class="ano"><i class="fa fa-calendar"></i> ' . $ano . '</span>';
      echo '      <span class="km"><i class="fa fa-tachometer"></i> ' . $km . ' km</span>';
      echo '    </div>';
      echo '    <strong class="price">R$ ' . $preco . '</strong>';
      echo '    <button class="oferta-button" data-descricao="' . $descricao . '">Ver oferta</button>';
      echo '  </div>';
      echo '</div>';
    }
  }

  $stmt->close();
}

// ✅ VERIFICA FILTRO DA URL
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;

// AUTOMÁTICOS
echo '<section class="categoria">';
echo '<h2>AUTOMÁTICOS</h2>';
echo '<div class="carousel">';
renderCards("carro", "automatico", $filtro);
echo '</div>';
echo '</section>';

// MANUAIS
echo '<section class="categoria">';
echo '<h2>MANUAIS</h2>';
echo '<div class="carousel">';
renderCards("carro", "manual", $filtro);
echo '</div>';
echo '</section>';

// MOTOS
echo '<section class="categoria">';
echo '<h2>MOTOS</h2>';
echo '<div class="carousel">';
renderCards("moto", null, $filtro);
echo '</div>';
echo '</section>';

$conn->close();
?>

