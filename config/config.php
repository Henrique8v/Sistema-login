<?php
// Configurações do Banco de Dados - RV Project
$host    = 'localhost';
$port    = '3306'; // Porta padrão do MySQL no XAMPP
$dbname  = 'projeto_login'; // SUBSTITUA pelo nome do banco que você importou no phpMyAdmin
$user    = 'root';
$pass    = ''; // No XAMPP, a senha padrão é vazia

try {
    // Estabelece a conexão usando PDO
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Desabilita a emulação de prepared statements por segurança
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem amigável (em produção, o ideal é salvar em log)
    die("Erro ao conectar com o banco de dados da Brotherhood: " . $e->getMessage());
}

require_once __DIR__ . "../../config/config.php";

?>