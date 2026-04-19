<?php
// perfil_membro.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php';
require_once 'site/data/user.php'; 
require_once 'site/data/user_menu.php';  
require_once 'classes/Usuarios.php';

$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();

$id_visitado = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$post_ref    = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);
$meu_id      = $_SESSION['id_usuario'];

if (!$id_visitado || ($id_visitado == $meu_id && !$post_ref)) {
    header("Location: membros.php");
    exit;
}

// 1. Busca os dados do membro visitado
$sql = "SELECT nome, foto, bio FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id_visitado]);
$membro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$membro) {
    echo "Membro não encontrado.";
    exit;
}

// 2. Busca o TEMA de quem está logado
$sql_pref = "SELECT tema FROM usuario_configuracoes WHERE id_usuario = :id";
$stmt_pref = $pdo->prepare($sql_pref);
$stmt_pref->execute([':id' => $meu_id]);
$config_logado = $stmt_pref->fetch(PDO::FETCH_ASSOC);
$tema_atual = $config_logado['tema'] ?? 'escuro';

// 3. Busca a PRIVACIDADE de quem está sendo visitado
$stmt_p = $pdo->prepare("SELECT privacidade_feed FROM usuario_configuracoes WHERE id_usuario = :id");
$stmt_p->execute([':id' => $id_visitado]);
$config_visitado = $stmt_p->fetch(PDO::FETCH_ASSOC);
$privacidade = $config_visitado['privacidade_feed'] ?? 'publico';

// 4. Lógica de Amizades (CORRIGIDO: id_usuario e id_amigo)
$amizades = null;
if ($id_visitado != $meu_id) {
    $sqlCheck = "SELECT status FROM amizades 
                 WHERE (id_usuario = :me AND id_amigo = :vi) 
                 OR (id_usuario = :vi AND id_amigo = :me) LIMIT 1";
    $check = $pdo->prepare($sqlCheck);
    $check->execute([':me' => $meu_id, ':vi' => $id_visitado]);
    $amizades = $check->fetch(PDO::FETCH_ASSOC);
}

