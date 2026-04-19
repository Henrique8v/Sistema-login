O que está acontecendo é um conflito clássico de posicionamento (CSS) e atualização de estado (PHP/JS).

Quando você clica no ícone, a caixa abre mas "fica só uma linha" porque o container pai está limitando o espaço ou as notificações dentro dela estão com problemas de renderização. E o contador não some porque o PHP já imprimiu o número e o CSS que passamos antes não tinha o "gatilho" para esconder quando você abre.

Vamos resolver isso agora com o código exato para o seu perfil_do_usuario.php:

1. Correção no notificacoes.css (Para a caixa aparecer direito)
Substitua o conteúdo do seu site/css/notificacoes.css por este. Note o min-height e o display: flex, que impedem que a caixa vire "uma linha":

CSS

/* Container das Notificações */
.notif-container {
    position: relative;
    display: inline-block;
    cursor: pointer;
    vertical-align: middle;
}

/* O Balão vermelho */
.notif-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #F91313;
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: bold;
    border: 1px solid #1a1a1a;
    z-index: 11;
}

/* A Caixa de Notificações - AJUSTADA */
.box-notificacoes {
    display: none;
    position: absolute;
    top: 30px;
    left: 0; /* Alinhado à esquerda do ícone */
    width: 320px;
    min-height: 100px; /* Impede de virar apenas uma linha */
    max-height: 450px;
    background: #171a21;
    border: 1px solid #333;
    box-shadow: 0 10px 30px rgba(0,0,0,0.8);
    z-index: 9999; /* Garante que fique acima de tudo */
    overflow-y: auto;
    border-radius: 3px;
    cursor: default;
}

/* Item de Notificação Individual */
.notif-item {
    display: flex;
    padding: 12px;
    border-bottom: 1px solid #23262e;
    transition: background 0.2s;
}

.notif-item:hover {
    background: #1e2129;
}
2. Correção no notificacoes.js (Para o contador sumir ao abrir)
Para o contador sumir assim que você abre a caixa (como o sistema da Steam faz), mude seu site/js/notificacoes.js para:

JavaScript

function toggleNotif() {
    const box = document.getElementById('box-notificacoes');
    const badge = document.querySelector('.notif-badge');

    if (box) {
        if (box.style.display === 'block') {
            box.style.display = 'none';
        } else {
            box.style.display = 'block';
            // Quando abre, o contador some visualmente
            if (badge) {
                badge.style.display = 'none';
            }
        }
    }
}

// Fecha se clicar fora do container
window.addEventListener('click', function(e) {
    const container = document.querySelector('.notif-container');
    const box = document.getElementById('box-notificacoes');
    if (container && !container.contains(e.target)) {
        if (box) box.style.display = 'none';
    }
});
3. Ajuste no perfil_do_usuario.php
No seu arquivo de perfil, o ícone e a caixa de notificações estão dentro de um fluxo de texto. Vamos garantir que a caixa tenha espaço para respirar. No trecho onde está a notif-container, aplique este pequeno ajuste:

PHP

<div class="notif-container" onclick="toggleNotif()" style="margin-top: 10px; z-index: 100;">
    <img src="site/img/icons/notification.png" style="width: 24px; opacity: 0.8;" title="Notificações">
    <?php if($totalNotif > 0): ?>
        <span class="notif-badge"><?php echo $totalNotif; ?></span>
    <?php endif; ?>
    
    <div id="box-notificacoes" class="box-notificacoes">
        <div style="padding: 10px; border-bottom: 1px solid #333; font-size: 11px; font-weight: bold; background: #2a475e; color: #fff; text-transform: uppercase;">
            Notificações
        </div>
        <div id="conteudo-notificacoes">
            <?php include 'site/data/notificacoes_loop.php'; ?>
        </div>
    </div>
</div>
Por que agora vai funcionar?
A "Linha Única": No CSS antigo, o box-notificacoes provavelmente não tinha um min-height definido ou estava herdando algum display: inline do pai. Agora forçamos o tamanho e o z-index.

