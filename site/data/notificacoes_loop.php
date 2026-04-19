<?php
// notificacoes_loop.php
require_once 'notificacoes_funcoes.php';

// Conexão segura
if (!isset($pdo)) { $pdo = $u->getPdo(); }

$notificacoes = buscarNotificacoes($pdo, $_SESSION['id_usuario']);

if ($notificacoes):
    foreach ($notificacoes as $n):
        // CORREÇÃO AQUI: O seu SQL usa 'id_notificacao'
        $id_notif = isset($n['id_notificacao']) ? $n['id_notificacao'] : 0; 
        
        $lida     = $n['lida'] ?? 0;
        $tipo     = $n['tipo'] ?? '';
        $idPost   = $n['id_post_referencia'] ?? 0;
        
        // Renderização da mensagem
        $msg      = isset($n['mensagem']) ? $n['mensagem'] : '';
        
        $foto_db = !empty($n['foto']) ? $n['foto'] : "img/default-user.png";
        $foto_final = (strpos($foto_db, 'site/') === 0) ? $foto_db : "site/" . $foto_db;
?>
<div class="notif-wrapper" style="position: relative; overflow: hidden; border-bottom: 1px solid #222; background-color: #d32f2f; user-select: none;">
    
    <div class="notif-back" style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: flex-start; padding-left: 20px; color: white; z-index: 1;">
        <span style="font-family: 'Segoe UI', Arial; font-size: 10px; font-weight: 800; letter-spacing: 1px;">✕ EXCLUIR</span>
    </div>
    
    <div class="notif-item" 
         data-id="<?php echo $id_notif; ?>" 
         onmousedown="startDrag(event, this)" 
         ontouchstart="startDrag(event, this)"
         style="cursor: grab; position: relative; z-index: 2; display: flex; align-items: center; gap: 12px; padding: 12px; background: #1b2838; width: 100%; height: 100%; transition: transform 0.2s cubic-bezier(0.18, 0.89, 0.32, 1.28);">
        
        <img src="<?= $foto_final ?>" style="width: 38px; height: 38px; border-radius: 4px; object-fit: cover; border: 1px solid #333; flex-shrink: 0;" draggable="false" onerror="this.src='site/img/default-user.png'">
        
        <div style="flex: 1; pointer-events: none;">
            <p style="margin:0; font-size: 11px; color: #c7d5e0; line-height: 1.4;">
                <?php echo $msg; ?>
            </p>
            
            <div style="margin-top: 6px; pointer-events: auto;">
                <?php if (($tipo == 'amizade' || $tipo == 'pedido_amizade') && $lida == 0): ?>
                    <a href="pedidos_amg.php" style="text-decoration: none; color: #67c1f5; font-size: 10px; font-weight: bold; text-transform: uppercase;">RESPONDER PEDIDO</a>
                
                <?php elseif ($lida == 1 && (in_array($tipo, ['amizade', 'pedido_amizade', 'pedido_recusado']))): ?>
                    <span style="font-size: 9px; color: <?= ($tipo == 'pedido_recusado') ? '#ff4d4d' : '#4caf50' ?>; font-weight: bold; text-transform: uppercase;">
                        <?= ($tipo == 'pedido_recusado') ? 'PEDIDO RECUSADO' : 'PEDIDO ACEITO' ?>
                    </span>

                <?php elseif (in_array($tipo, ['like', 'comentario']) && $idPost > 0): ?>
                    <a href="perfil_do_usuario.php?post=<?= $idPost ?>" style="text-decoration: none; color: #ff4d4d; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">VER <?= ($tipo == 'like') ? 'CURTIDA' : 'COMENTÁRIO' ?></a>
                
                <?php elseif ($tipo == 'chat'): ?>
                    <a href="mensagens.php?com=<?= $n['id_remetente'] ?>" 
                       style="color: #00ff00; font-size: 9px; font-weight: bold; text-transform: uppercase; text-decoration: none;">
                       ABRIR CONVERSA
                    </a>
                <?php elseif ($tipo == 'amizade_confirmada'): ?>
                    <span style="font-size: 9px; color: #4caf50; font-weight: bold; text-transform: uppercase;">IRMANDADE FORMADA!</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>      
<?php endforeach; else: ?>
    <p style="text-align:center; color:#55677d; font-size:12px; padding:30px; font-style: italic;">Nenhuma nova sombra surgiu.</p>
<?php endif; ?>