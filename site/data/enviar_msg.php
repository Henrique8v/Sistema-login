<?php
// Sistema-login/site/data/enviar_msg.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php';
require_once 'user.php';
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();

$remetente = $_SESSION['id_usuario'];

// 1. LÓGICA: LIMPAR CONVERSA
if (isset($_POST['limpar_conversa_com'])) {
    $id_amigo = (int)$_POST['limpar_conversa_com'];
    $sqlLimpar = "DELETE FROM mensagens_privadas 
                  WHERE (remetente_id = :me AND destinatario_id = :amigo) 
                  OR (remetente_id = :amigo AND destinatario_id = :me)";
    $stmtLimpar = $pdo->prepare($sqlLimpar);
    $stmtLimpar->execute([':me' => $remetente, ':amigo' => $id_amigo]);
    echo "limpo";
    exit;
}

// 2. LÓGICA: EXCLUIR MENSAGEM ÚNICA
if (isset($_POST['excluir_id'])) {
    $id_msg = (int)$_POST['excluir_id'];
    $sqlDel = "DELETE FROM mensagens_privadas WHERE id = :id AND remetente_id = :meu_id";
    $stmtDel = $pdo->prepare($sqlDel);
    if($stmtDel->execute([':id' => $id_msg, ':meu_id' => $remetente])) {
        echo "apagado";
    }
    exit;
}

// 3. LÓGICA DE ENVIO (COM GIF E CITAÇÃO)
if(isset($_POST['id_para'])) {
    $destinatario = (int)$_POST['id_para'];
    $msg = $_POST['mensagem'] ?? '';
    $gif_url = $_POST['gif_url'] ?? null;
    $citacao_id = !empty($_POST['citacao_id']) ? (int)$_POST['citacao_id'] : null;

    if((!empty($msg) || !empty($gif_url)) && $destinatario > 0) {
        $sql = "INSERT INTO mensagens_privadas (remetente_id, destinatario_id, mensagem, gif_url, citacao_id) 
                VALUES (:r, :d, :m, :g, :c)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':r' => $remetente, 
            ':d' => $destinatario, 
            ':m' => $msg,
            ':g' => $gif_url,
            ':c' => $citacao_id
        ]);
        
        // Notificação (apenas se não for GIF ou se tiver texto)
        $sqlNotif = "INSERT INTO notificacoes (id_remetente, id_destinatario, mensagem, tipo) 
                     VALUES (:rem, :dest, 'Enviou uma nova mensagem.', 'mensagem')";
        $stmtN = $pdo->prepare($sqlNotif);
        $stmtN->execute([':rem' => $remetente, ':dest' => $destinatario]);
        
        echo "enviado";
    }
}