<!--areaPrivada.php-->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); ?>

<?php require_once 'site/data/user.php'; ?>
<?php require_once 'site/data/user_menu.php'; ?>
<div style="display: ;">
    <?php require_once 'site/data/limpeza_arquivos.php'; ?>
</div>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações de Perfil</title>
    <link rel="shortcut icon" href="imagens/icon/logo.png">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/style/area_privada.css">
    
    <link rel="stylesheet" href="site/css/template.css">
    <link rel="stylesheet" href="site/css/menu.css">
	<link rel="stylesheet" href="site/css/editar_perfil.css">
</head>
<body style="background-color: #1a1a1a; color: white; font-family: 'Trebuchet MS', sans-serif; margin: 0;">

    <div id="container-perfil" style="margin-top: 30px; display: flex; justify-content: center; padding: 20px;">
        
        <div style="background: rgba(0,0,0,0.8); padding: 40px; border-radius: 15px; border: 1px solid #444; width: 100%; max-width: 450px;">
            
            <h1 style="text-align: center; color: red; margin-top: 0;">Editar Perfil</h1>
            <p style="text-align: center;">Gerencie as informações da sua conta, <strong><?php echo $_SESSION['nome_usuario']; ?></strong>.</p>

            <hr style="border: 0; border-top: 1px solid #333; margin: 20px 0;">
            
			<form id="form-perfil" action="site/data/atualizar_user.php" method="POST" enctype="multipart/form-data"> <!-- AJAX form-perfil --> 
                
                <div style="margin-bottom: 15px;">
                    <label>Nome de Usuário:</label><br>
                    <input type="text" name="novo_nome" value="<?php echo $_SESSION['nome_usuario']; ?>" 
                           style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background: #222; color: white; margin-top: 5px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Foto de Perfil:</label><br>
                    <input type="file" name="foto" accept="image/*" 
                           style="width: 100%; color: #ccc; font-size: 14px; margin-top: 5px;">
                    <small style="color: #888;">Formatos aceitos: JPG, PNG, GIF</small>
                </div>

                <div style="margin-bottom: 15px; margin-top: 10px;">
                    <label>Bio (Descrição do Perfil):</label>
                    <textarea name="bio" placeholder="Escreva algo sobre você..." maxlength="300" 
                              style="width: 100%; height: 100px; background: #111; color: white; border: 1px solid #444; border-radius: 4px; padding: 10px; resize: none; margin-top: 5px;"><?php echo $_SESSION['bio_usuario']; ?></textarea>
                </div>
                
                <div style="margin-bottom: 20px; margin-top: 20px;">
                    <label>Nova Senha:</label><br>
                    <input type="password" name="nova_senha" placeholder="Deixe vazio para não alterar" 
                           style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #555; background: #222; color: white; margin-top: 5px;">
                </div>

                <button type="submit" style="width: 100%; background: red; color: white; padding: 14px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#cc0000'" onmouseout="this.style.background='red'">
                    SALVAR ALTERAÇÕES
                </button>

            </form>

            <div style="margin-top: 20px; text-align: center; display: flex; justify-content: space-between;">
                <a href="perfil_do_usuario.php" style="color: #67c1f5; text-decoration: none; font-size: 14px;">← Voltar ao Perfil</a>
                <a href="sair.php" style="color: #888; text-decoration: none; font-size: 14px;">Logout</a>
            </div>

        </div>
    </div>

    <script src="site/js/header.js"></script>
    <script src="site/js/footer.js"></script>
	<script src="site/js/editar_perfil.js"></script>

</body>
</html>