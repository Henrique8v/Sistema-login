<?php
// Sistema-login/site/data/visualizar_post.php
require_once '../../classes/Usuarios.php';
session_start();

// Garante que a resposta seja SEMPRE JSON para o modal_post.js não quebrar
header('Content-Type: application/json');

// Inicia o buffer para evitar que qualquer erro de PHP suje o JSON
ob_start();

if (!isset($_GET['id_post'])) {
    ob_clean();
    echo json_encode(["erro" => "ID do post não fornecido."]);
    exit;
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$id_post = (int)$_GET['id_post'];
$meu_id = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

try {
    // 1. BUSCAR DADOS DO POST, AUTOR E CONTAGEM DE LIKES
    $sqlPost = "SELECT t.*, u.nome as autor, u.foto as autor_foto, u.id_usuario as id_autor,
                (SELECT COUNT(*) FROM curtidas WHERE post_id = t.id_post) as total_likes,
                (SELECT COUNT(*) FROM curtidas WHERE post_id = t.id_post AND usuario_id = :meu) as ja_curti
                FROM timeline t 
                JOIN usuarios u ON t.usuario_id = u.id_usuario 
                WHERE t.id_post = :id";

    $stmt = $pdo->prepare($sqlPost);
    $stmt->execute([':id' => $id_post, ':meu' => $meu_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        ob_clean();
        echo json_encode(["erro" => "Post não encontrado."]);
        exit;
    }
// 2. BUSCAR COMENTÁRIOS (Atualizado para incluir parent_id)
    $sqlComent = "SELECT c.*, u.nome as autor_comentario, u.foto as foto_comentario, u.id_usuario as id_autor_comentario
                  FROM comentarios c 
                  JOIN usuarios u ON c.usuario_id = u.id_usuario 
                  WHERE c.post_id = :id 
                  ORDER BY c.data_comentario ASC"; // Mantemos ASC para as respostas seguirem a ordem
    
    $stmtC = $pdo->prepare($sqlComent);
    $stmtC->execute([':id' => $id_post]);
    $comentarios = $stmtC->fetchAll(PDO::FETCH_ASSOC);

    // 3. RETORNAR TUDO EM JSON
    // Limpamos o buffer antes do echo para garantir que só o JSON saia
// No retorno do JSON, os comentários agora levarão o campo 'parent_id'
    ob_clean();
    echo json_encode([
        "post" => $post,
        "comentarios" => $comentarios,
        "meu_id" => $meu_id
    ]);

} catch (Exception $e) {
    ob_clean();
    echo json_encode(["erro" => "Erro no servidor: " . $e->getMessage()]);
}
exit;