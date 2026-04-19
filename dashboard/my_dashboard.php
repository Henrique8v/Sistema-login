<?php
header('Content-Type: text/html; charset=utf-8');

// Caminhos configurados conforme seu ambiente XAMPP
$python_bin    = "C:/Users/lorde/AppData/Local/Programs/Python/Python313/python.exe";
$script_cpp_py = "C:/xampp/htdocs/Sistema-login/scripts/python/executar_cpp.py";
// Caminho para o novo executável Korvado
$korva_exe     = "C:/xampp/htdocs/Sistema-login/korvado.exe";
$output_term   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'run_core_analise') {
        $comando = "\"$python_bin\" \"$script_cpp_py\" 2>&1";
        $output_term = shell_exec($comando);
    }

    // Lógica atualizada para executar o Binário Nativo (Electron)
    // No seu my_dashboard.php
    if ($_POST['action'] === 'run_webview_build') {
        $script_gerar = "C:/xampp/htdocs/Sistema-login/coreview/gerar_binario.py";
        
        // 1. Comando para compilar o EXE
        $comando_compilar = "\"$python_bin\" \"$script_gerar\" 2>&1";
        $output_raw = shell_exec($comando_compilar);
        $output_term = mb_convert_encoding($output_raw, "UTF-8");
    
        // 2. Se a compilação terminou, avisa o usuário
        if (file_exists($korva_exe)) {
            $output_term .= "\n\n[SUCESSO]: korvado.exe forjado na raiz!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUB // MY DASHBOARD</title>
    <link rel="stylesheet" href="my_dashboard.css">
    <style>
        /* Estilos Cibernéticos e Positivistas */
        body { 
             background-color: #000000 !important; 
             margin: 20px; padding: 0; 
             vertical-align: middle; 
             flow: vertical; 
             will-change: transform; 
             touch-action: pan-y;
             text-rendering: optimizeLegibility;
             -webkit-font-smoothing: antialiased;
             font-size: 14pt; 
        }
        html {
             background-color: #000000 !important; 
             overflow-y: auto; 
             overflow-x: hidden; 
             scrollbar-width: none;            
        }
        html::-webkit-scrollbar {
             display: none;
             width: 0;
             height: 0;            
        }
        
        .hub-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .card-service { background: #080808; border: 1px solid #111; padding: 20px; border-radius: 8px; transition: 0.3s; }
        .card-service:hover { border-color: #00ff41; box-shadow: 0 0 10px rgba(0, 255, 65, 0.2); }
        
        .gold-btn { border-color: #FFD700 !important; color: #FFD700 !important; }
        .gold-btn:hover { background: #FFD700 !important; color: #000 !important; box-shadow: 0 0 15px #FFD700; }
        
        .sciter-btn { border-color: #00BFFF !important; color: #00BFFF !important; }
        .sciter-btn:hover { background: #00BFFF !important; color: #000 !important; box-shadow: 0 0 15px #00BFFF; }
        
        .btn-nav { text-decoration: none; display: block; text-align: center; margin-bottom: 10px; }
        .term-box { 
            background: #000; 
            border: 1px solid #333; 
            padding: 15px; 
            font-size: 12px; 
            overflow-y: auto; 
            white-space: pre-wrap; 
            will-change: transform;
        }
    </style>
</head>
<body>

<div class="panel" style="border-left: 4px solid #FFD700;">
    <h2>CENTRAL | <span style="color:#FFD700;">MY DASHBOARD</span></h2>
    
    <div class="hub-grid">
        <div class="card-service">
            <small style="color:#555;">CONECTIVIDADE (VOLÁTIL):</small><br><br>
            <a href="tunneling/pinggy_panel/pinggy_panel.php" class="btn-cyber btn-nav">Interruptor Pinggy</a>
            <a href="acess_panel/acess_panel.htm" class="btn-cyber btn-nav" style="border-color:#555; color:#555;">Painel de Acesso</a>
        </div>

        <div class="card-service">
            <small style="color:#555;">CORE SERVICES (ESTÁVEL):</small><br><br>
            <form method="POST">
                <button type="submit" name="action" value="run_core_analise" class="btn-cyber gold-btn">Deep Analysis C++</button>
            </form>
			<p style="font-size: 10px; color: #444; margin-top: 10px;">
                * Caso fizer uma limpeza de cache do windows, restaure o projeto_login.sql no <a href="http://localhost:8080/phpmyadmin/">phpMyAdmin<a>
            </p>
        </div>
        
        <div class="card-service" style="border-top: 2px solid #00BFFF;">
            <small style="color:#555;">INTERFACE GRÁFICA (ELECTRON ENGINE)</small><br><br>
            <form method="POST">
                <button type="submit" name="action" value="run_webview_build" class="btn-cyber sciter-btn">
                    Criar executavel do Projeto
                </button>
            </form>
            <p style="font-size: 10px; color: #444; margin-top: 10px;">
                * Inicia o núcleo nativo Korvado.exe com suporte total a Chromium/PDO.
            </p>
        </div>      
        
    </div>

    <?php if ($output_term): ?>
    <div style="margin-top: 20px;">
        <small style="color:#75ffff;">TERMINAL DE PROCESSOS:</small>
        <div class="term-box" style="min-height: 100px; color: #dbf57d; border-color: #02f70b;">
            <?php echo htmlspecialchars($output_term); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

</body>
</html>