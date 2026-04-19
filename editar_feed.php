<?php
// editar_feed.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php';
require_once __DIR__ . '/classes/Usuarios.php';
require_once 'site/data/user.php'; 
require_once 'site/data/user_menu.php'; 
// session_start();

if(!isset($_SESSION['id_usuario'])) { header("location: index.php"); exit; }

$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();

$id_logado = $_SESSION['id_usuario'];

// --- LÓGICA DE SALVAR EDIÇÃO ATUALIZADA ---
if (isset($_POST['salvar_edicao'])) {
    $id_edit = (int)$_POST['id_post'];
    // Permitimos as tags de formatação básicas, mas removemos scripts maliciosos
    $novo_texto = strip_tags($_POST['texto_editado'], '<b><i><u><br>');
    $novo_texto = addslashes($novo_texto); // Protege contra aspas que quebram o banco
    
    // Pega os valores dos inputs hidden preenchidos pelo JS
    $alinhamento = $_POST['alinhamento_edit'] ?? 'left';
    $gradiente = $_POST['gradiente_edit'] ?? '';
    
    // UPDATE com todas as colunas de estilo
    $sql = "UPDATE timeline SET 
            conteudo = :c, 
            alinhamento = :ali, 
            gradiente_id = :grad 
            WHERE id_post = :id_p AND usuario_id = :id_u";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':c'    => $novo_texto, 
        ':ali'  => $alinhamento, 
        ':grad' => $gradiente, 
        ':id_p' => $id_edit, 
        ':id_u' => $id_logado
    ]);
    
    header("location: editar_feed.php?sucesso=1");
    exit;
}

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['del'])) {
    $id_del = (int)$_GET['del'];
    $pdo->prepare("DELETE FROM timeline WHERE id_post = :id_p AND usuario_id = :id_u")
        ->execute([':id_p' => $id_del, ':id_u' => $id_logado]);
    header("location: editar_feed.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Feed - Ravenismo</title>
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/css/menu.css">
    <link rel="stylesheet" href="site/css/modal_post.css">
    <link rel="stylesheet" href="site/css/editar_feed.css">
    <link rel="stylesheet" href="site/css/perfil_global.css">	
    <link rel="stylesheet" href="site/css/modalEdit.css"> 	
    <style>       
	
    </style>
</head>
<body>

<div id="pageContent">
    <h1 style="color: #F91313; text-align: center;">Gerenciar Feed</h1>

<div class="publish-container">
    <form action="timeline_logica.php" method="POST" enctype="multipart/form-data">
        <div class="toolbar">
            <div style="display: flex; gap: 8px;">
                <button type="button" class="format-btn" onclick="inserirTag('b')">B</button>
                <button type="button" class="format-btn" onclick="inserirTag('i')">I</button>
                <button type="button" class="format-btn" onclick="inserirTag('u')">U</button>
                
                <span class="separator"></span>
        
                <button type="button" class="format-btn" onclick="alinharTexto('left')">⫷</button>
                <button type="button" class="format-btn" onclick="alinharTexto('center')">⫸⫷</button>
                <button type="button" class="format-btn" onclick="alinharTexto('right')">⫸</button>
            </div>
        
            <div class="gradient-selector">
                <div class="grad-opt none" onclick="setGradient('')" title="Sem fundo"></div>
                <div class="grad-opt g1" onclick="setGradient('grad-fire')" title="Fogo"></div>
                <div class="grad-opt g2" onclick="setGradient('grad-void')" title="Vazio"></div>
                <div class="grad-opt g3" onclick="setGradient('grad-blood')" title="Sangue"></div>
                <input type="hidden" name="gradiente_id" id="input-gradiente" value="">
            </div>
        </div>
        
        <textarea name="conteudo" id="main-textarea" placeholder="O que a Fênix revela hoje?"></textarea>
                
        <div class="publish-footer">
            <label class="btn-upload">
                <span>📷 Mídia</span> 
                <input type="file" name="imagem" id="file-upload" style="display:none;">
            </label>
            
            <span id="file-name-display" style="color: #666; font-size: 12px; margin-left: 10px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
            
            <button type="submit" name="postar" class="btn-publicar">PUBLICAR</button>
        </div>
    </form>
</div>

    <div class="grid-feed">
        <?php
        $sql = "SELECT * FROM timeline WHERE usuario_id = :id ORDER BY data_post DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id_logado]);
        while($post = $stmt->fetch(PDO::FETCH_ASSOC)):
            $midia = $post['imagem_post'];
            $grad = $post['gradiente_id'];
            $classeGrad = (!$midia && $grad) ? $grad : '';
            
            // Lógica para detectar se é vídeo
            $extensao = $midia ? strtolower(pathinfo($midia, PATHINFO_EXTENSION)) : '';
            $e_video = in_array($extensao, ['mp4', 'webm', 'ogg']);
        ?>
            <div class="post-card <?= $classeGrad ?>" onclick="abrirPost(<?= $post['id_post'] ?>)">
                <div class="btn-controls">
                    <button type="button" class="btn-mini btn-abrir-editor" 
                            data-id="<?= $post['id_post'] ?>" 
                            data-texto="<?= htmlspecialchars($post['conteudo'], ENT_QUOTES, 'UTF-8') ?>" 
                            data-img="<?= $midia ?>" 
                            data-grad="<?= $grad ?>"
                            onclick="event.stopPropagation();"> EDITAR
                    </button>
                    <a href="editar_feed.php?del=<?= $post['id_post'] ?>" class="btn-mini" style="color: #ff4d4d;" onclick="event.stopPropagation();">X</a>
                </div>
                  <?php if($midia): ?>
                    <?php if($e_video): ?>
                        <video src="site/img/posts/<?= $midia ?>" style="width:100%; height:100%; object-fit:cover;" muted></video>
                        <div style="position:absolute; bottom:5px; right:5px; color:white; text-shadow:1px 1px 2px black;">▶</div>
                    <?php else: ?>
                        <img src="site/img/posts/<?= $midia ?>" style="width:100%; height:100%; object-fit:cover;">
                    <?php endif; ?>
                <?php else: ?>
                    <div style="padding: 15px; text-align: center; font-size: 12px; height:100%; display:flex; align-items:center; justify-content:center;">
                        <?= mb_strimwidth(strip_tags($post['conteudo']), 0, 100, "...") ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
	
</div>

<div id="modalEdit">
    <div class="edit-box">
        <div class="edit-left" id="edit-preview" style="background: #050505; display: flex; align-items: center; justify-content: center;">
            </div>

        <div class="edit-right">
            <h3 style="color: #F91313; margin-top: 0; font-family: 'Cinzel', serif;">Editar Publicação</h3>
            
            <form method="POST" action="editar_feed.php" style="flex:1; display:flex; flex-direction:column;">
                <div class="toolbar" style="margin-bottom: 10px; padding: 5px; background: #111; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <div style="display: flex; gap: 5px;">
                        <button type="button" class="format-btn" onclick="inserirTagEdicao('b')"><b>B</b></button>
                        <button type="button" class="format-btn" onclick="inserirTagEdicao('i')"><i>I</i></button>
                        <button type="button" class="format-btn" onclick="inserirTagEdicao('u')"><u>U</u></button>
                    </div>
                    <span class="separator">|</span>
                    <div style="display: flex; gap: 5px;">
                        <button type="button" class="format-btn" onclick="alinharEdicao('left')">⫷</button>
                        <button type="button" class="format-btn" onclick="alinharEdicao('center')">⫸⫷</button>
                        <button type="button" class="format-btn" onclick="alinharEdicao('right')">⫸</button>
                    </div>
                    <span class="separator">|</span>
                    <div class="gradient-selector">
                        <div class="grad-opt none" onclick="setGradientEdicao('')"></div>
                        <div class="grad-opt g1" onclick="setGradientEdicao('grad-fire')"></div>
                        <div class="grad-opt g2" onclick="setGradientEdicao('grad-void')"></div>
                        <div class="grad-opt g3" onclick="setGradientEdicao('grad-blood')"></div>
                    </div>
                </div>

                <textarea name="texto_editado" id="edit-texto" class="main-textarea" style="flex:1; min-height:150px; margin-bottom:15px;"></textarea>
                
                <input type="hidden" name="id_post" id="edit-id">
                <input type="hidden" name="alinhamento_edit" id="alinhamento_edit">
                <input type="hidden" name="gradiente_edit" id="gradiente_edit">

                <div style="display:flex; gap:10px;">
                    <button type="submit" name="salvar_edicao" class="btn-publicar" style="flex:1;">SALVAR</button>
                    <button type="button" onclick="fecharEdicao()" class="btn-upload" style="flex:1; justify-content:center;">CANCELAR</button>
                </div>
            </form>
        </div>
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
                    <textarea id="texto-comentario" placeholder="Escreva um comentário..." required style="width: 100%; background: #000; border: 1px solid #333; color: white; padding: 10px; border-radius: 4px; resize: none; margin-bottom: 5px;"></textarea>
                    <button type="submit" class="btn-acao-modal">COMENTAR</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

</script>

    <script src="site/js/editar_feed.js"></script> 
    <script src="site/js/modalEdit.js"></script>
    <script src="site/js/modal_post.js"></script>
	<script src="site/js/footer.js"></script>
    <script src="site/js/header.js"></script>
	<script src="site/js/video_post.js"></script> 
</body>
</html>