<?php
// site/data/limpar_notificacoes.php
require_once 'user.php';
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];

// Marca como lida qualquer notificação que não precise de ação (likes, comentários, confirmações)
$sql = "UPDATE notificacoes SET lida = 1 
        WHERE id_destinatario = :meu 
        AND tipo IN ('amizade_confirmada', 'like', 'comentario', 'chat')";
$pdo->prepare($sql)->execute([':meu' => $meu_id]);

echo "limpo";