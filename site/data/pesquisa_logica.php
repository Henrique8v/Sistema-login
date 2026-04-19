<?php
// site/data/pesquisa_logica.php
require_once 'user.php'; 
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$termo = $_GET['q'] ?? '';
$meu_id = $_SESSION['id_usuario'];
$baseUrl = "/Sistema-login";

if (strlen($termo) < 2) { exit; }

// Query otimizada para buscar membros e páginas do mapa do site
$sql = "
    (SELECT id_usuario AS id, nome AS nome, foto AS info, 'membro' AS tipo 
     FROM usuarios 
     WHERE nome LIKE :q AND id_usuario != :me)
    UNION
    (SELECT id AS id, titulo AS nome, url AS info, 'pagina' AS tipo 
     FROM mapa_site 
     WHERE titulo LIKE :q OR descricao LIKE :q)
    LIMIT 10
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':q' => "%$termo%", ':me' => $meu_id]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($resultados as $res) {
    if ($res['tipo'] === 'membro') {
        // Lógica para Membros da Irmandade
        $img = !empty($res['info']) ? $baseUrl . "/site/" . $res['info'] : $baseUrl . "/site/img/default-user.png";
        $link = $baseUrl . "/perfil_membro.php?id=" . $res['id'];
        $subtitulo = "Corvo da Ordem";
    } else {
        // Lógica para Páginas do Sistema (Ícones Dinâmicos)
        $url_atual = $res['info']; 
        $icon_path = $baseUrl . "/site/img/icons/";

        if (strpos($url_atual, 'perfil_do_usuario.php') !== false) {
            $img = $icon_path . "home.png";
        } elseif (strpos($url_atual, 'galeria.php') !== false) {
            $img = $icon_path . "galery.png";
        } elseif (strpos($url_atual, 'home.php') !== false) {
            $img = $icon_path . "feed.png";
        } elseif (strpos($url_atual, 'index.php') !== false) {
            $img = $icon_path . "login.png";
        } elseif (strpos($url_atual, 'repo.php') !== false) {
            $img = $icon_path . "site.png";
        } elseif (strpos($url_atual, 'membros.php') !== false) {
            $img = $icon_path . "membros.png";
        } else {
            $img = $icon_path . "page.png";
        }

        $link = $baseUrl . "/" . ltrim($url_atual, '/');
        $subtitulo = "Santuário / Sistema";
    }

    // Output com otimizações de performance (will-change e touch-action)
    echo '<a href="' . $link . '" class="res-item-header">
            <div class="res-img-container">
                <img src="' . $img . '" 
                     class="res-img-header" 
                     style="will-change: transform; touch-action: pan-y;" 
                     onerror="this.src=\'' . $baseUrl . '/site/img/default-user.png\'">
            </div>
            <div class="res-dados-header">
                <span class="res-nome-header">' . htmlspecialchars($res['nome']) . '</span>
                <span class="res-meta-header" style="color: #67c1f5; font-size: 10px; text-transform: uppercase;">' . $subtitulo . '</span>
            </div>
          </a>';
}