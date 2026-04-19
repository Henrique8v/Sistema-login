document.addEventListener('DOMContentLoaded', () => {
    // Seleção dos elementos do Player
    const audio = document.getElementById('audio-track');
    const playBtn = document.getElementById('play-icon');
    const seekSlider = document.getElementById('seek-slider');
    const volumeSlider = document.getElementById('volume-slider');
    const muteBtn = document.getElementById('mute-icon');
    const toggleBtn = document.getElementById('toggle-art');
    const albumArt = document.getElementById('album-art-container');

    // Verificação de segurança: Só executa se os elementos existirem
    if (!audio || !playBtn) {
        console.error("Estação Korvado: Elementos do player não encontrados no DOM.");
        return;
    }

    // --- Lógica de Interface (Toggle) ---
    toggleBtn.addEventListener('click', () => {
        albumArt.classList.toggle('hidden');
        toggleBtn.textContent = albumArt.classList.contains('hidden') ? 'SHOW ART' : 'HIDE ART';
    });

    // --- Lógica de Áudio (Core) ---
    playBtn.addEventListener('click', () => {
        if (audio.paused) {
            audio.play().catch(e => console.error("Erro ao dar play:", e));
            playBtn.textContent = '⏸';
            playBtn.style.boxShadow = "0 0 20px #ff0000";
        } else {
            audio.pause();
            playBtn.textContent = '▶';
            playBtn.style.boxShadow = "none";
        }
    });

    audio.addEventListener('timeupdate', () => {
        if (!isNaN(audio.duration)) {
            const progress = (audio.currentTime / audio.duration) * 100;
            seekSlider.value = progress;
            document.getElementById('current-time').textContent = formatTime(audio.currentTime);
        }
    });

    seekSlider.addEventListener('input', () => {
        const time = (seekSlider.value * audio.duration) / 100;
        audio.currentTime = time;
    });

    volumeSlider.addEventListener('input', () => {
        const val = volumeSlider.value;
        audio.volume = val / 100;
        if (val == 0) muteBtn.textContent = '🔇';
        else if (val < 50) muteBtn.textContent = '🔉';
        else muteBtn.textContent = '🔊';
    });

    muteBtn.addEventListener('click', () => {
        audio.muted = !audio.muted;
        muteBtn.textContent = audio.muted ? '🔇' : '🔊';
        muteBtn.style.color = audio.muted ? '#555' : '#ff0000';
    });

    function formatTime(seconds) {
        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60);
        return `${min}:${sec < 10 ? '0' : ''}${sec}`;
    }

    // Evento crucial: Carrega a duração assim que o áudio está pronto
    audio.addEventListener('loadedmetadata', () => {
        document.getElementById('duration').textContent = formatTime(audio.duration);
    });

    audio.addEventListener('ended', () => {
        playBtn.textContent = '▶';
        seekSlider.value = 0;
        audio.currentTime = 0;
        playBtn.style.boxShadow = "none";
    });
});