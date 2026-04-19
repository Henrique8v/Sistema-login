//modal_post.js

function excluirComentario(idComentario, idPost) {
    // Removemos a linha "if(confirm(...))" para apagar sem perguntar nada.
    let fd = new FormData();
    fd.append('excluir_id', idComentario);

    fetch('site/data/comentar_logica.php', {
        method: 'POST',
        body: fd
    })
    .then(res => res.text())
    .then(data => {
        // O PHP retorna "excluido" conforme vimos no seu arquivo comentar_logica.php
        if(data.trim() === "excluido") {
            if(typeof mostrarAviso === 'function') {
                mostrarAviso("Comentário removido");
            }
            abrirPost(idPost); // Recarrega os comentários no modal
        } else {
            console.error("Erro retornado pelo PHP:", data);
            alert("Erro ao excluir comentário");
        }
    })
    .catch(err => console.error("Erro na requisição:", err));
}