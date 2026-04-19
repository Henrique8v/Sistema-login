<?php 
// uncknown.php
//______________teste de erros_____________________
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//_______________________________________________________

require_once $_SERVER['DOCUMENT_ROOT'] . '/Sistema-login/config/config.php'; 

//caminho absoluto começando pela raiz. Configurações de conexão com o servidor. Ao usar as variáveis do config.php, se amanhã você mudar a senha do banco no XAMPP, você só altera em um lugar, e o uncknown.php continuará funcionando. Manter a lógica de conexão centralizada evita expor dados sensíveis em múltiplos arquivos.

require_once 'site/data/user.php';  //segurança/escudo
require_once 'classes/Usuarios.php'; //autenticador, classes

//______________conector de servidor_____________________
$u = new Usuarios();
$u->conectar($dbname, $host, $user, $pass);
$pdo = $u->getPdo();
//_______________________________________________________

$meu_id = $_SESSION['id_usuario']; //sessão do usuário

?>