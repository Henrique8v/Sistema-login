document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('.card-post video');
    let globalMuted = true; // Estado mudo por padrão

    const silenciarTodos = () => {
        videos.forEach(v => {
            v.muted = true;
        });
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const video = entry.target;
            if (entry.isIntersecting) {
                // Ao entrar no foco, garante que ele respeita o mute global
                // mas primeiro silenciamos todos para evitar sobreposição
                video.loop = true;
                video.muted = globalMuted; 
                
                video.play().catch(() => {
                    video.muted = true;
                    video.play();
                });
            } else {
                video.pause();
                video.muted = true; // Garante silêncio ao sair da tela
            }
        });
    }, { threshold: 0.7 });

    videos.forEach(video => {
        observer.observe(video);

        // --- Botão de Controle de Áudio ---
        if (!video.parentElement.querySelector('.mute-toggle')) {
            const muteBtn = document.createElement('div');
            muteBtn.className = 'mute-toggle';
            muteBtn.innerHTML = '🔇';
            video.parentElement.appendChild(muteBtn);

            muteBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                
                // Inverte o estado global
                globalMuted = !globalMuted;

                // Se o usuário ativou o som, silenciamos todos e ativamos SÓ este
                silenciarTodos();
                video.muted = globalMuted;

                // Atualiza os ícones de todos os botões para refletir a escolha global
                document.querySelectorAll('.mute-toggle').forEach(btn => {
                    btn.innerHTML = globalMuted ? '🔇' : '🔊';
                    btn.style.borderColor = globalMuted ? '#333' : 'var(--primary-green)';
                });
            });
        }

        // --- Hover Inteligente ---
        const card = video.closest('.card-post');
        card.addEventListener('mouseenter', () => {
            // Se o usuário quer som (globalMuted false), 
            // silenciamos os outros antes de tocar este com som
            if (!globalMuted) {
                silenciarTodos();
            }
            video.muted = globalMuted;
            video.play().catch(() => {});
        });
    });
});