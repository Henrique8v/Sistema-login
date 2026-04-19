Diagnóstico do Erro de Exclusão
No seu arquivo comentar_logica.php, a exclusão depende do método $u->excluirComentario. Se ele não está funcionando, pode ser um erro na classe Usuarios.php ou o caminho do arquivo no fetch.

No seu modal_post.js, a função excluirComentario chama site/data/comentar_logica.php. Precisamos garantir que essa rota esteja correta em relação à localização do JS.

Correção: comentar_logica.php (Backend)
Certifique-se de que o retorno seja consistente para o AJAX.

PHP

// No comentar_logica.php, verifique se a ação de excluir está assim:
if (isset($_POST['excluir_id'])) {
    $id_comentario = (int)$_POST['excluir_id'];
    
    // Verificamos se o comentário pertence ao usuário logado antes de deletar
    $sql = "DELETE FROM comentarios WHERE id_comentario = :idc AND usuario_id = :uid";
    $stmt = $pdo->prepare($sql);
    $sucesso = $stmt->execute([
        ':idc' => $id_comentario,
        ':uid' => $id_usuario_que_comentou
    ]);

    if ($sucesso) {
        echo "excluido";
    } else {
        echo "erro";
    }
    exit; 
}