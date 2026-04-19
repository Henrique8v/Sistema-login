// --- 3. AÇÕES (DELAY E SINCRONIZAÇÃO) ---

function recarregarDadosPost(idPost) {
    setTimeout(() => {
        fetch(`site/data/visualizar_post.php?id_post=${idPost}&t=${new Date().getTime()}`)
            .then(res => res.json())
            .then(data => { if (!data.erro) atualizarSocial(data); });
    }, 300);
}