<!-- sair.php -->
<?php
session_start(); // Inicia a sessão para poder manipulá-la
unset($_SESSION['id_usuario']); // Remove a variável específica do usuário
session_destroy(); // Destrói todas as informações da sessão
header("location: index.php"); // Manda o usuário de volta para a tela de login
exit;
?>