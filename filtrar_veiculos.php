<?php
$host = "localhost";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

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

$filtro = $_GET['filtro'] ?? null;
$categoria = $_GET['categoria'] ?? null; // "automatico", "manual", "moto"

if ($categoria === 'moto') {
  renderCards("moto", null, $filtro);
} elseif ($categoria === 'manual') {
  renderCards("carro", "manual", $filtro);
} elseif ($categoria === 'automatico') {
  renderCards("carro", "automatico", $filtro);
}

$conn->close();
?>