O Contador: O JavaScript agora captura o elemento .notif-badge e dá um display: none nele no momento do clique. Mesmo que o PHP tenha impresso o número, o JS esconde ele para o usuário.

Feedback Visual: Adicionamos o hover no CSS para ficar com cara de sistema profissional.

Dica extra: Se ao abrir a caixa ela ainda parecer "esmagada", verifique se o arquivo notificacoes_loop.php não está vazio ou com erro de PHP (como o que corrigimos antes). Se ele estiver imprimindo as notificações corretamente, o min-height: 100px vai garantir que a caixa apareça.

Teste esses três pontos e me diga se a "sombra" das notificações finalmente apareceu! Crow.

######################################################################################################

<?php
// notificacoes_loop.php
require_once 'notificacoes_funcoes.php';
$notificacoes = buscarNotificacoes($pdo, $_SESSION['id_usuario']);

if ($notificacoes):
    foreach ($notificacoes as $n):
        $caminho_foto = !empty($n['foto']) ? $n['foto'] : "img/default-user.png";
        $foto_final = (strpos($caminho_foto, 'site/') === 0) ? $caminho_foto : "site/" . $caminho_foto;
        
        $tipo = $n['tipo']; 
        $lida = $n['lida'];
        $idPost = !empty($n['id_post_referencia']) ? $n['id_post_referencia'] : 0;
?>
    <div class="notif-item" style="display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid #333; opacity: <?= ($lida == 1) ? '0.6' : '1' ?>;">
        
        <img src="<?= $foto_final ?>" style="width: 35px; height: 35px; border-radius: 3px; object-fit: cover; border: 1px solid #222;">
        
        <div style="flex: 1;">
            <p style="margin:0; font-size: 11px; color: #c7d5e0;">
                <?= $n['mensagem'] ?>
            </p>

            <div style="margin-top: 5px;">
                <?php if (($tipo == 'amizade' || $tipo == 'pedido_amizade') && $lida == 0): ?>
                    <a href="pedidos_amg.php" style="text-decoration: none; color: #67c1f5; font-size: 10px; font-weight: bold; border: 1px solid #67c1f5; padding: 2px 5px; border-radius: 2px;">RESPONDER PEDIDO</a>
                
                <?php elseif ($lida == 1 && ($tipo == 'amizade' || $tipo == 'pedido_amizade' || $tipo == 'pedido_recusado')): ?>
                    <span style="font-size: 10px; color: <?= ($tipo == 'pedido_recusado') ? '#F91313' : '#28a745' ?>; font-weight: bold; text-transform: uppercase;">
                        <?= ($tipo == 'pedido_recusado') ? 'PEDIDO RECUSADO' : 'PEDIDO ACEITO' ?>
                    </span>

                <?php /* LINK PARA CURTIDAS E COMENTÁRIOS */ ?>
                <?php elseif (($tipo == 'like' || $tipo == 'comentario') && $idPost > 0): ?>
                    <a href="perfil_do_usuario.php?post=<?= $idPost ?>" style="text-decoration: none; color: #F91313; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                        VER <?= ($tipo == 'like') ? 'CURTIDA' : 'COMENTÁRIO' ?>
                    </a>

                <?php elseif ($tipo == 'chat'): ?>
                    <a href="mensagens.php?com=<?= $n['id_remetente'] ?>" style="color: #67c1f5; font-size: 9px; font-weight: bold;">RESPONDER MENSAGEM</a>
                
                <?php elseif ($tipo == 'amizade_confirmada'): ?>
                    <span style="font-size: 9px; color: #28a745; font-weight: bold;">IRMANDADE FORMADA!</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php 
    endforeach; 
else: 
?>
    <p style="text-align:center; color:#666; font-size:12px; padding:20px;">Nenhuma nova sombra surgiu.</p>
<?php endif; ?>