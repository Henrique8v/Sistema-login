<?php
// Sistema-login/site/data/ler_msg.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php';
require_once 'user.php';
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];
$amigo_id = filter_input(INPUT_GET, 'id_para', FILTER_SANITIZE_NUMBER_INT);

if (!$amigo_id) exit;

// Busca as mensagens e tenta buscar a mensagem citada se existir
$sql = "SELECT m.*, 
        (SELECT mensagem FROM mensagens_privadas WHERE id = m.citacao_id) as msg_citada,
        (SELECT gif_url FROM mensagens_privadas WHERE id = m.citacao_id) as gif_citado
        FROM mensagens_privadas m
        WHERE (m.remetente_id = :me AND m.destinatario_id = :amigo) 
        OR (m.remetente_id = :amigo AND m.destinatario_id = :me) 
        ORDER BY m.data_envio ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':me' => $meu_id, ':amigo' => $amigo_id]);
$mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($mensagens as $m) {
    $sou_eu = ($m['remetente_id'] == $meu_id);
    $classe = $sou_eu ? 'me' : 'them';
    $msg_id = $m['id'];
    
    // Escapar texto para o JavaScript não quebrar
    $texto_para_js = htmlspecialchars($m['mensagem'], ENT_QUOTES, 'UTF-8');
    
    echo "<div class='msg {$classe}' id='msg-{$msg_id}'>";
    
    // Ícone de Resposta (Seta de Reply) - Clicar aqui ativa a citação
    echo "<span onclick='prepararResposta({$msg_id}, \"{$texto_para_js}\")' 
                style='position:absolute; top:2px; right:5px; font-size:10px; cursor:pointer; opacity:0.3;' 
                title='Responder'>
            <i class='fa-solid fa-reply'></i>
          </span>";

    // Exibe Citação (Se a mensagem atual for uma resposta a outra)
    if ($m['citacao_id']) {
        $conteudo_citado = !empty($m['msg_citada']) ? $m['msg_citada'] : "GIF/Mídia";
        echo "<div class='citacao-msg' style='background: rgba(0,0,0,0.2); border-left: 3px solid var(--accent-color); padding: 5px; margin-bottom: 8px; font-size: 0.8em;'>
                <small><i>Respondendo a:</i></small><br>" . htmlspecialchars($conteudo_citado) . "
              </div>";
    }

    // Conteúdo da Mensagem
    if (!empty($m['mensagem'])) {
        echo "<div>" . htmlspecialchars($m['mensagem']) . "</div>";
    }

    // GIF (Se houver)
    if (!empty($m['gif_url'])) {
        echo "<img src='{$m['gif_url']}' style='max-width:100%; border-radius:5px; margin-top:5px;'>";
    }

    // Opção de apagar (apenas nas minhas)
    if ($sou_eu) {
        echo "<div style='text-align:right; margin-top:5px;'>
                <span onclick='excluirMensagem({$msg_id})' style='font-size:9px; cursor:pointer; color:#F91313; opacity:0.6;'>excluir</span>
              </div>";
    }

    echo "</div>";
}