<?php
session_start();
require_once 'classes/Usuarios.php';
$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

if (isset($_GET['id']) && isset($_SESSION['id_usuario'])) {
    $meu_id = $_SESSION['id_usuario'];
    $amigo_id = (int)$_GET['id'];

    if ($meu_id != $amigo_id) {
        // Verifica se já existe o vínculo
        $sql = $pdo->prepare("SELECT * FROM amigos WHERE (id_usuario = :m AND id_amigo = :a) OR (id_usuario = :a AND id_amigo = :m)");
        $sql->execute([':m' => $meu_id, ':a' => $amigo_id]);

        if ($sql->rowCount() == 0) {
            $insere = $pdo->prepare("INSERT INTO amigos (id_usuario, id_amigo, status) VALUES (:m, :a, 'aceito')");
            $insere->execute([':m' => $meu_id, ':a' => $amigo_id]);
        }
    }
}
header("location: mensagens.php?contato=" . $amigo_id);
exit;
?>