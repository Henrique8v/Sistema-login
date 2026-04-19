// --- LÓGICA DO EDITOR PRINCIPAL ---

document.getElementById('file-upload').addEventListener('change', function() {
    const display = document.getElementById('file-name-display');
    if (this.files && this.files.length > 0) {
        display.textContent = "📄 " + this.files[0].name;
        display.style.color = "#F91313";
    } else {
        display.textContent = "";
    }
});

function alinharTexto(alinhamento) {
    document.getElementById('main-textarea').style.textAlign = alinhamento;
}

function setGradient(classe) {
    const textarea = document.getElementById('main-textarea');
    const input = document.getElementById('input-gradiente');
    textarea.className = 'main-textarea ' + (classe || '');
    input.value = classe;
}

// --- LÓGICA DO MODAL DE EDIÇÃO ---

function abrirEdicao(id, texto, alinhamento, gradiente, midiaPath) {
    const modal = document.getElementById('modalEdit');
    const area = document.getElementById('edit-texto');
    const preview = document.getElementById('edit-preview');
    
    document.getElementById('edit-id').value = id;
    area.value = texto;
    
    // Alinhamento e Gradiente na área de texto
    area.style.textAlign = alinhamento || 'left';
    document.getElementById('alinhamento_edit').value = alinhamento || 'left';
    area.className = 'main-textarea ' + (gradiente || '');
    document.getElementById('gradiente_edit').value = gradiente || '';

    // LÓGICA DE PREVIEW DE MÍDIA
    if (midiaPath) {
        const ext = midiaPath.split('.').pop().toLowerCase();
        const videoExts = ['mp4', 'webm', 'ogg'];

        if (videoExts.includes(ext)) {
            preview.innerHTML = `<video src="site/img/posts/${midiaPath}" controls style="max-width:100%; max-height:100%;"></video>`;
        } else {
            preview.innerHTML = `<img src="site/img/posts/${midiaPath}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
        }
    } else {
        // Se não tem mídia, o preview mostra o gradiente (opcional)
        preview.innerHTML = `<div class="${gradiente}" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#555; font-size:12px;">Postagem apenas de texto</div>`;
    }
    
    modal.style.display = 'flex';
}

function fecharEdicao() {
    document.getElementById('modalEdit').style.display = 'none';
}

function alinharEdicao(ali) {
    const area = document.getElementById('edit-texto');
    area.style.textAlign = ali;
    document.getElementById('alinhamento_edit').value = ali;
}

function setGradientEdicao(grad) {
    const area = document.getElementById('edit-texto');
    area.className = 'main-textarea ' + grad;
    document.getElementById('gradiente_edit').value = grad;
}

function inserirTagEdicao(tag) {
    const area = document.getElementById('edit-texto');
    const inicio = area.selectionStart;
    const fim = area.selectionEnd;
    const textoSel = area.value.substring(inicio, fim);
    
    // Se não houver seleção, apenas coloca a tag vazia e foca no meio
    const novoTexto = area.value.substring(0, inicio) + 
                      `<${tag}>` + textoSel + `</${tag}>` + 
                      area.value.substring(fim);
    
    area.value = novoTexto;
    area.focus();
    
    // Reposiciona o cursor
    const posicaoCursor = inicio + tag.length + 2 + textoSel.length;
    area.setSelectionRange(posicaoCursor, posicaoCursor);
}