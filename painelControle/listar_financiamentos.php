<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$dbname = "u953537988_concessionaria";
$user = "u953537988_concessionaria";
$pass = "13579012Victor)";

try {
  $conn = new mysqli($host, $user, $pass, $dbname);
  $conn->set_charset('utf8mb4');

  // Tabela: financiamentos(id, nome, celular, email, cpf, tem_troca, criado_em)
  $sql = "
    SELECT
      nome,
      email,
      celular,
      cpf,
      tem_troca,
      criado_em AS enviado_em,   -- alias p/ manter compatibilidade com o front
      NULL      AS carro         -- não existe na tabela; devolve NULL
    FROM financiamentos
    ORDER BY criado_em DESC
  ";

  $res = $conn->query($sql);

  $dados = [];
  while ($row = $res->fetch_assoc()) {
    $dados[] = [
      'nome'       => $row['nome']        ?? '',
      'email'      => $row['email']       ?? '',
      'celular'    => $row['celular']     ?? '',
      'cpf'        => $row['cpf']         ?? '',
      'troca'      => (string)($row['tem_troca'] ?? '0'),
      'enviado_em' => $row['enviado_em']  ?? '',
      'carro'      => $row['carro']       ?? null,
    ];
  }

  echo json_encode($dados, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['erro' => 'Falha ao listar financiamentos', 'detalhe' => $e->getMessage()]);
}
