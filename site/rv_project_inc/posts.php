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
    <title>Postagens</title>
    
    <link rel="stylesheet" href="../css/template.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/posts.css">
	
    <style>
    </style>
	
</head>

<body>
    <div class="container-full">
        <h1 class="header-title">P O S T A G E N S</h1>

        <div class="posts-grid">
            <a href="posts/o_principio/o_principio.php" class="post-card-neon">
                <span>O Princípio</span>
            </a>
            
            </div>

        <div class="pagination-cyber">
            <a href="#" style="color: red;">&laquo; ANTERIOR</a>
            <?php for($i=1; $i<=9; $i++): ?>
                <a href="#" class="<?= ($i==1)?'active':'' ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></a>
            <?php endfor; ?>
            <a href="#" style="color: red;">SEGUINTE &raquo;</a>    
        </div>  
    </div>

    <div id="pagination-container"></div>
    <script src="../js/header.js"></script>
    <script src="../pagination.js"></script>
    <script src="../js/footer.js"></script>
    <script src="../js/search.js"></script>
</body>
</html>