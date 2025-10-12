<?php
$host = "localhost";

$conn = new mysqli($host, $user, $pass, $dbname);

$sql = "SELECT * FROM propostas_venda ORDER BY criado_em DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="slots-header">
            <div>Nome<br><small>Status</small></div>
            <div>Telefone</div>
            <div>Email</div>
            <div>Detalhes</div>
          </div>';

    while ($row = $result->fetch_assoc()) {
        $nome = $row['nome_cliente'] ?: 'Não informado';
        $email = $row['email_cliente'] ?: 'Não informado';
        $telefone = $row['telefone_cliente'] ?: 'Não informado';
        $status = strtolower($row['status']);

        $badge = "<span class='badge badge-{$status}'>".ucfirst($status)."</span>";

        echo "<div class='slots-row'>
                <div class='slot-item'>{$nome}<br>{$badge}</div>
                <div class='slot-item'>{$telefone}</div>
                <div class='slot-item'>{$email}</div>
                <div class='slot-item'>
                  <span class='detail-dots'
                    data-id='{$row['id']}'
                    data-nome='{$row['nome_cliente']}'
                    data-telefone='{$row['telefone_cliente']}'
                    data-email='{$row['email_cliente']}'
                    data-marca='{$row['marca']}'
                    data-modelo='{$row['modelo']}'
                    data-ano='{$row['ano']}'
                    data-cambio='{$row['cambio']}'
                    data-km='{$row['quilometragem']}'
                    data-valor='{$row['valor_pedido']}'
                    data-renavam='{$row['renavam']}'
                    data-historico=\"".htmlspecialchars($row['historico'], ENT_QUOTES)."\" 
                    data-documentos='{$row['documentos']}'
                  >&#8942;</span>
                </div>
              </div>";
    }
} else {
    echo "<p>Nenhum anúncio encontrado.</p>";
}
?>

