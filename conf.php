<?php
// Mantendo suas inclusões originais
require_once 'site/data/user.php'; 
require_once 'classes/Usuarios.php';

// Inicia a sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];

// --- PROCESSADOR AJAX ÚNICO (Tema, Privacidade, etc) ---
if (isset($_GET['ajax_update'])) {
    header('Content-Type: application/json');
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';
    
    $permitidos = ['tema', 'privacidade_feed', 'qualidade_midia'];
    
    if (in_array($campo, $permitidos)) {
        // Usando a tabela de configurações que você criou
        $sql = "UPDATE usuario_configuracoes SET $campo = :valor WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':valor' => $valor, ':id' => $meu_id]);
        echo json_encode(['status' => 'sucesso']);
    } else {
        echo json_encode(['status' => 'erro']);
    }
    exit;
}

// --- BUSCA CONFIGURAÇÕES ATUAIS ---
$sql = "SELECT * FROM usuario_configuracoes WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $meu_id]);
$config = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não existir, define o padrão
if (!$config) {
    $config = ['tema' => 'escuro', 'privacidade_feed' => 'publico', 'qualidade_midia' => 'SD'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações | RV Project</title>
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/conf/css/conf.css">
<script>
        // Define a base do projeto para todos os arquivos JS
        const baseUrl = "http://localhost:8080/Sistema-login/";
</script>	
</head>
<body class="<?= ($config['tema'] == 'claro') ? 'claro-mode modo-claro' : '' ?>">

    <div class="conf-wrapper">
        <h2 class="config-title" style="color: white; border-bottom: 1px solid #333; padding-bottom: 10px;">CONFIGURAÇÕES DO SISTEMA</h2>

        <div class="config-section">
            <h3 class="config-header">Aparência</h3>
            <div class="config-item">
                <div class="config-info">
                    <span>Modo de Exibição</span>
                    <p>Alterne entre os modos claro e escuro para todo o site.</p>
                </div>
                <select id="select-tema" onchange="updateConfig('tema', this.value)">
                    <option value="escuro" <?= $config['tema'] == 'escuro' ? 'selected' : '' ?>>Modo Escuro</option>
                    <option value="claro" <?= $config['tema'] == 'claro' ? 'selected' : '' ?>>Modo Claro</option>
                </select>
            </div>
        </div>

        <div class="config-section">
            <h3 class="config-header">Privacidade e Feed</h3>
            <div class="config-item">
                <div class="config-info">
                    <span>Visibilidade do Feed</span>
                    <p>Quem pode ver suas publicações no seu perfil?</p>
                </div>
                <select onchange="updateConfig('privacidade_feed', this.value)">
                    <option value="publico" <?= $config['privacidade_feed'] == 'publico' ? 'selected' : '' ?>>Público (Todos)</option>
                    <option value="amigos" <?= $config['privacidade_feed'] == 'amigos' ? 'selected' : '' ?>>Apenas Amigos</option>
                    <option value="privado" <?= $config['privacidade_feed'] == 'privado' ? 'selected' : '' ?>>Privado (Só eu)</option>
                </select>
            </div>
        </div>

        <div class="config-section">
            <h3 class="config-header">Mídia e Upload</h3>
            <div class="config-item">
                <div class="config-info">
                    <span>Qualidade de Upload</span>
                    <p>Reduzir resolução para economizar dados (Recomendado: SD).</p>
                </div>
                <select onchange="updateConfig('qualidade_midia', this.value)">
                    <option value="SD" <?= $config['qualidade_midia'] == 'SD' ? 'selected' : '' ?>>Baixa Resolução (SD)</option>
                    <option value="HD" <?= $config['qualidade_midia'] == 'HD' ? 'selected' : '' ?>>Alta Resolução (HD)</option>
                </select>
            </div>
        </div>

        <div class="config-section" style="margin-top: 50px;">
            <h3 class="config-header" style="color: #ff4d4d;">Exclusão de Conta</h3>
            
            <div id="bloco-deletar-inicial" class="config-item" style="border: 1px solid #a52a2a;">
                <div class="config-info">
                    <span style="color: #ff4d4d;">Encerrar Conta</span>
                    <p>Apagar perfil, fotos e mensagens permanentemente da Irmandade.</p>
                </div>
                <button onclick="toggleExclusao(true)" class="btn-perigoso">DELETAR CONTA</button>
            </div>

            <div id="confirmacao-exclusao" style="display:none; flex-direction: column; gap: 15px; padding: 25px; background: rgba(20, 0, 0, 0.4); border: 1px solid #ff4d4d; border-radius: 4px;">
                <span style="color: #ff4d4d; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Confirmação de Segurança</span>
                <p style="font-size: 13px; color: #ccc; margin-bottom: 5px;">Digite sua senha atual para confirmar a exclusão permanente.</p>
                
                <input type="password" id="senha-mestra" placeholder="Sua senha mestra" class="input-rv" style="width: 100%; max-width: 400px; padding: 12px; background: #000; color: #fff; border: 1px solid #444;">
                
                <div id="erro-exclusao" style="color: #ff4d4d; display:none; font-size: 14px; font-weight: bold; background: rgba(0,0,0,0.3); padding: 10px; border-left: 3px solid #ff4d4d;"></div>
                
                <div style="display:flex; gap:10px; margin-top: 10px;">
                    <button id="btn-confirmar-exclusao" onclick="executarExclusao()" class="btn-perigoso" style="flex: 1; max-width: 200px;">CONFIRMAR E APAGAR</button>
                    <button onclick="toggleExclusao(false)" class="btn-upload" style="flex: 1; max-width: 150px; background: #444;">CANCELAR</button>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <a href="perfil_do_usuario.php" class="btn-voltar" style="color: #67c1f5; text-decoration: none;">← Voltar ao Início</a>
        </div>
    </div>

    <script src="site/conf/js/conf.js"></script> 
</body>
</html>