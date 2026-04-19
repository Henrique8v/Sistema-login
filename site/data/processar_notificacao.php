<?php
//processar_notificacoes.php
require_once 'user.php';
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$id_remetente = filter_input(INPUT_POST, 'id_remetente', FILTER_SANITIZE_NUMBER_INT);
$acao = $_POST['acao'];
$meu_id = $_SESSION['id_usuario'];
$meu_nome = $_SESSION['nome_usuario'];

if ($id_remetente && $acao) {
    if ($acao === 'aceitar') {
        // 1. Atualiza o status da amizade para 'aceito'
        $sql = "UPDATE amizades SET status = 'aceito' 
                WHERE (usuario_id = :rem AND amigo_id = :meu) 
                OR (usuario_id = :meu AND amigo_id = :rem)";
        $pdo->prepare($sql)->execute([':rem' => $id_remetente, ':meu' => $meu_id]);

        // 2. Notifica o remetente que o pedido foi aceito
        $msg = "O corvo $meu_nome aceitou seu pedido. Agora vocês são amigos!";
        $sqlNotif = "INSERT INTO notificacoes (id_destinatario, id_remetente, mensagem, tipo) 
                     VALUES (:dest, :rem, :msg, 'amizade_confirmada')";
        $pdo->prepare($sqlNotif)->execute([
            ':dest' => $id_remetente,
            ':rem' => $meu_id,
            ':msg' => $msg
        ]);

    } else {
        // Se recusar, removemos o pedido
        $sql = "DELETE FROM amizades 
                WHERE (usuario_id = :rem AND amigo_id = :meu AND status = 'pendente')";
        $pdo->prepare($sql)->execute([':rem' => $id_remetente, ':meu' => $meu_id]);
    }

    // Marca a notificação do pedido como lida
    $sqlLida = "UPDATE notificacoes SET lida = 1 
                WHERE id_destinatario = :meu AND id_remetente = :rem AND tipo IN ('pedido_amizade', 'amizade')";
    $pdo->prepare($sqlLida)->execute([':meu' => $meu_id, ':rem' => $id_remetente]);

    echo "sucesso";
}