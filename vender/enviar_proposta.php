<?php
// Dados de conexão

// Conecta ao banco
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Cria pastas de upload se não existirem
if (!is_dir("../uploads/fotos_propostas")) {
    mkdir("../uploads/fotos_propostas", 0777, true);
}
if (!is_dir("../uploads/documentos_propostas")) {
    mkdir("../uploads/documentos_propostas", 0777, true);
}

// Captura dados do formulário
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$ano = $_POST['ano'];
$quilometragem = $_POST['quilometragem'];
$cambio = $_POST['cambio'];
$historico = $_POST['historico'];
$valor_pedido = $_POST['valor_pedido'];
$renavam = isset($_POST['renavam']) ? $_POST['renavam'] : null;
$forma_contato = $_POST['forma_contato'];

// Decide nome e telefone/email dependendo da escolha de contato
$nome_cliente = "";
$telefone_cliente = "";
$email_cliente = "";

if ($forma_contato === 'telefone') {
    $nome_cliente = $_POST['nome_telefone'];
    $telefone_cliente = $_POST['telefone'];
} elseif ($forma_contato === 'email') {
    $nome_cliente = $_POST['nome_email'];
    $email_cliente = $_POST['email'];
}

// Upload das fotos do veículo
$fotosNomes = [];
if (isset($_FILES['fotos']) && !empty($_FILES['fotos']['name'][0])) {
    foreach ($_FILES['fotos']['name'] as $index => $fotoName) {
        $fotoTmp = $_FILES['fotos']['tmp_name'][$index];
        if ($_FILES['fotos']['error'][$index] === 0) {
            $fotoUnico = uniqid('foto_') . "_" . basename($fotoName);
            $destinoFoto = "../uploads/fotos_propostas/" . $fotoUnico;
            if (move_uploaded_file($fotoTmp, $destinoFoto)) {
                $fotosNomes[] = $fotoUnico;
            }
        }
    }
}

// Upload dos documentos do veículo
$documentosNomes = [];
if (isset($_FILES['documentos']) && !empty($_FILES['documentos']['name'][0])) {
    foreach ($_FILES['documentos']['name'] as $index => $docName) {
        $docTmp = $_FILES['documentos']['tmp_name'][$index];
        if ($_FILES['documentos']['error'][$index] === 0) {
            $docUnico = uniqid('doc_') . "_" . basename($docName);
            $destinoDoc = "../uploads/documentos_propostas/" . $docUnico;
            if (move_uploaded_file($docTmp, $destinoDoc)) {
                $documentosNomes[] = $docUnico;
            }
        }
    }
}
$documentosString = implode(",", $documentosNomes);
$fotosString = implode(",", $fotosNomes);

$stmt = $conn->prepare("
    INSERT INTO propostas_venda 
    (marca, modelo, ano, quilometragem, cambio, historico, imagens, valor_pedido, documentos, renavam, forma_contato, nome_cliente, telefone_cliente, email_cliente)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssiissdsssssss",
    $marca,
    $modelo,
    $ano,
    $quilometragem,
    $cambio,
    $historico,
    $fotosString, // <-- isso aqui é o que precisa ir!
    $valor_pedido,
    $documentosString,
    $renavam,
    $forma_contato,
    $nome_cliente,
    $telefone_cliente,
    $email_cliente
);


if ($stmt->execute()) {
    echo "Proposta enviada com sucesso!";
} else {
    echo "Erro ao enviar proposta: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

