<?php
// korvado.php - Nervo de Redirecionamento da Estação
session_start();

// --- CHAVE DE SEGURANÇA INTEGRADA ---
// Verifica se a sessão ativa existe antes de permitir o acesso ao perfil
$logado = false;
if (isset($_SESSION['id_usuario'])) {
    $logado = true;
    // Redirecionamento automático após 2 segundos se já estiver logado
    header("refresh:2;url=perfil_do_usuario.php");
} else {
    // Se não houver sessão, prepara para retornar ao login após o carregamento visual
    header("refresh:3;url=index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciando | Korvado™</title>
    <link rel="stylesheet" href="site/css/login.css">
    <style>
        /* Ajuste específico para a estética de carregamento */
        .loading-bar {
            width: 100%;
            height: 2px;
            background: #111;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }
        .loading-progress {
            width: 50%;
            height: 100%;
            background: #ff0000;
            position: absolute;
            animation: move 1.5s infinite ease-in-out;
            box-shadow: 0 0 10px #ff0000;
        }
        @keyframes move {
            0% { left: -50%; }
            100% { left: 100%; }
        }
        .status-text {
            font-size: 10px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div id="corpo-form">
    <h1>Korvado™</h1>
    
    <div style="text-align: center; margin-top: 20px;">
        <?php if ($logado): ?>
            <p style="color: #ccc;">Sessão Identificada.</p>
            <p style="color: #ff0000; font-size: 14px; margin-top: 5px;">Sincronizando Perfil...</p>
        <?php else: ?>
            <p style="color: #555;">Nenhuma sessão ativa encontrada.</p>
            <p style="color: #ff0000; font-size: 14px; margin-top: 5px;">Redirecionando para Autenticação...</p>
        <?php endif; ?>
    </div>

    <div class="loading-bar">
        <div class="loading-progress"></div>
    </div>

    <div class="status-text">
        Estação de Acesso v1.7.0 // Brotherhood
    </div>
</div>

</body>
</html>