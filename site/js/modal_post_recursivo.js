function gerarHtmlRecursivo(coment, listaTotal, meuId, idPost, nivel) {
    const filhotes = listaTotal.filter(c => c.parent_id == coment.id_comentario);
    const recuo = nivel > 0 ? `margin-left: ${nivel === 1 ? '35' : '20'}px; border-left: 1px solid #F91313; padding-left: 10px;` : 'border-bottom: 1px solid #111;';
    
    let conteudo = coment.comentario;
    if (conteudo.includes('giphy.com')) {
        conteudo = `<img src="${conteudo}" style="max-width:140px; border-radius:4px; display:block; margin-top:5px; border: 1px solid #222;">`;
    }

    let btnExcluir = (coment.id_autor_comentario == meuId) ? `<a href="javascript:void(0);" onclick="excluirComentario(${coment.id_comentario}, ${idPost})" style="color:#F91313; text-decoration:none; font-size:9px; margin-left:8px; font-weight:bold;">[EXCLUIR]</a>` : '';
    let btnResponder = `<a href="javascript:void(0);" onclick="prepararResposta(${coment.id_comentario}, '${coment.autor_comentario}')" style="color:#F91313; text-decoration:none; font-size:9px; margin-left:8px; font-weight:bold;">[RESPONDER]</a>`;

    let html = `
        <div class="comment-item" style="display:flex; gap:10px; padding:10px 15px; ${recuo}">
            <img src="site/${coment.foto_comentario}" style="width:28px; height:28px; border-radius:50%; object-fit:cover;" onerror="this.src='site/img/default-user.png'">
            <div style="flex:1">
                <div style="font-size:12px; color:#fff;">
                    <strong style="color:#F91313;">${coment.autor_comentario}</strong> <span>${conteudo}</span>
                </div>
                <div style="font-size:9px; color: #555; margin-top:4px;">
                    ${formatarDataRedeSocial(coment.data_comentario)} ${btnResponder} ${btnExcluir}
                </div>
            </div>
        </div>`;

    if (filhotes.length > 0) {
        html += `
            <div id="btn-respostas-${coment.id_comentario}" onclick="toggleRespostas(${coment.id_comentario})" style="margin-left: ${nivel === 0 ? '45' : '30'}px; color: #F91313; font-size: 10px; font-weight: bold; cursor: pointer; padding: 5px 0;">── Ver respostas</div>
            <div id="respostas-container-${coment.id_comentario}" style="display: none;">
                ${filhotes.map(f => gerarHtmlRecursivo(f, listaTotal, meuId, idPost, nivel + 1)).join('')}
            </div>`;
    }
    return html;
}

