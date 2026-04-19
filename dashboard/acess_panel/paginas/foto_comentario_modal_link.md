o ponto onde você deve mexer é dentro do comentarios.forEach (no Bloco 3 do código).

Para que o clique funcione, você precisa criar uma variável de link para o autor do comentário (parecida com a linkAutor que já existe no cabeçalho) e envolver os elementos desejados.

Aqui está o trecho específico que você deve alterar e como fazer:

Local da alteração:
Procure por esta parte no seu código:

JavaScript

comentarios.forEach(c => {
    let btnExcluir = (c.id_autor_comentario == meuId) ? ...;
    commDiv.innerHTML += `
        <div class="comment-item" ...>
            <img src="site/${c.foto_comentario}" ...>
            <div>
                ...
                <strong style="color:#F91313;">${c.autor_comentario}</strong>
                ...
            </div>
        </div>`;
});
O que você deve fazer (Passo a Passo):
Crie a lógica do link: Logo abaixo do forEach(c => {, defina para onde o link vai levar:

JavaScript

let linkComentador = (c.id_autor_comentario == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${c.id_autor_comentario}`;
Envolva a Imagem: Coloque a tag <a> em volta da <img>.

Envolva o Nome: Coloque a tag <a> em volta do <strong>.

Dica de Ouro: Adicione style="text-decoration: none;" nos links para que o navegador não coloque aquele sublinhado azul que estraga a estética do RV Project.

Exemplo de como ficaria a sua string de HTML:
JavaScript

commDiv.innerHTML += `
    <div class="comment-item" style="display:flex; gap:12px; padding:12px; border-bottom:1px solid #111;">
        <a href="${linkComentador}">
            <img src="site/${c.foto_comentario}" style="width:30px; height:30px; border-radius:50%;" onerror="this.src='site/img/default-user.png'">
        </a>
        <div>
            <div style="font-size:13px;">
                <a href="${linkComentador}" style="text-decoration:none;">
                    <strong style="color:#F91313;">${c.autor_comentario}</strong>
                </a> 
                <span style="color:#eee;">${c.comentario}</span>
            </div>
            <div style="font-size:10px; color:#555;">${formatarDataRedeSocial(c.data_comentario)} ${btnExcluir}</div>
        </div>
    </div>`;