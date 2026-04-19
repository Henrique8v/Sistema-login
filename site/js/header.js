// header.js - Versão RV Project Elite Consolidada

const baseUrl = "/Sistema-login";

function getUsuarioMenu() {
    if (typeof USUARIO_LOGADO === 'undefined' || !USUARIO_LOGADO) return ''; 
    const cacheBuster = new Date().getTime();
    return `
        <div id="user-info-container" style="display: flex; align-items: center; gap: 10px; font-family: sans-serif;">
            <a href="${baseUrl}/perfil_do_usuario.php" style="text-decoration: none; color: var(--text-principal); display: flex; align-items: center;">
                <span style="margin-right: 10px; font-size: 14px; font-weight: bold; margin-left: 10px;">${USUARIO_LOGADO.nome}</span>
                <img src="${baseUrl}/site/${USUARIO_LOGADO.foto}?t=${cacheBuster}" alt="Perfil" 
                     style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid var(--accent-color); object-fit: cover; display: block;"
                     onerror="this.src='${baseUrl}/site/img/default-user.png'"> 
            </a>
            <a href="${baseUrl}/conf.php" style="padding: 8px 12px; text-decoration: none; display: flex; align-items: center;">
                 <img src="${baseUrl}/site/img/setting.png" style="height: 25px; width: 25px; filter: var(--filter-icon);">
            </a>
        </div>
    `;
}

const menuTrigger = `
    <div id="menu-toggle-container" onclick="toggleRVMenu()" style="cursor: pointer; display: flex; flex-direction: column; gap: 4px; z-index: 1001;">
        <div style="width: 25px; height: 2px; background: var(--accent-color); border-radius: 2px;"></div>
        <div style="width: 18px; height: 2px; background: var(--accent-color); border-radius: 2px;"></div>
        <div style="width: 25px; height: 2px; background: var(--accent-color); border-radius: 2px;"></div>
    </div>
`;

function toggleRVMenu() {
    const menu = document.querySelector('.menuhorizontal2');
    const headerDiv = document.querySelector('.header-main-div');
    if (menu) {
        menu.classList.toggle('menu-aberto');
        if (headerDiv) headerDiv.classList.toggle('header-expandido');
    }
}

// CORREÇÃO: Removido background-image fixo que causava o bug visual e corrigido aspas
const headerContent = `
<header style="position: relative; z-index: 1000;">
    <div class="header-main-div" style="
         background-color: var(--bg-container); 
         border-bottom: 1px solid var(--border-color);
         position: relative; 
         min-height: 100%; 
         display: flex; 
         align-items: center; 
         justify-content: space-between; 
         padding: 0 5%;
         transition: all 0.3s ease;">
        
        ${menuTrigger}

        <div class="logo-container" style="display: flex; align-items: center; flex: 1; margin-left: 15px;">
             <a href="${baseUrl}/site/public/repo.php">
                <img src="${baseUrl}/site/img/logo4-removebg-preview1.png" style="display: block; height: 45px; width: auto; filter: var(--filter-logo);">
            </a>
        </div>

        <ul class="menuhorizontal2">
            <li>
                <span style="font-family: trebuchet; font-size: 16px; cursor: pointer; color: var(--text-principal);"><img src="${baseUrl}/site/img/icons/folder-7-128.ico" style="height: 25px; width: 25px; margin-bottom: 5px"></span>
                <ul>
                    <li><a href="${baseUrl}/site/public/livraria.php">Livraria</a></li>
                    <li><a href="${baseUrl}/site/public/repo.php">Repositórios</a></li>
                    <li><a href="${baseUrl}/site/rv_project_inc/posts/galeria/galeria.php">Galeria</a></li>
                </ul>
            </li>
            <li>
                <span style="font-family: trebuchet; font-size: 16px; cursor: pointer; color: var(--text-principal);"><img src="${baseUrl}/site/img/icons/rss-6-128.ico" style="height: 20px; width: 25px; margin-bottom: 5px"></span>
                <ul>
                    <li><a href="#">Mais Relevantes</a></li>
                    <li><a href="#">Tutoriais</a></li>
                </ul>
            </li>
            <li><a href="${baseUrl}/mensagens.php"><span style="font-family: trebuchet; font-size: 16px; color: var(--text-principal);"><img src="${baseUrl}/site/img/icons/message.png" style="height: 30px; width: 29px; margin-bottom: 5px"></span></a></li>
        </ul>

        <div id="user-area">
            ${getUsuarioMenu()}
        </div>  
    </div>
</header>
`;

// Injeta o cabeçalho
document.body.insertAdjacentHTML('afterbegin', headerContent);

// --- LÓGICA DE TEMA SINCRONIZADA ---
function aplicarTemaSalvo() {
    // Prioridade 1: O que o PHP já colocou no body
    // Prioridade 2: localStorage (para rapidez)
    const temaSalvo = localStorage.getItem('tema_korvado');
    
    if (temaSalvo === 'claro') {
        document.body.classList.add('modo-claro');
    } else if (temaSalvo === 'escuro') {
        document.body.classList.remove('modo-claro');
    }
}

// Chamar imediatamente para evitar flicker
aplicarTemaSalvo();