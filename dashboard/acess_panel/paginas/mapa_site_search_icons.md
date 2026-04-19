dentro do foreach:

    } else {
        // Lógica para selecionar o ícone correto baseado na URL ou Título
        $url_atual = $res['info']; // Caminho vindo do banco (mapa_site)
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
            $img = $icon_path . "page.png"; // O seu default
        }

        $link = $baseUrl . "/" . ltrim($url_atual, '/');
        $subtitulo = "Página do Sistema";
    }
######################### Exemplo (de um backup): ###################################

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
        // Para Membros
        $img = !empty($res['info']) ? $baseUrl . "/site/" . $res['info'] : $baseUrl . "/site/img/default-user.png";
        $link = $baseUrl . "/perfil_membro.php?id=" . $res['id'];
        $subtitulo = "Corvo";
    } else {
        // Lógica para selecionar o ícone correto baseado na URL ou Título
        $url_atual = $res['info']; // Caminho vindo do banco (mapa_site)
        $icon_path = $baseUrl . "/site/img/icons/";

        if (strpos($url_atual, 'perfil_do_usuario.php') !== false) {
            $img = $icon_path . "home.png";
        } elseif (strpos($url_atual, 'galeria.php') !== false) {
            $img = $icon_path . "galery.png";
        } elseif (strpos($url_atual, 'home.php') !== false) {
            $img = $icon_path . "feed.png";
        } elseif (strpos($url_atual, 'index.php') !== false) {
            $img = $icon_path . "site.png";
        } else {
            $img = $icon_path . "page.png"; // O seu default
        }

        $link = $baseUrl . "/" . ltrim($url_atual, '/');
        $subtitulo = "Página do Sistema";
    }

    echo '<a href="' . $link . '" class="res-item-header">
            <div class="res-img-container">
                <img src="' . $img . '" class="res-img-header" onerror="this.src=\'' . $baseUrl . '/site/img/default-user.png\'">
            </div>
            <div class="res-dados-header">
                <span class="res-nome-header">' . $res['nome'] . '</span>
                <span class="res-meta-header">' . $subtitulo . '</span>
        </div>
        </a>';
}