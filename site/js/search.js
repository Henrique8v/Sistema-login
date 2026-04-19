// js/search.js - Versão Limpa e Sincronizada com CSS

const searchHTML = `
<div class="search-box">
    <input type="text" id="search-input" placeholder="Buscar..." autocomplete="off">
    <div id="results-list" style="display: none;"></div>
</div>
`;

// Função para injetar a busca no header
function injetarBusca() {
    const menu = document.querySelector('header');
    
    if (menu) {
        menu.insertAdjacentHTML('beforeend', searchHTML);
        ativarEventosBusca();
    } else {
        // Tenta novamente caso o header ainda não tenha carregado
        setTimeout(injetarBusca, 100);
    }
}

function ativarEventosBusca() {
    const inputBusca = document.getElementById('search-input');
    const listaResultados = document.getElementById('results-list');

    if (!inputBusca) return;

    inputBusca.addEventListener('input', () => {
        const termo = inputBusca.value.trim();

        if (termo.length >= 2) {
            // Busca os dados via PHP usando a baseUrl global
            // No seu search.js, mude a linha do fetch para:
                fetch(`${baseUrl}/site/data/pesquisa_logica.php?q=${encodeURIComponent(termo)}&modo=compacto`)
                .then(response => response.text())
                .then(html => {
                    listaResultados.innerHTML = html;
                    listaResultados.style.display = 'block';
                })
                .catch(err => console.error("Erro na busca de corvos:", err));
        } else {
            listaResultados.innerHTML = '';
            listaResultados.style.display = 'none';
        }
    });

    // Fecha a lista ao clicar fora da busca
    document.addEventListener('click', (e) => {
        if (!inputBusca.contains(e.target) && !listaResultados.contains(e.target)) {
            listaResultados.style.display = 'none';
        }
    });
}

// Inicia o processo de injeção
injetarBusca();