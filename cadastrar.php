<?php
// cadastrar.php
require_once 'classes/Usuarios.php';
$u = new Usuarios();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | Brotherhood</title>
    <link rel="stylesheet" href="site/css/login.css">
	<link rel="stylesheet" href="site/css/template.css">
</head>
<body>

<div id="corpo-form"> <h1>KORVADO™</h1>
    <p style="text-align:center; color:#555; font-size:10px; letter-spacing:2px; margin-bottom:20px;">NOVA CONEXÃO</p>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome Completo" maxlength="30" required autofocus/>
        <input type="text" name="telefone" placeholder="Telefone" maxlength="30" required autofocus/>
        <input type="text" name="email" placeholder="E-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2}$" maxlength="40" required autofocus/>
        <input type="password" name="senha" placeholder="Senha" maxlength="32" required autofocus/>
        <input type="password" name="conf_senha" placeholder="Confirmar Senha" required autofocus/>
        
        <input type="submit" value="CADASTRAR">
    </form>
    
    <a href="index.php">Já possui uma conta? <strong>Entrar no Santuário</strong></a>

    <?php
    if(isset($_POST['nome'])) {
        $nome = addslashes($_POST['nome']);
        $telefone = addslashes($_POST['telefone']);
        $email = addslashes($_POST['email']);
        $senha = addslashes($_POST['senha']);
        $conf_senha = addslashes($_POST['conf_senha']);

        if(!empty($nome) && !empty($telefone) && !empty($email) && !empty($senha) && !empty($conf_senha)) {
            $u->conectar("projeto_login", "localhost", "root", "");

            if($senha == $conf_senha) {
                if($u->cadastrar($nome, $telefone, $email, $senha)) {
                    echo '<div class="msg-sucesso" style="padding:15px; border:1px solid #00ff41; color:#00ff41; background:rgba(0,255,0,0.05); text-align:center; margin-top:20px; font-size:12px;">CADASTRO CONCLUÍDO! <a href="index.php" style="display:inline; color:#fff; margin:0;">FAÇA LOGIN</a></div>';
                } else {
                    echo '<div class="msg-erro">ESTE E-MAIL JÁ FOI DETECTADO NO SISTEMA!</div>';
                }
            } else {
                echo '<div class="msg-erro">AS SENHAS NÃO COINCIDEM!</div>';
            }
        } else {
            echo '<div class="msg-erro">PREENCHA TODOS OS CAMPOS!</div>';
        }
    }
    ?>
</div>

</body>
</html>