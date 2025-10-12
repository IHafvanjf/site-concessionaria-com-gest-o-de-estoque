<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // ajuste o caminho conforme necessário

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['erro' => 'Dados incompletos']);
    exit;
}

$id = intval($data['id']);
$status = $data['status'];

$host = "localhost";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(['erro' => 'Erro de conexão']);
    exit;
}

// Atualiza o status da proposta
$update = $conn->prepare("UPDATE propostas_venda SET status = ? WHERE id = ?");
$update->bind_param("si", $status, $id);
$success = $update->execute();

// Busca dados do cliente
$busca = $conn->prepare("SELECT nome_cliente, email_cliente, telefone_cliente FROM propostas_venda WHERE id = ?");
$busca->bind_param("i", $id);
$busca->execute();
$res = $busca->get_result();
$cliente = $res->fetch_assoc();

if ($success && $cliente) {
    $nome = $cliente['nome_cliente'];
    $email = $cliente['email_cliente'];
    $telefone = preg_replace('/[^0-9]/', '', $cliente['telefone_cliente']); // remove caracteres não numéricos

    $mensagem = ($status === 'Aprovado') ?
        "Olá $nome, sua proposta de venda foi aprovada pela equipe da AllTech. Entraremos em contato para seguir com o processo." :
        "Olá $nome, lamentamos informar que sua proposta foi recusada após análise. Agradecemos seu interesse.";

    // 1) Se tiver telefone, retorna link do WhatsApp
    if (!empty($telefone)) {
        $linkWhatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);
        echo json_encode([
            'sucesso' => true,
            'via' => 'whatsapp',
            'link' => $linkWhatsapp
        ]);
        exit;
    }

    // 2) Se não tiver telefone mas tiver email, envia e-mail
    if (!empty($email)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pedrolhf06@gmail.com';
            $mail->Password   = 'hbzc bmix julz ausn';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('pedrolhf06@gmail.com', 'Concessionária AllTech');
            $mail->addAddress($email, $nome);

            $mail->isHTML(true);
            $mail->Subject = "Sua proposta foi $status";
            $mail->Body = nl2br($mensagem);

            $mail->send();
            echo json_encode(['sucesso' => true, 'via' => 'email']);
        } catch (Exception $e) {
            echo json_encode(['erro' => 'Falha no envio de e-mail', 'info' => $mail->ErrorInfo]);
        }
        exit;
    }

    // 3) Nenhum contato disponível
    echo json_encode(['erro' => 'Cliente não possui telefone nem e-mail']);
} else {
    echo json_encode(['erro' => 'Erro ao atualizar ou cliente não encontrado']);
}

$conn->close();

