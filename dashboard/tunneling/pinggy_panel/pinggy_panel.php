<?php
header('Content-Type: text/html; charset=utf-8');
$python_bin = "C:/Users/lorde/AppData/Local/Programs/Python/Python313/python.exe";
// Caminho atualizado para a nova pasta pinggy_panel
$base_path  = "C:/xampp/htdocs/Sistema-login/dashboard/tunneling/pinggy_panel/";

$status_file = $base_path . "status.txt";
$status_rastros = $base_path . "status_rastros.txt";

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'kill_pinggy') {
        // Encerra apenas o processo que executa o script do túnel usando WMIC
        shell_exec('wmic process where "commandline like \'%executar_pinggy.py%\'" delete');
        shell_exec("taskkill /F /IM ssh.exe /T");
        file_put_contents($status_file, "OFFLINE");
    }

    if ($action === 'kill_rastros') {
        // Encerra apenas o processo que executa o monitor de rastros usando WMIC
        shell_exec('wmic process where "commandline like \'%monitor_tunnel.py%\'" delete');
        file_put_contents($status_rastros, "OFFLINE");
    }

    if ($action === 'clear_logs') {
        foreach (glob($base_path . "logs/*.log") as $file) {
            file_put_contents($file, "--- LOG LIMPO PELO SISTEMA ---" . PHP_EOL);
        }
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action_val = $_POST['action'];
    $target = $base_path . ($action_val === 'rastros' ? "monitor_tunnel.py" : "executar_pinggy.py");
    pclose(popen("start /B \"\" \"$python_bin\" \"$target\"", "r"));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Brotherhood</title>
    <link rel="stylesheet" href="pinggy_painel.css">
    <script src="pinggy_painel.js" defer></script>
</head>
<body>
<div class="panel" id="main-panel">
    <h2>SISTEMA: <span id="status-text-global">OFFLINE</span></h2>
    
    <div class="url-display-box">
        <div class="url-item">
            <small>SISTEMA LOGIN:</small><br>
            <a id="link-main" href="#" target="_blank" class="url-link-main">AGUARDANDO TÚNEL...</a>
        </div>
        <div class="url-item">
            <small>PERFIL USUÁRIO:</small><br>
            <a id="link-perfil" href="#" target="_blank" class="url-link-perfil">AGUARDANDO TÚNEL...</a>
        </div>
    </div>

    <div class="control-grid">
        <div class="control-group">
            <form method="POST" target="hidden_frame" id="form-rastros">
                <input type="hidden" name="action" value="rastros">
                <button type="submit" class="btn-rastros">Ligar Rastros</button>
            </form>
            <button id="btn-off-rastros" onclick="systemAction('kill_rastros')" class="btn-off" style="display:none;">Desligar Rastros</button>
        </div>
        <div class="control-group">
            <form method="POST" target="hidden_frame" id="form-pinggy">
                <input type="hidden" name="action" value="pinggy">
                <button type="submit" class="btn-rastros btn-pinggy">Ligar Túnel</button>
            </form>
            <button id="btn-off-pinggy" onclick="systemAction('kill_pinggy')" class="btn-off" style="display:none;">Desligar Túnel</button>
        </div>
    </div>

    <button onclick="systemAction('clear_logs')" class="btn-clear" style="margin-top:15px;">Limpar Histórico de Logs</button>
    <iframe name="hidden_frame" style="display:none;"></iframe>

    <div class="grid-terms">
        <div class="term-container">
            <small style="color: #444;">MONITOR STATUS: <span id="status-rastros-label">OFFLINE</span></small>
            <div id="log-rastros" class="term-box">...</div>
        </div>
        <div class="term-container">
            <small style="color: #444;">TÚNEL STATUS: <span id="status-pinggy-label">OFFLINE</span></small>
            <div id="log-pinggy" class="term-box">...</div>
        </div>
    </div>
</div>
</body>
</html>