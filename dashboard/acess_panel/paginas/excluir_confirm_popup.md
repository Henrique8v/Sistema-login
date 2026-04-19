Nota: tire o if(confirm com seu '{' e '}', pois a cção da função fica dentro desse 'se'/'if'.

Para remover essa popup de confirmação (que é o confirm() nativo do navegador), você precisa substituir a chamada dessa função no seu JavaScript por uma execução direta ou por um modal customizado.

A função responsável por essa popup é a excluirComentario.

1. Alteração no site/js/modal_post.js
Abra o seu arquivo e localize a função excluirComentario. Remova o bloco if(confirm(...)) para que a exclusão aconteça instantaneamente ao clicar.

De:

JavaScript

function excluirComentario(idComentario, idPost) {
    if(confirm("Deseja realmente apagar este comentário?")) { // <--- REMOVA ISSO
        let fd = new FormData();
        // ... restante do código
    }
}
Para (Exclusão Direta):

JavaScript

function excluirComentario(idComentario, idPost) {
    // Agora ele executa direto sem perguntar nada
    let fd = new FormData();
    fd.append('excluir_id', idComentario);

    fetch('site/data/comentar_logica.php', {
        method: 'POST',
        body: fd
    })
    .then(res => res.text())
    .then(data => {
        if(data.trim() === "excluido") {
            // Se você tiver uma função de aviso/toast, use-a aqui
            if(typeof mostrarAviso === 'function') mostrarAviso("Comentário removido");
            abrirPost(idPost); 
        } else {
            alert("Erro ao excluir comentário");
        }
    });
}