function atualizarSocial(data) {
    const post = data.post;
    const todosComentarios = data.comentarios;
    const meuId = data.meu_id;
    const idPost = post.id_post;

    // Header
    const header = document.getElementById('post-header');
    if (header) {
        const jaCurti = post.ja_curti > 0;
        const linkAutor = (post.id_autor == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${post.id_autor}`;
        header.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="${linkAutor}"><img src="site/${post.autor_foto}" onerror="this.src='site/img/default-user.png'" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #F91313;"></a>
                    <div>
                        <a href="${linkAutor}" style="text-decoration:none; color: inherit;"><strong style="display:block; color: #fff;">${post.autor}</strong></a>
                        <small style="color:#666">${formatarDataRedeSocial(post.data_post)}</small>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span onclick="alternarLike(${idPost})" style="cursor: pointer; font-size: 20px;">${jaCurti ? '❤️' : '🤍'}</span>
                    <span style="color: #fff; font-size: 13px; font-weight: bold;">${post.total_likes || 0}</span>
                </div>
            </div>`;
    }

    // Comentários
    const commDiv = document.getElementById('post-comments');
    if (commDiv) {
        commDiv.innerHTML = '';
        const raiz = todosComentarios.filter(c => !c.parent_id);
        if(raiz.length === 0) {
            commDiv.innerHTML = '<p style="color:#444; padding:15px;">Nenhum rastro encontrado.</p>';
        } else {
            raiz.forEach(coment => {
                commDiv.innerHTML += gerarHtmlRecursivo(coment, todosComentarios, meuId, idPost, 0);
            });
        }
    }
}

function alternarLike(idPost) {
    let fd = new FormData();
    fd.append('id_post', idPost);
    fetch('site/data/curtir_logica.php', { method: 'POST', body: fd }).then(() => recarregarDadosPost(idPost));
}