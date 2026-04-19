<?php
// site/data/atualizar_user.php
require_once 'user.php'; 
require_once '../../classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", ""); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário da areaPrivada.php
    $nome  = addslashes($_POST['novo_nome']);
    $bio   = addslashes($_POST['bio']);
    $senha = addslashes($_POST['nova_senha']);
    $id_usuario = $_SESSION['id_usuario'];

    if (!empty($nome)) {
        // 1. Atualiza os dados textuais no banco
        $u->atualizarPerfil($nome, $bio, $senha, $id_usuario);

        // 2. Verifica se o usuário enviou uma foto nova
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $arquivo = $_FILES['foto'];
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            $formatos_aceitos = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extensao, $formatos_aceitos)) {
                
                /* LOGICA DE NOME PERSONALIZADO:
                   Transformamos "Lorde Raven" em "Lorde_Raven" para evitar erros no Windows.
                */
                $usuario_sanitizado = preg_replace("/[^a-zA-Z0-9]/", "_", $nome);
                
                // Criamos o nome fixo: nome_id.extensao
                $novo_nome_foto = $usuario_sanitizado . "_id_" . $id_usuario . "." . $extensao;
                
                $diretorio_destino = "../img/perfis/";

                if (!is_dir($diretorio_destino)) {
                    mkdir($diretorio_destino, 0777, true);
                }

                // Ao mover, se já existir uma foto com esse nome (do mesmo user), o Windows sobrescreve.
                if (move_uploaded_file($arquivo['tmp_name'], $diretorio_destino . $novo_nome_foto)) {
                    // 3. Salva o caminho final no banco de dados
                    $u->atualizarFoto("img/perfis/" . $novo_nome_foto, $id_usuario);
                }
            }
        }
        
        // Redireciona com sucesso para o perfil
        header("Location: ../../perfil_do_usuario.php?sucesso=1");
        exit;
    } else {
        header("Location: ../../perfil_do_usuario.php?erro=nome_vazio");
        exit;
    }
} else {
    header("Location: ../../index.php");
    exit;
}