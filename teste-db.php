<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "config/config.php";

if (isset($pdo)) {
    echo "✅ Conexão com o banco 'projeto_login' estabelecida com sucesso!<br>";
    echo "🚀 O RV Project está pronto para operar.";
} else {
    echo "❌ Erro: A variável de conexão não foi definida.";
}
?>

//http://localhost:8080/Sistema-login/teste-db.php