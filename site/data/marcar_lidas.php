<?php
require_once '../../classes/Usuarios.php';
session_start();

if (isset($_SESSION['id_usuario'])) {
    $u = new Usuarios();
    $u->conectar("projeto_login", "localhost", "root", "");
    $pdo = $u->getPdo();

    $sql = "UPDATE notificacoes SET lida = 1 WHERE id_destinatario = :id AND lida = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_SESSION['id_usuario']]);
    echo "sucesso";
}