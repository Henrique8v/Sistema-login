<?php
// site/data/pesquisa_logica_membros.php
require_once 'user.php';
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$busca = $_GET['q'] ?? '';
$meu_id = $_SESSION['id_usuario'];

// BaseUrl para caminhos de imagem no XAMPP
$baseUrl = "/Sistema-login";

// Busca os usuários ignorando a si mesmo
$sql = "SELECT id_usuario, nome, foto FROM usuarios WHERE nome LIKE :q AND id_usuario != :me LIMIT 20";
$stmt = $pdo->prepare($sql);
$stmt->execute([':q' => "%$busca%", ':me' => $meu_id]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($usuarios) == 0) {
    echo '<p style="text-align: center; color: #555; padding: 20px;">Nenhum corvo encontrado com este nome.</p>';
    exit;
}

foreach($usuarios as $user) {
    // CORREÇÃO: Nomes das colunas conforme sua nova tabela amizades
    $sqlCheck = "SELECT status, id_usuario FROM amizades 
                 WHERE (id_usuario = :me AND id_amigo = :vi) 
                 OR (id_usuario = :vi AND id_amigo = :me) LIMIT 1";
    $check = $pdo->prepare($sqlCheck);
    $check->execute([':me' => $meu_id, ':vi' => $user['id_usuario']]);
    $amizade = $check->fetch(PDO::FETCH_ASSOC);

    // Caminho da foto com aceleração de hardware via CSS Inline para performance
    $fotoPerfil = !empty($user['foto']) ? $baseUrl . "/site/" . $user['foto'] : $baseUrl . "/site/img/default-user.png";

    echo '<div class="resultado-card" style="will-change: background;">
            <div class="info-membro">
                <img src="'.$fotoPerfil.'" 
                     style="will-change: transform; touch-action: pan-y;" 
                     onerror="this.src=\''.$baseUrl.'/site/img/default-user.png\'">
                <span style="font-family: \'Cinzel\', serif; letter-spacing: 1px;">'.htmlspecialchars($user['nome']).'</span>
            </div>
            
            <div class="acoes-membro" style="display: flex; gap: 10px; align-items: center;">
                <a href="perfil_membro.php?id='.$user['id_usuario'].'" class="btn-perfil">Ver Perfil</a>';
                
    if (!$amizade) {
        // Se não há vínculo, mostra botão Adicionar
        echo '<button id="btn-add-'.$user['id_usuario'].'" 
                      onclick="adicionarAmigo('.$user['id_usuario'].')" 
                      class="btn-add">ADICIONAR</button>';
    } elseif ($amizade['status'] == 'pendente') {
        // Se o remetente fui eu, mostro "Solicitação Enviada"
        if ($amizade['id_usuario'] == $meu_id) {
            echo '<button class="btn-add" style="background:#2a475e; color:#67c1f5; cursor:default;" disabled>PENDENTE</button>';
        } else {
            // Se o remetente foi ele, convido a ver o pedido
            echo '<button class="btn-add" style="background:#f91313; cursor:pointer;" onclick="location.href=\'perfil_do_usuario.php\'">VER PEDIDO</button>';
        }
    } else {
        // Se já são amigos (aceito)
        echo '<span style="color: #67c1f5; font-size: 11px; font-weight:bold; letter-spacing: 1px;">✔ ALIADO</span>';
    }

    echo '  </div>
          </div>';
}
?>