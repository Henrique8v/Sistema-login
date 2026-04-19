<!-- user.php -->
<?php
session_start();
// Se o usuário não estiver logado, ele é jogado de volta para o login
if(!isset($_SESSION['id_usuario'])) {
    header("location: ../../index.php"); 
    exit;	
}

?>  