const GIPHY_API_KEY = 'dc6zaTOxFJmzC'; 

// --- 1. GESTÃO DE MÍDIA (EMOJIS E GIFS) ---

function injetarSeletoresMidia() {
    const footer = document.querySelector('.modal-post-footer');
    if (!footer || document.getElementById('emoji-picker')) return;

    const htmlMidia = `
        <div style="padding: 5px 0; display: flex; gap: 15px; border-bottom: 1px solid #111; margin-bottom: 10px; background: transparent;">
            <span onclick="toggleEmojiPicker()" style="cursor:pointer; filter: grayscale(1); font-size:14px; opacity:0.6; transition: 0.3s;" onmouseover="this.style.opacity=1; this.style.filter='none'" onmouseout="this.style.opacity=0.6; this.style.filter='grayscale(1)'">😊</span>
            <span onclick="toggleGifPicker()" style="cursor:pointer; filter: grayscale(1); font-size:10px; font-weight:bold; opacity:0.6; color:#fff; border:1px solid #fff; padding:0 3px; border-radius:3px; line-height:14px; transition: 0.3s;" onmouseover="this.style.opacity=1; this.style.filter='none'" onmouseout="this.style.opacity=0.6; this.style.filter='grayscale(1)'">GIF</span>
        </div>
        <div id="emoji-picker" style="display:none; grid-template-columns: repeat(8, 1fr); background:#050505; padding:10px; border:1px solid #F91313; border-radius: 4px; margin-bottom: 10px;">
            ${['🔥','💀','❤️','👑','🤝','✅','🚀','🖤','😂','👀','💎','✨'].map(e => `<span onclick="addEmoji('${e}')" style="cursor:pointer; padding:5px; text-align:center;">${e}</span>`).join('')}
        </div>
        <div id="gif-picker" style="display:none; background:#050505; padding:10px; border:1px solid #F91313; border-radius: 4px; margin-bottom: 10px;">
            <div id="gif-list" style="display:flex; overflow-x:auto; gap:8px; padding-bottom:5px; scrollbar-width: thin;"></div>
        </div>
    `;
    footer.insertAdjacentHTML('afterbegin', htmlMidia);
}

function toggleEmojiPicker() {
    const p = document.getElementById('emoji-picker');
    p.style.display = p.style.display === 'none' ? 'grid' : 'none';
    document.getElementById('gif-picker').style.display = 'none';
}

function toggleGifPicker() {
    const p = document.getElementById('gif-picker');
    if (p.style.display === 'none') {
        p.style.display = 'block';
        document.getElementById('emoji-picker').style.display = 'none';
        buscarGifs();
    } else { p.style.display = 'none'; }
}

function addEmoji(e) {
    const i = document.getElementById('texto-comentario');
    i.value += e; i.focus();
}

function buscarGifs() {
    const container = document.getElementById('gif-list');
    container.innerHTML = '<span style="color:#333; font-size:10px;">...</span>';
    fetch(`https://api.giphy.com/v1/gifs/trending?api_key=${GIPHY_API_KEY}&limit=12`)
        .then(res => res.json()).then(json => {
            container.innerHTML = '';
            json.data.forEach(gif => {
                const img = document.createElement('img');
                img.src = gif.images.fixed_height_small.url;
                img.style = "width:60px; height:60px; cursor:pointer; object-fit:cover; border-radius:2px; flex-shrink:0;";
                img.onclick = () => { document.getElementById('texto-comentario').value = gif.images.fixed_height.url; postarComentario(); };
                container.appendChild(img);
            });
        });
}