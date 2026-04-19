// --- LOGICA DO BOTÃO EDITAR ---
// Vincula os botões de edição à função abrirEdicao que está no seu JS externo
document.querySelectorAll('.btn-abrir-editor').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const texto = this.getAttribute('data-texto');
        const img = this.getAttribute('data-img');
        const grad = this.getAttribute('data-grad');
        
        // abrirEdicao deve estar definida no seu editar_feed.js
        if (typeof abrirEdicao === 'function') {
            abrirEdicao(id, texto, img, grad);
        }
    });
});

function fecharEdicao() {
    document.getElementById('modalEdit').style.display = 'none';
}

function abrirEdicao(id, texto, img, grad) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-texto').value = texto;
    
    const preview = document.getElementById('edit-preview');
    preview.innerHTML = ''; 
    preview.className = 'edit-left';

    if(img && img !== "") {
        preview.innerHTML = `<img src="site/img/posts/${img}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
    } else if(grad && grad !== "") {
        preview.classList.add(grad);
        preview.innerHTML = `<div style="padding:20px; text-align:center; color:white; font-weight:bold;">${texto}</div>`;
    } else {
        preview.innerHTML = `<div style="padding:20px; color:white;">${texto}</div>`;
    }

    document.getElementById('modalEdit').style.display = 'block';
}

function fecharEdicao() {
    document.getElementById('modalEdit').style.display = 'none';
}

function inserirTag(tag) {
    const isModalOpen = document.getElementById('modalEdit').style.display === 'block';
    const areaId = isModalOpen ? 'edit-texto' : 'main-textarea';
    const area = document.getElementById(areaId);
    if(!area) return;

    const start = area.selectionStart;
    const end = area.selectionEnd;
    const selecao = area.value.substring(start, end);
    area.value = area.value.substring(0, start) + `<${tag}>${selecao}</${tag}>` + area.value.substring(end);
    area.focus();
}