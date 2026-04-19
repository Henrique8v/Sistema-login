<?php 
// perfil_do_usuario.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php';
require_once 'site/data/user.php';  
require_once 'site/data/notificacoes_funcoes.php'; 
require_once 'classes/Usuarios.php';

$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];
$totalNotif = contarNotificacoes($pdo, $meu_id);

// --- PADRONIZAÇÃO DE PREFERÊNCIAS (TEMA E PRIVACIDADE) ---
// Buscamos na tabela correta: usuario_configuracoes
$sql_pref = "SELECT tema, privacidade_feed FROM usuario_configuracoes WHERE id_usuario = :id";
$stmt_pref = $pdo->prepare($sql_pref);
$stmt_pref->execute([':id' => $meu_id]);
$config = $stmt_pref->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>Perfil de <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></title>
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/css/menu.css"> 
    <link rel="stylesheet" href="site/css/modal_post.css"> 
    <link rel="stylesheet" href="site/css/perfil_do_usuario.css">
	<link rel="stylesheet" href="site/css/perfil_global.css">
    <link rel="stylesheet" href="site/css/editar_feed.css">
	<link rel="stylesheet" href="site/css/mute-toggle.css">
	<link rel="stylesheet" href="site/css/notificacoes.css">
    <style>

    </style>
</head>

<body class="<?= ($tema_atual === 'claro') ? 'modo-claro' : '' ?>">

    <?php require_once 'site/data/user_menu.php'; ?>

    <div class="perfil-container">
        <div class="perfil-topo">
             <div class="perfil-foto">
                <img src="site/<?php echo $_SESSION['foto_usuario']; ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='site/img/perfil/default-user.jpg'">
            </div>           
            <div style="flex: 1;">
                <h2 style="font-size: 26px; margin: 0;"><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></h2>               
                <div class="notif-container">
                    <div id="btn-notificacoes" onclick="toggleNotif()">					    
                        <img src="site/img/icons/appointment-reminders-128_.ico" class="notif-icon" title="Notificações">
                        <?php if($totalNotif > 0): ?>
                            <span class="notif-badge"><?php echo $totalNotif; ?></span>							
                        <?php endif; ?>
                    </div>                    
                    <div id="box-notificacoes" class="box-notificacoes">
                        <div class="notif-header">					
                             <span>Notificações</span>
						     <button class="btn-fechar-notif" onclick="toggleNotif()">&times;</button>
						</div>
                        <div id="conteudo-notificacoes">						    
                            <?php include 'site/data/notificacoes_loop.php'; ?>
                        </div>
                    </div>
                </div>
                <div class="bio" style="margin-top: 15px; background: rgba(0,0,0,0.2); padding: 12px; border-radius: 4px; border: 0px solid rgba(255,255,255,0.05);">
                    <h4 style="color: #67c1f5; margin: 0 0 5px 0; text-transform: uppercase; font-size: 10px; letter-spacing: 1px;">SOBRE</h4>
                    <p style="font-size: 13px; margin: 0;"><?php echo !empty($_SESSION['bio_usuario']) ? nl2br(htmlspecialchars($_SESSION['bio_usuario'])) : "Este membro ainda não revelou sua história."; ?></p>
                </div>				
            </div>

            <div class="menu-lateral-perfil">
                <a href="editar_perfil.php" class="btn-perfil">Editar Perfil</a>
                <a href="editar_feed.php" class="btn-perfil">Editar Feed</a>
                <a href="amigos.php" class="btn-perfil">Amigos</a>
				<a href="membros.php" class="btn-perfil">Membros</a>
				<a href="sair.php" class="btn-perfil" class="btn-perfil">Sair</a>
            </div>
        </div>

        <h3 class="public_h3">Publicações</h3>
        
        <div class="grid-posts">
            <?php
            // Como este arquivo é perfil_do_usuario.php (O MEU PERFIL), 
            // eu sempre vejo meus posts, independente da privacidade.
            $pode_ver_feed = true; 

            if ($pode_ver_feed):
                $sql_posts = "SELECT * FROM timeline WHERE usuario_id = :id ORDER BY data_post DESC";
                $stmt_p = $pdo->prepare($sql_posts);
                $stmt_p->execute([':id' => $meu_id]);
                $posts = $stmt_p->fetchAll(PDO::FETCH_ASSOC);

                if(count($posts) > 0):
                    foreach($posts as $post): 
                       $midia = $post['imagem_post'];
                       $grad = $post['gradiente_id'];
                       $alinhamento = $post['alinhamento'] ?? 'center';
                       $classeCard = (!$midia && $grad) ? $grad : '';
                   ?>
                       <div class="card-post <?= $classeCard ?>" onclick="abrirPost(<?= $post['id_post'] ?>)" style="cursor: pointer;">
                           <?php if(!empty($midia)): 
                               $ext = strtolower(pathinfo($midia, PATHINFO_EXTENSION));
                           ?>
                               <?php if(in_array($ext, ['mp4', 'webm', 'ogg'])): ?>
                                   <video muted style="width:100%; height:100%; object-fit:cover; touch-action: pan-y;">
                                       <source src="site/img/posts/<?= $midia ?>" type="video/<?= $ext ?>">
                                   </video>
                               <?php else: ?>
                                   <img src="site/img/posts/<?= $midia ?>" loading="lazy" style="width:100%; height:100%; object-fit:cover; touch-action: pan-y;">
                               <?php endif; ?>
                           <?php elseif(!empty($post['conteudo'])): ?>
                               <div style="padding: 10px; text-align: <?= $alinhamento ?>; font-size: 11px; overflow: hidden; height:100%; display:flex; align-items:center; justify-content:center;">
                                   <?= mb_strimwidth(strip_tags($post['conteudo'], '<b><i><u>'), 0, 100, "...") ?>
                               </div>
                           <?php endif; ?>
                       </div>
                <?php endforeach; else: ?>
                    <p style="color: #555; text-align: center; width: 100%; padding: 40px;">Nenhuma sombra registrada.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="modal-post" onclick="fecharModalPost(event)">
        <div class="modal-post-container" onclick="event.stopPropagation()">
            <div class="modal-post-media" id="media-content"></div>
            <div class="modal-post-info">
                <div class="modal-post-header" id="post-header"></div>
                <div class="modal-post-comments" id="post-comments"></div>
                <div class="modal-post-footer">
                    <form onsubmit="postarComentario(event)">
                        <input type="hidden" id="current-post-id"> 
                        
                        <input type="hidden" id="parent-id-input" value="">
                
                        <textarea id="texto-comentario" placeholder="Escreva um comentário..." required style="width: 100%; background: #000; border: 1px solid #333; color: white; padding: 10px; border-radius: 4px; resize: none; margin-bottom: 5px;"></textarea>
						
                        <button type="submit" class="btn-acao-modal">COMENTAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>const TEMA_ATUAL = "<?php echo $tema_atual; ?>";</script>
	
    <script src="site/js/modal_post_delay.js"></script>
    <script src="site/js/modal_post_midia.js"></script>
    <script src="site/js/modal_post_respostas.js"></script> 
    <script src="site/js/modal_post_excluir.js"> </script>   
    <script src="site/js/modal_post_recursivo.js"></script>
    <script src="site/js/modal_post_data.js"></script>
    <script src="site/js/modal_post_atualizar.js"></script>
    <script src="site/js/modal_post.js"></script>

	<script src="site/js/footer.js"></script>
    <script src="site/js/header.js"></script>
    <script src="site/js/perfil_do_usuario.js"></script>
    <script src="site/js/notificacoes.js"></script>
    <script src="site/js/video_post.js"></script> 	
	
</body>
</html>