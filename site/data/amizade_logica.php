<?php
// site/data/amizade_logica.php
require_once 'user.php'; 
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();
$meu_id = $_SESSION['id_usuario'];

// AÇÃO: ADICIONAR (SOLICITAR)
if (isset($_POST['adicionar_id'])) {
    $visitado = (int)$_POST['adicionar_id'];

    // CORREÇÃO: Usando id_usuario e id_amigo
    $check = $pdo->prepare("SELECT status FROM amizades WHERE (id_usuario = :m AND id_amigo = :v) OR (id_usuario = :v AND id_amigo = :m)");
    $check->execute([':m' => $meu_id, ':v' => $visitado]);

    if ($check->rowCount() == 0) {
        // 1. Cria a amizade PENDENTE
        // id_usuario = quem envia | id_amigo = quem recebe
        $sql = "INSERT INTO amizades (id_usuario, id_amigo, status) VALUES (:m, :v, 'pendente')";
        $pdo->prepare($sql)->execute([':m' => $meu_id, ':v' => $visitado]);

        // 2. Cria a NOTIFICAÇÃO para o outro corvo
        $msg = "enviou um pedido de aliança para você.";
        $notif = "INSERT INTO notificacoes (id_destinatario, id_remetente, mensagem, tipo) VALUES (:dest, :rem, :msg, 'pedido_amizade')";
        $pdo->prepare($notif)->execute([
            ':dest' => $visitado,
            ':rem'  => $meu_id,
            ':msg'  => $msg
        ]);

        echo "enviado";
    }
    exit;
}

// AÇÃO: ACEITAR PEDIDO
if (isset($_POST['aceitar_id'])) {
    $id_remetente = (int)$_POST['aceitar_id'];

    // 1. Atualiza status na tabela amizades
    $sql = "UPDATE amizades SET status = 'aceito' WHERE id_usuario = :rem AND id_amigo = :me AND status = 'pendente'";
    $pdo->prepare($sql)->execute([':rem' => $id_remetente, ':me' => $meu_id]);

    // 2. Cria feedback de notificação
    $msg_feedback = "aceitou seu pedido. Agora vocês são aliados!";
    $sqlFeedback = "INSERT INTO notificacoes (id_destinatario, id_remetente, mensagem, tipo) 
                    VALUES (:dest, :rem, :msg, 'amizade_confirmada')";
    $pdo->prepare($sqlFeedback)->execute([
        ':dest' => $id_remetente,
        ':rem'  => $meu_id,
        ':msg'  => $msg_feedback
    ]);

    echo "aceito";
    exit;
}

// AÇÃO: REMOVER (EXILAR)
if (isset($_POST['remover_id'])) {
    $id_exilado = (int)$_POST['remover_id'];

    // CORREÇÃO: Deleta a amizade independente de quem enviou o convite (quem é usuario ou quem é amigo)
    $sql = "DELETE FROM amizades 
            WHERE (id_usuario = :me AND id_amigo = :exilado) 
               OR (id_usuario = :exilado AND id_amigo = :me)";
    
    $stmt = $pdo->prepare($sql);
    $executou = $stmt->execute([
        ':me' => $meu_id,
        ':exilado' => $id_exilado
    ]);

    if ($executou) {
        echo "removido";
    } else {
        echo "erro";
    }
    exit;
}

// AÇÃO: RECUSAR OU CANCELAR
if (isset($_POST['recusar_id']) || isset($_POST['cancelar_id'])) {
    $target = isset($_POST['recusar_id']) ? (int)$_POST['recusar_id'] : (int)$_POST['cancelar_id'];

    $sql = "DELETE FROM amizades WHERE (id_usuario = :me AND id_amigo = :ta) OR (id_usuario = :ta AND id_amigo = :me)";
    $pdo->prepare($sql)->execute([':me' => $meu_id, ':ta' => $target]);

    echo "removido";
    exit;
}