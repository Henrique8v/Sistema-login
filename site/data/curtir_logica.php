<?php
// site/data/curtir_logica.php
require_once '../../classes/Usuarios.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario']) || !isset($_POST['id_post'])) {
    echo json_encode(['status' => 'erro']);
    exit;
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];
$id_post = (int)$_POST['id_post'];

// Verifica se já existe o like
$sqlCheck = "SELECT id_curtida FROM curtidas WHERE post_id = :p AND usuario_id = :u";
$stmt = $pdo->prepare($sqlCheck);
$stmt->execute([':p' => $id_post, ':u' => $meu_id]);
$existe = $stmt->fetch();

if ($existe) {
    // Se existe, remove (Desfazer Like)
    $sqlDel = "DELETE FROM curtidas WHERE post_id = :p AND usuario_id = :u";
    $pdo->prepare($sqlDel)->execute([':p' => $id_post, ':u' => $meu_id]);
    echo json_encode(['status' => 'removido']);
} else {
    // Se não existe, adiciona (Dar Like)
    $sqlIns = "INSERT INTO curtidas (post_id, usuario_id) VALUES (:p, :u)";
    $pdo->prepare($sqlIns)->execute([':p' => $id_post, ':u' => $meu_id]);

    // LÓGICA DE NOTIFICAÇÃO
    $sqlDono = "SELECT usuario_id FROM timeline WHERE id_post = :idp";
    $stmtD = $pdo->prepare($sqlDono);
    $stmtD->execute([':idp' => $id_post]);
    $dono = $stmtD->fetch(PDO::FETCH_ASSOC);

    if ($dono && $dono['usuario_id'] != $meu_id) {
        $msg = "<b>{$_SESSION['nome_usuario']}</b> curtiu sua publicação.";
        $sqlN = "INSERT INTO notificacoes (id_remetente, id_destinatario, id_post_referencia, mensagem, tipo) 
                 VALUES (:rem, :dest, :post, :msg, 'like')";
        $pdo->prepare($sqlN)->execute([
            ':rem' => $meu_id, 
            ':dest' => $dono['usuario_id'], 
            ':post' => $id_post, 
            ':msg' => $msg
        ]);
    }
    echo json_encode(['status' => 'adicionado']);
}