// 5. DECISÃO FINAL: Pode ver o feed?
$pode_ver = false;
if ($meu_id == $id_visitado) {
    $pode_ver = true;
} elseif ($privacidade == 'publico') {
    $pode_ver = true;
} elseif ($privacidade == 'amigos' && isset($amizades['status']) && $amizades['status'] == 'aceito') {
    $pode_ver = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($membro['nome']) ?> - Korvado™</title>
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/css/menu.css"> 
    <link rel="stylesheet" href="site/css/perfil_membro.css">
    <link rel="stylesheet" href="site/css/perfil_global.css">	
    <link rel="stylesheet" href="site/css/modal_post.css"> 
    <link rel="stylesheet" href="site/css/editar_feed.css">
	<link rel="stylesheet" href="site/css/mute-toggle.css">
    <style>

    </style>
</head>
<body style="background: #1a1a1a; color: #c7d5e0;" class="<?= ($tema_atual === 'claro') ? 'modo-claro' : '' ?>">
    
    <div class="perfil-container">
        <div class="perfil-topo">
		    <div class="perfil-foto">
                <img src="site/<?= $membro['foto'] ?>" class="img-membro-topo" onerror="this.src='site/img/perfil/default-user.jpg'">
            </div>
			
            <div style="flex-grow: 1;">
                <h1><?= htmlspecialchars($membro['nome']) ?></h1>
                <p>Membro da Irmandade</p>
                
                <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 4px; margin-top: 10px; border: 0px solid #222;">
                    <span style="color: #67c1f5; font-size: 10px; font-weight: bold; display: block; margin-bottom: 5px; letter-spacing: 2px;">BIO</span>
                    <p style="color: inherit; font-style: italic; margin: 0; font-size: 13px;">
                        <?= !empty($membro['bio']) ? nl2br(htmlspecialchars($membro['bio'])) : "Este corvo ainda não escreveu sua história..." ?>
                    </p>
                </div>
            </div>                                     

            <div class="menu-lateral-perfil">
                <?php if ($id_visitado == $meu_id): ?>
                    <a href="areaPrivada.php" class="btn-acao" class="btn-perfil">Editar Perfil</a>
                    <a href="sair.php" class="btn-perfil" class="btn-perfil">Sair</a>
                <?php else: ?>
                    
                    <?php if (!$amizades): ?>
                        <button onclick="gerenciarAmizade(<?= $id_visitado ?>, 'adicionar')" class="btn-acao" style="background: #F91313; color: white; padding: 10px; border: none; cursor: pointer; border-radius: 4px;">Seguir</button>
                    <?php elseif (isset($amizades['status']) && $amizades['status'] == 'pendente'): ?>
                        <button class="btn-acao" style="background: #444; color: white; opacity: 0.7; padding: 10px; border: none; border-radius: 4px;" disabled>Solicitação Enviada</button>
                    <?php elseif (isset($amizades['status']) && $amizades['status'] == 'aceito'): ?>
                         <button class="btn-acao" style="background: #2a475e; color: #67c1f5; padding: 10px; border: none; border-radius: 4px;" disabled>Seguindo</button>
                    <?php endif; ?>

                    <a href="mensagens.php?com=<?= $id_visitado ?>" style="vertical-align: middle; display: flex; flex-direction: column; text-align: center; margin-top: 10px;">
                        <img src="site/img/icons/message.png" style="height: 35px; width: 42px; filter: drop-shadow(0px 0px 2px rgba(255,0,0,0.5));">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    
        <h3 class="public_h3">GALERIA DE MEMBRO</h3>
    
        <div class="grid-posts">
            <?php if($pode_ver): ?>
                <?php
                $sql_posts = "SELECT * FROM timeline WHERE usuario_id = :id ORDER BY data_post DESC";
                $stmt_p = $pdo->prepare($sql_posts);
                $stmt_p->execute([':id' => $id_visitado]);
                $posts = $stmt_p->fetchAll(PDO::FETCH_ASSOC);

                if(count($posts) > 0):
                    foreach($posts as $post): 
                       $midia = $post['imagem_post'];
                       $grad = $post['gradiente_id'];
                       $alinhamento = $post['alinhamento'] ?? 'center';
                       $classeCard = (!$midia && $grad) ? $grad : '';
                   ?>
                       <div class="card-post <?= $classeCard ?>" onclick="abrirPost(<?= $post['id_post'] ?>)" style="will-change: transform; cursor: pointer;">
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
                               <div style="padding: 10px; text-align: <?= $alinhamento ?>; font-size: 11px; color: white; height:100%; display:flex; align-items:center; justify-content:center;">
                                   <?= mb_strimwidth(strip_tags($post['conteudo'], '<b><i><u>'), 0, 100, "...") ?>
                               </div>
                           <?php endif; ?>
                       </div>
                    <?php endforeach;
                else:
                    echo "<p style='color: #555; text-align: center; width:100%; margin-top: 50px;'>Este membro ainda não registrou sombras.</p>";
                endif; ?>
            <?php else: ?>
                <div class="aviso-privado">
                    
                    <img src="site/img/icons/shield-128.ico" style="width:60px; opacity:0.2; filter: grayscale(1);">
                    <p style="margin-top:15px; color: #898e91; position: flex; align: center;">As postagens deste membro estão protegidas 🔐.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="modal-post" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9);" onclick="fecharModalPost(event)">
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

    <script src="site/js/modal_post_atualizar.js"></script>
    <script src="site/js/footer.js"></script>
    <script src="site/js/header.js"></script>	
    <script src="site/js/perfil_membro.js"></script>
	<script src="site/js/video_post.js"></script> 
	
</body>
</html>