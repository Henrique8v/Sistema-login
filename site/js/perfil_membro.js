   function gerenciarAmizade(id, acao) {
        let fd = new FormData();
        fd.append(acao === 'remover' ? 'remover_id' : 'adicionar_id', id);
        fetch('site/data/amizade_logica.php', { method: 'POST', body: fd })
        .then(() => {
            location.reload();
        });
    }
    
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const postParaAbrir = urlParams.get('post');
        if (postParaAbrir && typeof abrirPost === 'function') {
            setTimeout(() => abrirPost(postParaAbrir), 300);
        }
    }