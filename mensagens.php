<?php
// Sistema-login/mensagens.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php';
require_once 'site/data/user.php';
require_once 'classes/Usuarios.php';

$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];

$sql_pref = "SELECT tema FROM usuario_configuracoes WHERE id_usuario = :id";
$stmt_pref = $pdo->prepare($sql_pref);
$stmt_pref->execute([':id' => $meu_id]);
$config = $stmt_pref->fetch(PDO::FETCH_ASSOC);
$tema_atual = $config['tema'] ?? 'escuro';

$idPara = filter_input(INPUT_GET, 'com', FILTER_SANITIZE_NUMBER_INT) ?? 0;

$sqlContatos = "SELECT u.id_usuario, u.nome, u.foto FROM usuarios u 
                JOIN amizades a ON (a.id_amigo = u.id_usuario OR a.id_usuario = u.id_usuario)
                WHERE (a.id_usuario = :uid OR a.id_amigo = :uid) 
                AND u.id_usuario != :uid 
                AND a.status = 'aceito'
                ORDER BY u.nome ASC";
$stmtC = $pdo->prepare($sqlContatos);
$stmtC->execute([':uid' => $meu_id]);
$contatos = $stmtC->fetchAll(PDO::FETCH_ASSOC);

$nomeAmigo = "Selecione um Corvo";
if ($idPara > 0) {
    $stmtA = $pdo->prepare("SELECT nome FROM usuarios WHERE id_usuario = :id");
    $stmtA->execute([':id' => $idPara]);
    $resAmigo = $stmtA->fetch(PDO::FETCH_ASSOC);
    $nomeAmigo = $resAmigo ? $resAmigo['nome'] : "Desconhecido";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santuário de Mensagens - Korvado™</title>
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/css/menu.css"> 
	<link rel="stylesheet" href="site/css/mensagens.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>

    </style>
</head>
<body class="<?= ($tema_atual === 'claro') ? 'modo-claro' : '' ?>">
    <?php require_once 'site/data/user_menu.php'; ?>
    
<div class="msg_body">	
    <div class="main-chat">
        <div class="sidebar">
            <div class="title-amigos">Sua Irmandade</div>
            <?php foreach($contatos as $c): ?>
                <a href="mensagens.php?com=<?= $c['id_usuario'] ?>" class="contact-link <?= ($idPara == $c['id_usuario']) ? 'active-chat' : '' ?>">
                    <img src="site/<?= htmlspecialchars($c['foto']) ?>" class="img-perfil-chat" onerror="this.src='site/img/default-user.png'">
                    <span><?= htmlspecialchars($c['nome']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="chat-area">
            <div class="chat-header">
                <h3 style="margin: 0; color: #F91313;">Conversa com <?= htmlspecialchars($nomeAmigo) ?></h3>
                <?php if($idPara > 0): ?>
                    <button class="btn-limpar" onclick="limparConversa(<?= $idPara ?>)">🗑️</button>
                <?php endif; ?>
            </div>
            
            <div class="msg-box" id="msg-box">
                <?php if($idPara == 0): ?>
                    <div style="text-align: center; margin-top: 100px; color: #555;">Selecione um membro ao lado para iniciar a conversa.</div>
                <?php endif; ?>
            </div>

            <div class="reply-preview" id="reply-preview">
                <div id="reply-text" style="font-size: 12px; color: var(--text-principal);"></div>
                <button onclick="cancelarResposta()" class="btn-icon" style="color: red;">&times;</button>
            </div>

            <div class="input-area">
                <?php if($idPara > 0): ?>
                    <button class="btn-icon" title="Emojis" onclick="togglePanel('emoji-panel')"><i class="fa-regular fa-face-smile"></i></button>
                    <button class="btn-icon" title="GIFs" onclick="togglePanel('giphy-panel')"><i class="fa-solid fa-bolt"></i></button>
                    
                    <div id="emoji-panel"><div id="emoji-results"></div></div>
                    <div id="giphy-panel">
                        <input type="text" id="giphy-search" placeholder="Buscar GIF..." style="width:90%; margin:10px auto; height:30px; background:#000; color:#fff; border:1px solid #333;">
                        <div id="giphy-results"></div>
                    </div>
                <?php endif; ?>

                <input type="text" id="msg-input" placeholder="Digite sua mensagem..." <?= ($idPara == 0) ? 'disabled' : '' ?> autocomplete="off" >
                <button class="send-btn" onclick="enviar()" <?= ($idPara == 0) ? 'disabled' : '' ?>>ENVIAR</button>				
            </div>
        </div>
    </div>
</div>	

	<script>
        // Definimos a variável global ANTES de chamar o arquivo JS
        var idPara = <?= (int)$idPara ?>;
        console.log("Chat focado no ID:", idPara); // Isso ajuda a debugar no F12
    </script>

    <script src="site/js/mensagens.js?v=<?= time(); ?>"></script>
    <script src="site/js/header.js"></script>
</body>
</html>