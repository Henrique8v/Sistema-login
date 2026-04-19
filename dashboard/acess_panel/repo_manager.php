<?php
// Configuração de Conexão PDO [cite: 2026-02-03]
$host = 'localhost';
$db   = 'projeto_login';
$user = 'root';
$pass = '';
$port = '3306'; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// 1. Escaneamento Automático
$diretorio = "paginas/"; // Ajuste para o seu caminho real
$arquivos_no_disco = array_diff(scandir($diretorio), array('..', '.'));

foreach ($arquivos_no_disco as $arquivo) {
    if (pathinfo($arquivo, PATHINFO_EXTENSION) == 'htm' || pathinfo($arquivo, PATHINFO_EXTENSION) == 'php') {
        // Insere apenas se não existir no banco
        $stmt = $pdo->prepare("INSERT IGNORE INTO repositorio_paginas (nome_arquivo, titulo_custom) VALUES (?, ?)");
        $stmt->execute([$arquivo, $arquivo]); // Usa o nome do arquivo como título inicial
    }
}

// 2. Lógica de Paginação
$itens_por_pagina = 5;
$pagina_atual = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;

$total_itens = $pdo->query("SELECT COUNT(*) FROM repositorio_paginas")->fetchColumn();
$total_paginas = ceil($total_itens / $itens_por_pagina);

// 3. Busca de Dados
$paginas = $pdo->prepare("SELECT * FROM repositorio_paginas ORDER BY data_adicao DESC LIMIT :limit OFFSET :offset");
$paginas->bindValue(':limit', $itens_por_pagina, PDO::PARAM_INT);
$paginas->bindValue(':offset', $offset, PDO::PARAM_INT);
$paginas->execute();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Repo | Korvado</title>
    <style>
        /* ESTILO CIBERNÉTICO EXCLUSIVO [cite: 2026-02-24] */
        :root { --bg: #020202; --green: #00ff41; --cyan: #00f3ff; --card: #0d0d0d; }
        body { background: var(--bg); color: #b0b0b0; font-family: 'Segoe UI', sans-serif; padding: 20px; }
        
        .repo-container { max-width: 900px; margin: 0 auto; }
        .hacker-header { border-bottom: 2px solid var(--green); padding: 15px; text-align: center; margin-bottom: 30px; }
        
        .page-card { 
            background: var(--card); border: 1px solid #1a1a1a; 
            margin-bottom: 15px; padding: 20px; display: flex; 
            justify-content: space-between; align-items: center;
            transition: 0.3s;
        }
        .page-card:hover { border-color: var(--cyan); transform: translateX(10px); }
        
        .info h3 { color: var(--cyan); margin: 0; text-transform: uppercase; }
        .info p { font-size: 0.8rem; color: #666; margin: 5px 0; }
        
        .btn-access { 
            padding: 10px 20px; background: transparent; border: 1px solid var(--green);
            color: var(--green); text-decoration: none; font-size: 0.8rem;
            transition: 0.3s;
        }
        .btn-access:hover { background: var(--green); color: #000; box-shadow: 0 0 15px var(--green); }

        .pagination { margin-top: 30px; display: flex; gap: 10px; justify-content: center; }
        .pagination a { color: var(--cyan); text-decoration: none; padding: 5px 10px; border: 1px solid #333; }
        .pagination a.active { border-color: var(--green); color: var(--green); }
    </style>
</head>
<body>

<div class="repo-container">
    <header class="hacker-header">
        <h1>SISTEMA_REPOS_DINAMICO v1.0</h1>
    </header>

    <div class="list">
        <?php foreach ($paginas as $p): ?>
        <div class="page-card">
            <div class="info">
                <h3><?= $p['titulo_custom'] ?: $p['nome_arquivo'] ?></h3>
                <p><?= $p['subtitulo_custom'] ?: "Arquivo: " . $p['nome_arquivo'] ?></p>
                <small style="color: #444;">Adicionado em: <?= date('d/m/Y', strtotime($p['data_adicao'])) ?></small>
            </div>
            <a href="<?= $diretorio . $p['nome_arquivo'] ?>" class="btn-access">ACESSAR_MODULO</a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php for($i=1; $i<=$total_paginas; $i++): ?>
            <a href="?p=<?= $i ?>" class="<?= $i == $pagina_atual ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>