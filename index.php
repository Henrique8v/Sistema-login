<!-- index.php ( login ) -->
<?php
// index.php - Topo absoluto
session_set_cookie_params([
    'lifetime' => 86400 * 30,
    'path' => '/',
    'domain' => '', 
    'secure' => false, 
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once 'classes/Usuarios.php';
$u = new Usuarios();

// Ligar à base de dados ANTES de qualquer processamento POST
$u->conectar("projeto_login", "localhost", "root", ""); 
?>

<!DOCTYPE html>
<html lang="pt-br" class="scrolling-off-html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline';">
    <title>Login | Brotherhood</title>
    <link rel="stylesheet" href="site/css/login.css">
	<link rel="stylesheet" href="site/css/template.css">
    <link rel="shortcut icon" href="imagens/icon/logo.png">
    <style>
	
	</style>
</head>
<body class="scrolling-off-body">

<div id="corpo-form">
    <h1>Korvado™</h1>

    <form method="POST">
        <input type="text" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2}$" placeholder="Usuário" required autofocus />
        <input type="password" name="senha" placeholder="Senha" required autofocus />
        <input type="submit" value="A C E S S A R" />
        
        <a href="cadastrar.php">Ainda não é inscrito? <strong>Inscreva-se!</strong></a>
    </form>

    <?php
    if(isset($_POST['email'])):
        $email = addslashes($_POST['email']);
        $senha = addslashes($_POST['senha']);

        if(!empty($email) && !empty($senha)):
            // Conexão original mantida
            $u->conectar("projeto_login", "localhost", "root", "");

            if($u->msgERRO == ""):
                if($u->logar($email, $senha)):
                    header("location: perfil_do_usuario.php");
                else: 
                    echo '<div class="msg-erro">Acesso negado: Credenciais inválidas.</div>';
                endif;
            else:
                echo '<div class="msg-erro">Erro: '.$u->msgERRO.'</div>';
            endif;
        endif;
    endif;
    ?>
</div>

</body>
</html>