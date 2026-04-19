<?php
// site/conf/php/atualizar_privacidade.php
session_start();
require_once '../../../classes/Usuarios.php';

if (!isset($_SESSION['id_usuario'])) {
    die('Não autorizado');
}

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();

$meu_id = $_SESSION['id_usuario'];
$campo  = $_POST['campo'] ?? '';
$valor  = $_POST['valor'] ?? '';

// Lista de campos permitidos para evitar SQL Injection via nomes de colunas
$campos_permitidos = ['tema', 'privacidade_feed', 'qualidade_midia'];

if (in_array($campo, $campos_permitidos)) {
    // Usamos INSERT ... ON DUPLICATE KEY UPDATE para garantir que, 
    // se o usuário não tiver uma linha na tabela de config, ela seja criada na hora.
    $sql = "INSERT INTO usuario_configuracoes (id_usuario, $campo) 
            VALUES (:id, :valor) 
            ON DUPLICATE KEY UPDATE $campo = :valor2";
    
    $stmt = $pdo->prepare($sql);
    $exec = $stmt->execute([
        ':id'     => $meu_id,
        ':valor'  => $valor,
        ':valor2' => $valor
    ]);

    if ($exec) {
        echo "sucesso";
    } else {
        echo "erro ao salvar";
    }
} else {
    echo "campo inválido";
}