<?php
// site/data/amizade_aceitar.php
require_once 'user.php'; // Garante que apenas corvos logados operem
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

// Filtra as entradas para evitar magias negras (SQL Injection)
$id_remetente = filter_input(INPUT_POST, 'id_remetente', FILTER_SANITIZE_NUMBER_INT);
$acao         = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_SPECIAL_CHARS);
$meu_id       = $_SESSION['id_usuario'];
$meu_nome     = $_SESSION['nome_usuario'];

if ($id_remetente && $acao) {
    if ($acao === 'aceitar') {
        // 1. Oficializa a amizade no banco de dados
        $sql = "UPDATE amizades SET status = 'aceito' 
                WHERE (usuario_id = :rem AND amigo_id = :meu) 
                OR (usuario_id = :meu AND amigo_id = :rem)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':rem' => $id_remetente, ':meu' => $meu_id]);

        // 2. Envia a notificação de confirmação para o remetente
        $msg_confirmacao = "O corvo $meu_nome aceitou seu pedido. Agora vocês são amigos!";
        $sqlNotif = "INSERT INTO notificacoes (id_destinatario, id_remetente, mensagem, tipo, lida) 
                     VALUES (:dest, :rem, :msg, 'amizade_confirmada', 0)";
        $stmtNotif = $pdo->prepare($sqlNotif);
        $stmtNotif->execute([
            ':dest' => $id_remetente,
            ':rem'  => $meu_id,
            ':msg'  => $msg_confirmacao
        ]);

        echo "aceito";

    } elseif ($acao === 'recusar') {
        // Se recusar, removemos o registro pendente da tabela de amizades
        $sql = "DELETE FROM amizades 
                WHERE (usuario_id = :rem AND amigo_id = :meu AND status = 'pendente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':rem' => $id_remetente, ':meu' => $meu_id]);

        echo "recusado";
    }

    // 3. Marca a notificação original do pedido como lida para ela sumir ou mudar de estado
    $sqlLida = "UPDATE notificacoes SET lida = 1 
                WHERE id_destinatario = :meu AND id_remetente = :rem AND tipo IN ('pedido_amizade', 'amizade')";
    $stmtLida = $pdo->prepare($sqlLida);
    $stmtLida->execute([':meu' => $meu_id, ':rem' => $id_remetente]);

} else {
    echo "erro";
}
?>