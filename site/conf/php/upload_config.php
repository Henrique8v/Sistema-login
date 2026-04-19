<?php
// site/conf/php/deletar_conta.php
session_start();
require_once '../../../classes/Usuarios.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['key'])) {
    header("Location: ../../../conf.php");
    exit;
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();
$meu_id = $_SESSION['id_usuario'];

// Decodifica a senha vinda do prompt do JS
$senha_confirmacao = base64_decode($_GET['key']);

// Busca a senha atual no banco
$sql = "SELECT senha FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $meu_id]);
$user = $stmt->fetch();

if ($user && password_verify($senha_confirmacao, $user['senha'])) {
    // APAGAR TUDO (Cuidado: certifique-se das chaves estrangeiras no MySQL)
    $deletar = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
    if ($deletar->execute([':id' => $meu_id])) {
        session_destroy();
        header("Location: ../../../index.php?msg=conta_deletada");
        exit;
    }
} else {
    echo "<script>alert('Senha incorreta! A conta não foi excluída.'); window.location.href='../../../conf.php';</script>";
}