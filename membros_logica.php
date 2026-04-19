<?php
// Sistema-login/membros_logica.php

require_once 'classes/Usuarios.php';
session_start();

$u = new Usuarios;
$u->conectar("projeto_login", "localhost", "root", "");

// Pegamos a conexão através do método público
$pdo = $u->getPdo();

$id_logado = $_SESSION['id_usuario'];

// BUSCA DINÂMICA DE USUÁRIOS
if (isset($_GET['buscar_membro'])) {
    $termo = "%" . addslashes($_GET['buscar_membro']) . "%";
    
    // Busca usuários exceto o logado
    $sql = "SELECT id_usuario, nome, foto FROM usuarios WHERE nome LIKE :t AND id_usuario != :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":t", $termo);
    $stmt->bindValue(":id", $id_logado);
    $stmt->execute();
    $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(count($membros) > 0) {
        foreach ($membros as $m) {
            $foto = !empty($m['foto']) ? "site/img/" . $m['foto'] : "site/img/default.png";
            
            // Verifica se já são amigos - Usando o método getPdo()
            $check = $pdo->prepare("SELECT id_amizade FROM amizades WHERE (usuario_id = :u AND amigo_id = :a) OR (usuario_id = :a AND amigo_id = :u)");
            $check->execute([':u' => $id_logado, ':a' => $m['id_usuario']]);
            $ja_amigo = $check->rowCount() > 0;

            echo "
            <div style='display: flex; align-items: center; justify-content: space-between; background: rgba(0,0,0,0.6); padding: 12px; margin-bottom: 8px; border-radius: 8px; border: 1px solid #333;'>
                <div style='display: flex; align-items: center;'>
                    <img src='{$foto}' style='width: 40px; height: 40px; border-radius: 50%; border: 1px solid #F91313; margin-right: 12px;'>
                    <span style='color: white; font-weight: bold;'>{$m['nome']}</span>
                </div>
                " . ($ja_amigo ? 
                    "<span style='color: #666; font-size: 12px; font-style: italic;'>Já é seu amigo</span>" : 
                    "<button onclick='adicionarAmigo({$m['id_usuario']})' style='background: #F91313; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;'>Adicionar</button>") . "
            </div>";
        }
    } else {
        echo "<p style='color: #666; text-align: center;'>Nenhum membro encontrado com este nome.</p>";
    }
    exit;
}

// AÇÃO: ADICIONAR AMIGO
if (isset($_POST['add_id'])) {
    $amigo_id = $_POST['add_id'];
    $sql = "INSERT INTO amizades (usuario_id, amigo_id, status) VALUES (:u, :a, 'aceito')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u' => $id_logado, ':a' => $amigo_id]);
    exit;
}

// AÇÃO: REMOVER AMIGO
if (isset($_POST['remove_id'])) {
    $amigo_id = $_POST['remove_id'];
    $sql = "DELETE FROM amizades WHERE (usuario_id = :u AND amigo_id = :a) OR (usuario_id = :a AND amigo_id = :u)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u' => $id_logado, ':a' => $amigo_id]);
    exit;
}
?>