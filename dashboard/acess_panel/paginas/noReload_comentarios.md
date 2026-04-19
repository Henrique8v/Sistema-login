Correção: modal_post.js (Frontend Otimizado)
// site/js/modal_post.js

function formatarDataRedeSocial(dataString) {
    const agora = new Date();
    const dataPost = new Date(dataString);
    const segundos = Math.floor((agora - dataPost) / 1000);
    if (segundos < 60) return segundos + "s";
    const minutos = Math.floor(segundos / 60);
    if (minutos < 60) return minutos + "min";
    const horas = Math.floor(minutos / 60);
    if (horas < 24) return horas + "h";
    const dias = Math.floor(horas / 24);
    if (dias < 7) return dias + "d";
    return dataPost.toLocaleDateString('pt-BR');
}

// site/js/modal_post.js

// ... (mantenha sua função formatarDataRedeSocial igual)

function abrirPost(idPost) {
    fetch(`site/data/visualizar_post.php?id_post=${idPost}`)
        .then(res => res.json())
        .then(data => {
            if (data.erro) return console.error(data.erro);

            const post = data.post;
            const comentarios = data.comentarios;
            const meuId = data.meu_id;
            
            // --- 1. RENDERIZAR MÍDIA (Só roda ao abrir o modal pela 1ª vez) ---
            const mediaDiv = document.getElementById('media-content');
            mediaDiv.className = "modal-post-media"; 
            mediaDiv.style = ""; 

            if (post.imagem_post) {
                const ext = post.imagem_post.split('.').pop().toLowerCase();
                if(['mp4', 'webm', 'ogg'].includes(ext)) {
                    mediaDiv.innerHTML = `<video controls autoplay style="width:100%; max-height:100%"><source src="site/img/posts/${post.imagem_post}"></video>`;
                } else {
                    mediaDiv.innerHTML = `<img src="site/img/posts/${post.imagem_post}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
                }
            } else {
                if (post.gradiente_id) mediaDiv.classList.add(post.gradiente_id);
                mediaDiv.style.textAlign = post.alinhamento || 'center';
                mediaDiv.style.display = "flex";
                mediaDiv.style.alignItems = "center";
                mediaDiv.style.justifyContent = "center";
                mediaDiv.style.padding = "40px";
                mediaDiv.style.fontSize = "22px";
                mediaDiv.style.color = "inherit";
                mediaDiv.innerHTML = `<div style="width:100%">${post.conteudo}</div>`;
            }

            // --- 2 e 3. ATUALIZA SOCIAL (Likes e Comentários) ---
            atualizarSocial(data);

            document.getElementById('current-post-id').value = idPost;
            document.getElementById('modal-post').style.display = 'flex';
        });
}

// NOVA FUNÇÃO: Atualiza apenas as partes de texto/interação
function atualizarSocial(data) {
    const post = data.post;
    const comentarios = data.comentarios;
    const meuId = data.meu_id;
    const idPost = post.id_post;

    // --- ATUALIZAR CABEÇALHO (LIKES) ---
    const jaCurti = post.ja_curti > 0;
    const linkAutor = (post.id_autor == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${post.id_autor}`;
    
    document.getElementById('post-header').innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <a href="${linkAutor}">
                    <img src="site/${post.autor_foto}" onerror="this.src='site/img/default-user.png'" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #F91313;">
                </a>
                <div>
                    <a href="${linkAutor}" style="text-decoration:none; color: inherit;"><strong style="display:block">${post.autor}</strong></a>
                    <small style="color:#666">${formatarDataRedeSocial(post.data_post)}</small>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span onclick="alternarLike(${idPost})" style="cursor: pointer; font-size: 20px;">
                    ${jaCurti ? '❤️' : '🤍'}
                </span>
                <span style="color: inherit; font-size: 13px;">${post.total_likes || 0}</span>
            </div>
        </div>`;

    // --- ATUALIZAR LISTA DE COMENTÁRIOS ---
    const commDiv = document.getElementById('post-comments');
    commDiv.innerHTML = comentarios.length === 0 ? '<p style="color:#444; padding:15px;">Nenhum comentário ainda.</p>' : '';
    
    comentarios.forEach(c => {
        let linkComentador = (c.id_autor_comentario == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${c.id_autor_comentario}`;
        let btnExcluir = (c.id_autor_comentario == meuId) ? `<span onclick="excluirComentario(${c.id_comentario}, ${idPost})" style="color:#F91313; cursor:pointer; font-size:9px; margin-left:8px;">EXCLUIR</span>` : '';
        commDiv.innerHTML += `
            <div class="comment-item" id="comentario-${c.id_comentario}" style="display:flex; gap:12px; padding:12px; border-bottom:1px solid #111;">
                <a href="${linkComentador}">
                    <img src="site/${c.foto_comentario}" style="width:30px; height:30px; border-radius:50%;" onerror="this.src='site/img/default-user.png'">
                </a>
                <div>
                    <div style="font-size:13px;">
                        <a href="${linkComentador}" style="text-decoration:none;">
                            <strong style="color:#F91313;">${c.autor_comentario}</strong>
                        </a> 
                        <span style="color: inherit;">${c.comentario}</span>
                    </div>
                    <div style="font-size:10px; color: inherit;">${formatarDataRedeSocial(c.data_comentario)} ${btnExcluir}</div>
                </div>
            </div>`;
    });
}

// --- FUNÇÕES DE AÇÃO (Sem reload de mídia) ---

function alternarLike(idPost) {
    let fd = new FormData();
    fd.append('id_post', idPost);
    fetch('site/data/curtir_logica.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(() => {
        // Em vez de abrirPost, buscamos apenas os dados e atualizamos o social
        fetch(`site/data/visualizar_post.php?id_post=${idPost}`)
        .then(res => res.json())
        .then(data => atualizarSocial(data));
    });
}

function postarComentario(e) {
    e.preventDefault();
    const idPost = document.getElementById('current-post-id').value;
    const input = document.getElementById('texto-comentario');
    let fd = new FormData();
    fd.append('id_post', idPost);
    fd.append('comentario', input.value);
    fetch('site/data/comentar_logica.php', { method: 'POST', body: fd })
    .then(() => { 
        input.value = ''; 
        fetch(`site/data/visualizar_post.php?id_post=${idPost}`)
        .then(res => res.json())
        .then(data => atualizarSocial(data));
    });
}

function excluirComentario(idC, idP) {
    // Excluir direto sem popup conforme pedido
    let fd = new FormData();
    fd.append('excluir_id', idC);
    fetch('site/data/comentar_logica.php', { method: 'POST', body: fd })
    .then(() => {
        fetch(`site/data/visualizar_post.php?id_post=${idP}`)
        .then(res => res.json())
        .then(data => atualizarSocial(data));
    });
}

function fecharModalPost(e) {
    document.getElementById('modal-post').style.display = 'none';
    document.getElementById('media-content').innerHTML = '';
}