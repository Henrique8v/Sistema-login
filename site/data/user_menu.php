<!-- user_menu -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário estiver logado, prepara os dados para o JS
if (isset($_SESSION['id_usuario'])) {
    echo "<script>
        const USUARIO_LOGADO = {
            id: '" . $_SESSION['id_usuario'] . "',
            nome: '" . $_SESSION['nome_usuario'] . "',
            foto: '" . $_SESSION['foto_usuario'] . "'
        };
    </script>";
} else {
    echo "<script>const USUARIO_LOGADO = null;</script>";
}
?>