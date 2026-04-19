    function toggleNotif() {
        const box = document.getElementById('box-notificacoes');
        const badge = document.querySelector('.notif-badge');
        box.style.display = (box.style.display === 'block') ? 'none' : 'block';
        if(box.style.display === 'block' && badge) {
            fetch('site/data/marcar_lidas.php').then(() => badge.style.display = 'none');
        }
    }

    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const postParaAbrir = urlParams.get('post');
        if (postParaAbrir) {
            setTimeout(() => { if (typeof abrirPost === 'function') abrirPost(postParaAbrir); }, 500);
        }
    }
	
// Abrir post automaticamente se vier via link de notificação
window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('post');
    if (postId) {
        if (typeof abrirPost === 'function') {
            abrirPost(postId);
        }
    }
});