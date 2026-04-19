<?php 
require_once '../data/user.php'; 
require_once '../data/user_menu.php'; 
?>
     
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logo.png">
    <title>Repositórios</title>
    
    <link rel="stylesheet" href="../css/template.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/repo.css"> 
	
    <style>
    </style>
	
</head>

<body>
    <div class="container-full">
        <h1 class="header-title">R E C U R S O S</h1>

        <div class="repo-grid">
            <?php
            $projetos = [                
                "Postagens do RV" => "../rv_project_inc/posts.php",
				"My_Dashboard" => "../../dashboard/my_dashboard.php",
				"Forúm" => "#",
                "Oficina" => "#",
                "Ferramentas" => "#",
                "Feed da Comunidade" => "#",
            ];
            
            foreach($projetos as $nome => $link): ?>
                <a href="<?= $link ?>" class="repo-card">
                    <span class="repo-text"><?= $nome ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="footer-credits">
            <i>Santuário criado pelo <font color="red">Lorde Raveno</font> [2026]</i>
            <br>
            <small>&copy; Rorvado.</small>
        </div>
    </div>
	
    <script src="../js/header.js"></script>
    <script src="../js/footer.js"></script>
    <script src="../js/search.js"></script>
</body>
</html>