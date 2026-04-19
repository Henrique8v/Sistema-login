<?php
// Sistema-login/timeline_logica.php

require_once __DIR__ . '/classes/Usuarios.php';
session_start();

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

if ($pdo === null) {
    die("Erro crítico: Falha na conexão com o banco de dados.");
}

// --- AÇÃO 1: PROCESSAR NOVA POSTAGEM ---
if (isset($_POST['postar']) && isset($_SESSION['id_usuario'])) {
    $conteudo = addslashes($_POST['conteudo']);
    $id_usuario = $_SESSION['id_usuario'];
    $gradiente_id = !empty($_POST['gradiente_id']) ? $_POST['gradiente_id'] : null;
    $alinhamento = !empty($_POST['alinhamento']) ? $_POST['alinhamento'] : 'center'; // NOVO
    $nome_arquivo = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $formatos_aceitos = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm'];

        if (in_array($extensao, $formatos_aceitos)) {
            $nome_arquivo = md5(uniqid()) . "." . $extensao;
            $diretorio = "site/img/posts/";
            if (!file_exists($diretorio)) { mkdir($diretorio, 0777, true); }
            move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $nome_arquivo);
        }
    }

    if (!empty($conteudo) || $nome_arquivo) {
        $grad_final = ($nome_arquivo) ? null : $gradiente_id;
        
        // Query atualizada com alinhamento
        $sql = "INSERT INTO timeline (usuario_id, conteudo, imagem_post, gradiente_id, alinhamento) 
                VALUES (:uid, :cont, :img, :grad, :align)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':uid' => $id_usuario, 
            ':cont' => $conteudo, 
            ':img' => $nome_arquivo, 
            ':grad' => $grad_final,
            ':align' => $alinhamento
        ]);
    }
    
    header("location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// --- AÇÃO 2: BUSCAR E EXIBIR POSTS ---
if (isset($_GET['buscar_posts'])) {
    $sql = "SELECT t.*, u.nome, u.foto,
            (SELECT COUNT(*) FROM curtidas WHERE post_id = t.id_post) as total_likes
            FROM timeline t 
            JOIN usuarios u ON t.usuario_id = u.id_usuario 
            ORDER BY t.data_post DESC LIMIT 30";
    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mapeamento de Gradientes para CSS
    $gradientes = [
        'g1' => 'linear-gradient(45deg, #f093fb 0%, #f5576c 100%)',
        'g2' => 'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)',
        'g3' => 'linear-gradient(45deg, #43e97b 0%, #38f9d7 100%)',
        'g4' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
    ];

    if (count($posts) > 0) {
        echo "<div id='masonry-grid' style='display: flex; flex-wrap: wrap; gap: 15px; justify-content: flex-start;'>";

        foreach ($posts as $p) {
            $foto_perfil = !empty($p['foto']) ? "site/" . $p['foto'] : "site/img/default-user.png";
            $arquivo = $p['imagem_post'];
            $idPost = $p['id_post'];
            $likes = $p['total_likes'];
            $gradId = $p['gradiente_id'];

            $estiloFundo = "background: #111;";
            if(!$arquivo && isset($gradientes[$gradId])) {
                $estiloFundo = "background: " . $gradientes[$gradId] . ";";
            }

            echo "
            <div class='post-card' onclick='abrirPost({$idPost})' style='width: calc(33.33% - 10px); {$estiloFundo} border-radius: 8px; overflow: hidden; cursor: pointer; border: 1px solid #222; transition: 0.3s; margin-bottom: 5px; aspect-ratio: 1/1; display: flex; flex-direction: column;'>";
                
                if ($arquivo) {
                    $ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                    if (in_array($ext, ['mp4', 'webm'])) {
                        echo "<video muted style='width: 100%; height: 100%; object-fit: cover;'><source src='site/img/posts/{$arquivo}'></video>";
                    } else {
                        echo "<img src='site/img/posts/{$arquivo}' style='width: 100%; height: 100%; object-fit: cover;'>";
                    }
                } else {
                    echo "<div style='padding: 20px; flex: 1; display: flex; align-items: center; justify-content: center; text-align: center; font-weight: bold; font-size: 14px; color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);'>".htmlspecialchars($p['conteudo'])."</div>";
                }

                echo "
                <div style='padding: 8px; background: rgba(0,0,0,0.6); display: flex; justify-content: space-between; align-items: center;'>
                    <div style='display: flex; align-items: center; gap: 6px;'>
                        <img src='{$foto_perfil}' style='width: 18px; height: 18px; border-radius: 50%; object-fit: cover;'>
                        <span style='color: #eee; font-size: 10px; font-weight: bold;'>{$p['nome']}</span>
                    </div>
                    <span style='color: #F91313; font-size: 10px;'>❤️ {$likes}</span>
                </div>
            </div>";
        }
        echo "</div>";
    } else {
        echo "<p style='color: #444; text-align: center; width: 100%;'>Nenhuma atividade na Ordem.</p>";
    }
    exit;
}
?>