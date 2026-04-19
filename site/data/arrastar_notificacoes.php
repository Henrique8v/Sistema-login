<?php
// site/data/arrastar_notificacoes.php
session_start();

// Conexão baseada no seu dump SQL (projeto_login)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=projeto_login", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (Exception $e) {
    die("Erro de conexão: " . $e->getMessage());
}

$meu_id = $_SESSION['id_usuario'] ?? null;
$id_notif = filter_input(INPUT_POST, 'id_notif', FILTER_SANITIZE_NUMBER_INT);

if ($id_notif && $meu_id) {
    // IMPORTANTE: Usei 'id_notificacao' e 'id_destinatario' conforme seu SQL
    $sql = "DELETE FROM notificacoes WHERE id_notificacao = :id AND id_destinatario = :meu_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id_notif,
        ':meu_id' => $meu_id
    ]);

    if ($stmt->rowCount() > 0) {
        echo "sucesso";
    } else {
        echo "erro_nao_encontrado";
    }
} else {
    echo "dados_ausentes";
}
exit;