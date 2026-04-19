<?php 
require_once 'site/data/user.php'; 
require_once 'site/data/user_menu.php'; 
require_once 'classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();
$meu_id = $_SESSION['id_usuario'];

// --- CORREÇÃO: SQL de Contagem ---
// Batendo com id_amigo e status da sua tabela 'amizades'
$sqlCount = "SELECT COUNT(*) as total FROM amizades WHERE id_amigo = :uid AND status = 'pendente'";
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute([':uid' => $meu_id]);
$resCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
$totalPendentes = $resCount['total'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Círculo de Amigos - Korvado™</title>
    <link rel="stylesheet" href="site/css/menu.css">
	<link rel="stylesheet" href="site/css/template.css">
	<link rel="stylesheet" href="site/css/amigos.css">
    <style>
        /* Estilos mantidos conforme sua necessidade */
    </style>
</head>
<body style="background-color: #0a0a0a; color: #c7d5e0;">

    <?php require_once 'site/data/user_menu.php'; ?>
	
<div style="margin: 0; padding: 20px; max-width: 100%; height: 85vh;">
    
    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <a href="pedidos_amg.php" class="btn-acao" style="background: #343a40; color: white; flex: 1; max-width: 250px; position: relative;">
            <?php if ($totalPendentes > 0): ?>
                <span class="ponto-alerta" style="color: #F91313;">●</span>
                PEDIDOS (<?= $totalPendentes ?>)
            <?php else: ?>
                <span style="color: #555; margin-right: 5px;">○</span>
                PEDIDOS (0)
            <?php endif; ?>
        </a>
    </div>

    <div class="section-title">
        <h2 style="margin: 0; color: white;">Sua Irmandade</h2>
        <span style="color: #F91313; font-size: 12px; letter-spacing: 2px;">ALIANÇAS ATIVAS</span>
    </div>
        
    <div class="circulo-amigos">
        <?php
        // --- CORREÇÃO: Query Otimizada da Irmandade ---
        // Ajustado para os nomes: id_amigo, id_usuario (plural 'amizades')
        $sql = "SELECT u.id_usuario, u.nome, u.foto FROM usuarios u 
                JOIN amizades a ON (a.id_amigo = u.id_usuario OR a.id_usuario = u.id_usuario)
                WHERE (a.id_usuario = :uid OR a.id_amigo = :uid) 
                AND u.id_usuario != :uid 
                AND a.status = 'aceito'
                ORDER BY u.nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':uid' => $meu_id]);
        $amigos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($amigos):
            foreach($amigos as $amigo):
                $foto = !empty($amigo['foto']) ? "site/".$amigo['foto'] : "site/img/default-user.png";
        ?>
            <div class="card-amigo" id="amigo-<?= $amigo['id_usuario'] ?>">
                <img src="<?= $foto ?>" alt="<?= htmlspecialchars($amigo['nome']) ?>" style="will-change: transform; touch-action: pan-y;">
                <p style="margin: 15px 0 5px; font-weight: bold; font-size: 15px; color: white;"><?= htmlspecialchars($amigo['nome']) ?></p>
                
                <a href="perfil_membro.php?id=<?= $amigo['id_usuario'] ?>" style="color: #67c1f5; text-decoration: none; font-size: 12px; transition: 0.3s;">Ver Perfil</a>
                
                <div style="margin-top: 5px;">
                    <button class="btn-exilar" onclick="exilarAmigo(<?= $amigo['id_usuario'] ?>)">EXILAR</button>
                </div>
            </div>
        <?php 
            endforeach;
        else:
            echo "<div style='grid-column: 1/-1; text-align: center; padding: 60px; background: rgba(0,0,0,0.2); border-radius: 8px; border: 1px dashed #333;'>
                    <p style='color: #555; font-style: italic;'>Ainda não há aliados nesta irmandade...</p>
                    <a href='membros.php' style='color: #F91313; text-decoration: none; font-size: 13px;'>Procurar membros</a>
                  </div>";
        endif; 
        ?>
    </div>
</div>

<script>
function exilarAmigo(id) {
        const card = document.getElementById('amigo-' + id);
        let fd = new FormData();
        fd.append('remover_id', id);

        fetch('site/data/amizade_logica.php', { method: 'POST', body: fd })
        .then(res => res.text())
        .then((data) => {
            // Se o seu amizade_logica retornar "removido" conforme o código enviado
            card.style.opacity = '0.3'; // Feedback visual imediato
            setTimeout(() => { 
                card.remove(); 
                if(document.querySelectorAll('.card-amigo').length === 0) {
                    location.reload();
                }
            }, 500);
        });
}
</script>
<script src="site/js/footer.js"></script>
<script src="site/js/header.js"></script>
</body>
</html>