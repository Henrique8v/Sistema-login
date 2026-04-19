<?php
// Sistema-login/site/data/comentar_logica.php
require_once '../../classes/Usuarios.php';
require_once 'notificacoes_funcoes.php'; 
session_start();

// 1. Proteção: precisa estar logado
if (!isset($_SESSION['id_usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(["erro" => "Acesso negado."]);
    exit;
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$id_usuario_que_comentou = $_SESSION['id_usuario'];
$nome_usuario_que_comentou = $_SESSION['nome_usuario'];

// ==========================================================
// LÓGICA DE EXCLUSÃO
// ==========================================================
if (isset($_POST['excluir_id'])) {
    $id_comentario = (int)$_POST['excluir_id'];
    
    // Ao deletar um comentário pai, você pode decidir se deleta os filhos 
    // ou se apenas remove o pai. Aqui removemos o comentário específico.
    $sql = "DELETE FROM comentarios WHERE id_comentario = :idc AND usuario_id = :uid";
    $stmt = $pdo->prepare($sql);
    $sucesso = $stmt->execute([
        ':idc' => $id_comentario,
        ':uid' => $id_usuario_que_comentou
    ]);

    if ($sucesso) {
        echo "excluido";
    } else {
        echo "erro";
    }
    exit; 
}

// ==========================================================
// LÓGICA DE INSERÇÃO (ATUALIZADA COM PARENT_ID - RESPOSTAS)
// ==========================================================
if (isset($_POST['id_post']) && isset($_POST['comentario'])) {
    $id_post = (int)$_POST['id_post'];
    $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Recebe o ID do comentário pai se for uma resposta, caso contrário é NULL
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    if (!empty($comentario)) {
        // A. Grava o comentário ou a resposta no banco
        $sql = "INSERT INTO comentarios (post_id, usuario_id, comentario, parent_id, data_comentario) 
                VALUES (:p, :u, :c, :parent, NOW())";
        $stmt = $pdo->prepare($sql);
        
        $params = [
            ':p'      => $id_post, 
            ':u'      => $id_usuario_que_comentou, 
            ':c'      => $comentario,
            ':parent' => $parent_id
        ];
        
        if($stmt->execute($params)) {
            
            // B. LÓGICA DE NOTIFICAÇÃO
            // Se for uma resposta a outro comentário, notificamos o autor do comentário original.
            // Se for um comentário direto no post, notificamos o dono do post.
            
            if ($parent_id) {
                // Notificar o autor do comentário original (o "pai")
                $sqlAutorPai = "SELECT usuario_id FROM comentarios WHERE id_comentario = :pid";
                $stmtPai = $pdo->prepare($sqlAutorPai);
                $stmtPai->execute([':pid' => $parent_id]);
                $infoPai = $stmtPai->fetch(PDO::FETCH_ASSOC);
                $id_destino_notif = $infoPai['usuario_id'] ?? null;
                $msg_notif = "<b>{$nome_usuario_que_comentou}</b> respondeu ao seu comentário.";
            } else {
                // Notificar o dono do post
                $sqlDono = "SELECT usuario_id FROM timeline WHERE id_post = :idp";
                $stmtDono = $pdo->prepare($sqlDono);
                $stmtDono->execute([':idp' => $id_post]);
                $post_info = $stmtDono->fetch(PDO::FETCH_ASSOC);
                $id_destino_notif = $post_info['usuario_id'] ?? null;
                $msg_notif = "<b>{$nome_usuario_que_comentou}</b> comentou na sua publicação.";
            }

            // Envia a notificação se o destino não for eu mesmo
            if ($id_destino_notif && $id_destino_notif != $id_usuario_que_comentou) {
                $sqlNotif = "INSERT INTO notificacoes (id_remetente, id_destinatario, id_post_referencia, mensagem, tipo, lida) 
                             VALUES (:rem, :dest, :post_ref, :msg, 'comentario', 0)";
                
                $stmtN = $pdo->prepare($sqlNotif);
                $stmtN->execute([
                    ':rem'      => $id_usuario_que_comentou,
                    ':dest'     => $id_destino_notif,
                    ':post_ref' => $id_post, 
                    ':msg'      => $msg_notif
                ]);
            }

            echo "sucesso";
        } else {
            echo "erro";
        }
    } else {
        echo "comentario_vazio";
    }
    exit; 
}