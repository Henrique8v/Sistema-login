// site/js/timeline.js

/* ESTRUTURA DO FEED
   Este script injeta a interface de postagem e a lista de posts
*/
const timelineHTML = `
<div id="container-timeline" style="max-width: 700px; margin: 20px auto; font-family: 'Inter', sans-serif;">
    
    <div style="background: #1a1a1a; padding: 20px; border-radius: 10px; border: 1px solid #F91313; margin-bottom: 30px;">
        <h3 style="color: white; margin-bottom: 15px; font-size: 16px;">O que está acontecendo, ${typeof USUARIO_LOGADO !== 'undefined' ? USUARIO_LOGADO.nome : 'Membro'}?</h3>
        <form action="timeline_logica.php" method="POST" enctype="multipart/form-data">
            <textarea name="conteudo" placeholder="Escreva sua mensagem para a ordem..." 
                      style="width: 100%; background: #000; border: 1px solid #333; color: white; padding: 10px; border-radius: 5px; min-height: 80px; outline: none; resize: none;"></textarea>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                <input type="file" name="imagem" id="img-post" style="display: none;">
                <label for="img-post" style="color: #666; cursor: pointer; font-size: 13px;">+ Adicionar Imagem</label>
                <input type="submit" name="postar" value="Publicar" 
                       style="background: #F91313; color: white; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: bold;">
            </div>
        </form>
    </div>

    <div id="feed-posts">
        <p style="color: gray; text-align: center;">Carregando pensamentos...</p>
    </div>
</div>
`;

// Injeção onde houver o placeholder id="timeline-placeholder"
const targetTimeline = document.getElementById('timeline-placeholder');
if (targetTimeline) {
    targetTimeline.innerHTML = timelineHTML;
    carregarPosts();
}

/* LÓGICA DE ATUALIZAÇÃO DO FEED */
function carregarPosts() {
    // Note o caminho voltando dois diretórios como solicitado
    fetch('../../timeline_logica.php?buscar_posts=1')
        .then(response => response.text())
        .then(html => {
            const feed = document.getElementById('feed-posts');
            if(feed) feed.innerHTML = html;
        });
}

// Atualiza o feed a cada 30 segundos
setInterval(carregarPosts, 30000);