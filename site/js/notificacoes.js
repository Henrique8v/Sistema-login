// notificacoes.js - RV Project
function toggleNotif() {
    const box = document.getElementById('box-notificacoes');
    const badge = document.querySelector('.notif-badge');

    if (box) {
        if (box.style.display === 'block') {
            box.style.display = 'none';
        } else {
            box.style.display = 'block';
            
            // 1. Esconde o badge visualmente ao abrir
            if (badge) badge.style.display = 'none';

            // 2. Marca como lidas no banco
            fetch('site/data/limpar_notificacoes.php')
                .then(res => res.text())
                .then(data => console.log("Notificações marcadas como lidas"));
        }
    }
}

// Lógica de Arraste (Swipe to Delete)
let startX = 0;
let currentItem = null;
let diff = 0;

function startDrag(e, item) {
    e.stopPropagation();
    startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
    currentItem = item;
    currentItem.style.transition = 'none';
    
    document.addEventListener('mousemove', dragMove);
    document.addEventListener('mouseup', dragEnd);
    document.addEventListener('touchmove', dragMove, { passive: false });
    document.addEventListener('touchend', dragEnd);
}

function dragMove(e) {
    if (!currentItem) return;
    let x = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
    diff = x - startX;

    if (diff > 0) {
        if(e.cancelable) e.preventDefault();
        currentItem.style.transform = `translateX(${diff}px)`;
    }
}

function dragEnd() {
    if (!currentItem) return;

    if (diff > 100) {
        lancarFora(currentItem);
    } else {
        currentItem.style.transition = 'transform 0.3s ease';
        currentItem.style.transform = 'translateX(0)';
    }

    document.removeEventListener('mousemove', dragMove);
    document.removeEventListener('mouseup', dragEnd);
    document.removeEventListener('touchmove', dragMove);
    document.removeEventListener('touchend', dragEnd);
    currentItem = null;
    diff = 0;
}

function lancarFora(item) {
    const idNotif = item.getAttribute('data-id');
    const wrapper = item.closest('.notif-wrapper'); 
    
    // 1. Animação de saída lateral
    item.style.transition = 'transform 0.4s ease-in, opacity 0.3s';
    item.style.transform = 'translateX(100%)';
    item.style.opacity = '0';

    // 2. ENVIO PARA O BANCO DE DADOS
    let fd = new FormData();
    fd.append('id_notif', idNotif);
    
    fetch('site/data/arrastar_notificacoes.php', {
        method: 'POST',
        body: fd
    })
    .then(response => response.text())
    .then(txt => console.log("Status do Banco: " + txt));
	
    // 3. Atualiza o contador (Badge) em tempo real
	let badge = document.querySelector('.notif-badge');
    if (badge) {
        let valorAtual = parseInt(badge.innerText);
        if (valorAtual > 1) {
            badge.innerText = valorAtual - 1;
        } else {
            badge.remove();
        }
    }

    // 4. Remove o espaço da lista suavemente
    setTimeout(() => {
        wrapper.style.transition = 'all 0.3s ease';
        wrapper.style.maxHeight = '0';
        wrapper.style.opacity = '0';
        wrapper.style.margin = '0';
        wrapper.style.padding = '0';
        wrapper.style.border = 'none';
        
        setTimeout(() => wrapper.remove(), 300);
    }, 200);
}

// Fechamento inteligente ao clicar fora
window.addEventListener('click', function(e) {
    const box = document.getElementById('box-notificacoes');
    const container = document.querySelector('.notif-container');
    const btnNotif = e.target.closest('#btn-notificacoes');

    if (box && box.style.display === 'block') {
        if (container && !container.contains(e.target) && !btnNotif) {
            box.style.display = 'none';
        }
    }
});