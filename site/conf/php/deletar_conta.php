<?php
session_start();
ob_start(); // Inicia o buffer para evitar qualquer lixo na saída

header('Content-Type: application/json');
require_once '../../../classes/Usuarios.php';

// ... (Restante da sua lógica de conexão e password_verify que já temos)

if ($user && password_verify($senha_digitada, $user['senha'])) {
    // ... deletar ...
    ob_clean(); // Limpa qualquer aviso do PHP antes de mandar o sucesso
    echo json_encode(['status' => 'sucesso']);
    exit;
} else {
    ob_clean();
    echo json_encode(['status' => 'erro', 'detalhe' => 'Senha mestra incorreta.']);
    exit;
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$id_logado = $_SESSION['id_usuario'];
$senha_digitada = $_POST['senha_excluir'] ?? '';

// 1. Procurar o Hash VARCHAR(255)
$stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id_usuario = ?");
$stmt->execute([$id_logado]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // 2. VERIFICAÇÃO COM PASSWORD_VERIFY (BCRYPT)
    if (password_verify($senha_digitada, $user['senha'])) {
        
        $delete = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        
        if ($delete->execute([$id_logado])) {
            session_destroy();
            ob_clean();
            echo json_encode(['status' => 'sucesso']);
        } else {
            ob_clean();
            echo json_encode(['status' => 'erro', 'detalhe' => 'Erro ao remover do banco.']);
        }
    } else {
        ob_clean();
        echo json_encode(['status' => 'erro', 'detalhe' => 'Senha mestra inválida.']);
    }
} else {
    ob_clean();
    echo json_encode(['status' => 'erro', 'detalhe' => 'Utilizador não encontrado.']);
}
exit;