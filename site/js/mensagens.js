// site/js/mensagens.js
let citacaoId = null;
const GIPHY_KEY = 'SUA_CHAVE_AQUI'; // Substitua pela sua chave quando criar

const emojis = ["😀","😁","😂","🤣","😃","😄","😅","😆","😉","😊","😋","😎","😍","😘","🥰","🤔","🤨","😐","😑","😶","🙄","😏","🔥","✨","⭐","⚡","🤘","👊","🤝","👍"];

function initEmojis() {
    const container = document.getElementById('emoji-results');
    if(!container) return;
    container.innerHTML = '';
    emojis.forEach(emoji => {
        const btn = document.createElement('button');
        btn.className = 'emoji-item';
        btn.innerText = emoji;
        btn.onclick = () => {
            document.getElementById('msg-input').value += emoji;
            togglePanel('emoji-panel');
        };
        container.appendChild(btn);
    });
}

function togglePanel(id) {
    const p = document.getElementById(id);
    const outro = id === 'emoji-panel' ? 'giphy-panel' : 'emoji-panel';
    const panelOutro = document.getElementById(outro);
    if(panelOutro) panelOutro.style.display = 'none';
    p.style.display = (p.style.display === 'flex') ? 'none' : 'flex';
}

function carregar() {
    if(idPara > 0) {
        fetch(`site/data/ler_msg.php?id_para=${idPara}`)
            .then(r => r.text())
            .then(html => {
                const box = document.getElementById('msg-box');
                const isAtBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 60;
                box.innerHTML = html;
                if (isAtBottom) box.scrollTop = box.scrollHeight;
            });
    }
}

function enviar(gifUrl = null) {
    const input = document.getElementById('msg-input');
    const mensagem = input.value.trim();
    
    if((mensagem !== "" || gifUrl !== null) && idPara > 0) {
        const fd = new FormData();
        fd.append('id_para', idPara);
        fd.append('mensagem', mensagem);
        if(gifUrl) fd.append('gif_url', gifUrl);
        if(citacaoId) fd.append('citacao_id', citacaoId);

        fetch('site/data/enviar_msg.php', { method: 'POST', body: fd })
            .then(() => {
                input.value = ""; 
                cancelarResposta();
                const gp = document.getElementById('giphy-panel');
                if(gp) gp.style.display = 'none';
                carregar(); 
            });
    }
}

function prepararResposta(id, texto) {
    citacaoId = id;
    document.getElementById('reply-text').innerText = "Respondendo a: " + texto;
    document.getElementById('reply-preview').style.display = 'flex';
    document.getElementById('msg-input').focus();
}

function cancelarResposta() {
    citacaoId = null;
    const rp = document.getElementById('reply-preview');
    if(rp) rp.style.display = 'none';
}

function excluirMensagem(id) {
    const fd = new FormData();
    fd.append('excluir_id', id);
    fetch('site/data/enviar_msg.php', { method: 'POST', body: fd }).then(() => carregar());
}

function limparConversa(idAmigo) {
    const fd = new FormData();
    fd.append('limpar_conversa_com', idAmigo);
    fetch('site/data/enviar_msg.php', { method: 'POST', body: fd }).then(() => carregar());
}

// Giphy Search
document.getElementById('giphy-search')?.addEventListener('input', e => {
    const q = e.target.value;
    if(q.length > 2) {
        fetch(`https://api.giphy.com/v1/gifs/search?api_key=${GIPHY_KEY}&q=${q}&limit=12`)
            .then(r => r.json())
            .then(res => {
                let h = '';
                res.data.forEach(g => {
                    h += `<img src="${g.images.fixed_height_small.url}" class="giphy-img" onclick="enviar('${g.images.fixed_height.url}')">`;
                });
                document.getElementById('giphy-results').innerHTML = h;
            });
    }
});

// Inicialização
if(idPara > 0) { 
    setInterval(carregar, 3000); 
    carregar(); 
    window.onload = initEmojis;
}

document.getElementById('msg-input')?.addEventListener('keypress', e => { 
    if(e.key === 'Enter') enviar(); 
});