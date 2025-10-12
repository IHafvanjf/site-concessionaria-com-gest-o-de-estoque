<?php
header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $host = "localhost";

    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset('utf8mb4');

    $nome     = $_POST['nome']     ?? '';
    $celular  = $_POST['celular']  ?? '';
    $email    = $_POST['email']    ?? '';
    $cpf      = $_POST['cpf']      ?? '';
    $temTroca = isset($_POST['temTroca']) ? (int)$_POST['temTroca'] : 0;

    if ($nome === '' || $celular === '' || $cpf === '') {
        echo json_encode(['sucesso' => false, 'erro' => 'Campos obrigatórios ausentes']);
        exit;
    }

    // CONFIRA se a tabela e as colunas existem exatamente assim:
    // financiamentos(nome, celular, email, cpf, tem_troca)
    $stmt = $conn->prepare(
        "INSERT INTO financiamentos (nome, celular, email, cpf, tem_troca) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssi", $nome, $celular, $email, $cpf, $temTroca);
    $stmt->execute();

    echo json_encode(['sucesso' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    // Em produção, retorne genérico; durante debug, pode incluir $e->getMessage()
    echo json_encode(['sucesso' => false, 'erro' => 'Falha no servidor', 'detalhe' => $e->getMessage()]);
} finally {
    if (isset($stmt) && $stmt) $stmt->close();
    if (isset($conn) && $conn) $conn->close();
}

