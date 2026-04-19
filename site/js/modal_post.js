// site/js/modal_post.js - VERSÃO INTEGRAL (Mídias + Comentários Infinitos + Delay)

let comentarioParentId = null;

// --- 2. LÓGICA DE EXIBIÇÃO E COMENTÁRIOS ---

function abrirPost(idPost) {
    fetch(`site/data/visualizar_post.php?id_post=${idPost}`)
        .then(res => res.json())
        .then(data => {
            if (data.erro) return;
            const modal = document.getElementById('modal-post');
            const mediaDiv = document.getElementById('media-content');
            
            modal.style.display = 'flex';
            injetarSeletoresMidia();

            if (mediaDiv) {
                const post = data.post;
                mediaDiv.className = "modal-post-media";
                if (post.imagem_post) {
                    const ext = post.imagem_post.split('.').pop().toLowerCase();
                    if(['mp4', 'webm', 'ogg'].includes(ext)) {
                        mediaDiv.innerHTML = `<video controls autoplay loop style="width:100%; max-height:100%"><source src="site/img/posts/${post.imagem_post}"></video>`;
                    } else {
                        mediaDiv.innerHTML = `<img src="site/img/posts/${post.imagem_post}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
                    }
                } else {
                    if (post.gradiente_id) mediaDiv.className = "modal-post-media " + post.gradiente_id;
                    mediaDiv.innerHTML = `<div style="padding:40px; text-align:${post.alinhamento || 'center'}; font-size:22px; width:100%;">${post.conteudo}</div>`;
                }
            }

            atualizarSocial(data);
            document.getElementById('current-post-id').value = idPost;
        });
}

function postarComentario(e) {
    if(e) e.preventDefault();
    const idPost = document.getElementById('current-post-id').value;
    const input = document.getElementById('texto-comentario');
    if(!input.value.trim()) return;

    let fd = new FormData();
    fd.append('id_post', idPost);
    fd.append('comentario', input.value);
    if(comentarioParentId) fd.append('parent_id', comentarioParentId);

    fetch('site/data/comentar_logica.php', { method: 'POST', body: fd }).then(() => {
        input.value = '';
        input.placeholder = "Escreva um rastro...";
        comentarioParentId = null;
        recarregarDadosPost(idPost);
    });
}

function fecharModalPost(e) {
    if (e.target.id === 'modal-post') {
        document.getElementById('modal-post').style.display = 'none';
        document.getElementById('media-content').innerHTML = '';
        comentarioParentId = null;
    }
}