<?php
// chat_logica.php

// Incluímos a classe de usuários para usar a conexão e a segurança
require_once 'classes/Usuarios.php';
session_start();

$u = new Usuarios;
$u->conectar("projeto_login", "localhost", "root", "");

// Ação de ENVIAR mensagem
if (isset($_POST['enviar_msg']) && isset($_SESSION['id_usuario'])) {
    $mensagem = addslashes($_POST['mensagem']);
    $id_usuario = $_SESSION['id_usuario'];
    
    // Buscamos os dados atuais do usuário para gravar na mensagem (evita JOINS pesados no chat)
    $sql = "SELECT nome, foto FROM usuarios WHERE id_usuario = :id";
    $stmt = $u->pdo->prepare($sql);
    $stmt->bindValue(":id", $id_usuario);
    $stmt->execute();
    $dados = $stmt->fetch();

    if (!empty($mensagem)) {
        $sql = "INSERT INTO mensagens_chat (usuario_id, nome, foto, mensagem) VALUES (:uid, :nome, :foto, :msg)";
        $stmt = $u->pdo->prepare($sql);
        $stmt->bindValue(":uid", $id_usuario);
        $stmt->bindValue(":nome", $dados['nome']);
        $stmt->bindValue(":foto", $dados['foto']);
        $stmt->bindValue(":msg", $mensagem);
        $stmt->execute();
    }
    exit; // Termina aqui pois é uma requisição AJAX
}

// Ação de LISTAR mensagens
if (isset($_GET['atualizar_chat'])) {
    // Pegamos as últimas 50 mensagens
    $sql = "SELECT * FROM (SELECT * FROM mensagens_chat ORDER BY id DESC LIMIT 50) AS sub ORDER BY id ASC";
    $stmt = $u->pdo->query($sql);
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($mensagens as $m) {
        $data = date('H:i', strtotime($m['data_envio']));
        echo "
        <div style='margin-bottom: 12px; border-bottom: 1px solid #333; padding-bottom: 5px;'>
            <img src='site/{$m['foto']}' style='width: 25px; height: 25px; border-radius: 50%; vertical-align: middle; margin-right: 8px;'>
            <strong style='color: #F91313; font-size: 13px;'>{$m['nome']}</strong> 
            <span style='color: #666; font-size: 10px;'> às {$data}</span>
            <p style='color: #ccc; margin: 5px 0 0 33px; font-size: 14px;'>{$m['mensagem']}</p>
        </div>";
    }
    exit;
}
?>