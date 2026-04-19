<?php
// pedidos_amg.php
require_once 'site/data/user.php'; 
require_once 'classes/Usuarios.php';
$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();
$meu_id = $_SESSION['id_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Amizade - Korvado™</title>
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/css/menu.css">
    <link rel="stylesheet" href="style/amigos.css">
<style>
    .circulo-amigos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .card-amigo {
        background: rgba(0,0,0,0.6);
        border: 1px solid #222;
        padding: 25px;
        text-align: center;
        border-radius: 8px;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: transform, box-shadow;
    }
    .card-amigo:hover { 
        border-color: #F91313;
        box-shadow: 0 0 20px rgba(249, 19, 19, 0.1);
        transform: translateY(-5px);
    }

    /* Estilo Base dos Botões */
    .btn-aceitar, .btn-recusar {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        font-family: 'Cinzel', serif;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        font-size: 12px;
        margin-top: 5px;
        will-change: transform, filter;
    }

    /* Botão ACEITAR: Brilho Vermelho */
    .btn-aceitar {
        background: #F91313;
        color: white;
        box-shadow: 0 0 5px rgba(249, 19, 19, 0.3);
    }
    .btn-aceitar:hover {
        background: #ff1a1a;
        box-shadow: 0 0 15px rgba(249, 19, 19, 0.7);
        transform: scale(1.03);
        filter: brightness(1.2);
    }

    /* Botão RECUSAR: Brilho Cinza/Sombrio */
    .btn-recusar {
        background: #222;
        color: #888;
        border: 1px solid #333;
    }
    .btn-recusar:hover {
        background: #333;
        color: #fff;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
        transform: scale(1.03);
        border-color: #555;
    }

    .img-pedido {
        width: 85px; height: 85px; 
        border-radius: 50%; 
        object-fit: cover; 
        border: 2px solid #F91313;
        margin-bottom: 10px;
        will-change: transform;
        touch-action: pan-y;
        transition: transform 0.3s ease;
    }
    .card-amigo:hover .img-pedido {
        transform: scale(1.05);
    }

    .fade-out { 
        opacity: 0; 
        transform: scale(0.8) !important; 
        transition: 0.5s; 
    }
</style>
</head>
<body style="background-color: #1a1a1a; color: white;">

    <?php require_once 'site/data/user_menu.php'; ?>

    <div style="margin-top: 100px; padding: 20px; max-width: 1000px; margin-left: auto; margin-right: auto;">
        <a href="amigos.php" class="btn-acao" style="background: #333; color: white; margin-bottom: 20px; max-width: 200px; text-decoration: none; display: block; padding: 10px; border-radius: 4px; text-align: center;">
            <span style="color: #67c1f5;">←</span> Voltar para Amigos
        </a>

        <h2 style="font-family: 'Cinzel', serif; border-bottom: 2px solid #F91313; padding-bottom: 10px; letter-spacing: 2px;">PEDIDOS DE ALIANÇA</h2>
        
        <div class="circulo-amigos">
            <?php
            // CORREÇÃO: Seleciona quem enviou convite (id_usuario) para MIM (id_amigo)
            $sql = "SELECT u.id_usuario, u.nome, u.foto FROM usuarios u 
                    JOIN amizades a ON a.id_usuario = u.id_usuario 
                    WHERE a.id_amigo = :uid AND a.status = 'pendente'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':uid' => $meu_id]);
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($pedidos):
                foreach($pedidos as $p):
                    $foto = !empty($p['foto']) ? "site/".$p['foto'] : "site/img/default-user.png";
            ?>
                <div class="card-amigo" id="pedido-<?= $p['id_usuario'] ?>">
                    <img src="<?= $foto ?>" class="img-pedido" onerror="this.src='site/img/default-user.png'">
                    <p style="margin: 15px 0; font-weight: bold; font-size: 14px; font-family: 'Cinzel', serif;"><?= htmlspecialchars($p['nome']) ?></p>
                    
                    <button class="btn-aceitar" onclick="decidir(<?= $p['id_usuario'] ?>, 'aceitar')">ACEITAR</button>
                    <button class="btn-recusar" onclick="decidir(<?= $p['id_usuario'] ?>, 'recusar')">RECUSAR</button>
                </div>
            <?php endforeach; else: ?>
                <p style="color: #555; grid-column: 1 / -1; text-align: center; padding: 60px; font-style: italic;">Nenhum novo corvo bateu à sua porta.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function decidir(id, acao) {
        const card = document.getElementById('pedido-' + id);
        let fd = new FormData();
        
        // Sincronizado com os nomes de POST que criamos no amizade_logica.php
        if(acao === 'aceitar') {
            fd.append('aceitar_id', id);
        } else {
            fd.append('recusar_id', id);
        }

        fetch('site/data/amizade_logica.php', { method: 'POST', body: fd })
        .then(res => res.text())
        .then(data => {
            card.classList.add('fade-out');
            setTimeout(() => { card.remove(); }, 500);
            
            if(typeof mostrarAviso === "function") {
                mostrarAviso(acao === 'aceitar' ? "Aliança confirmada!" : "Pedido removido.");
            }
        });
    }
    </script>
    <script src="site/js/header.js"></script>
</body>
</html>