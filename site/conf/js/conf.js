// 1. Alternar blocos (Funciona com seu HTML original)
function toggleExclusao(mostrar) {
    document.getElementById('bloco-deletar-inicial').style.display = mostrar ? 'none' : 'flex';
    document.getElementById('confirmacao-exclusao').style.display = mostrar ? 'flex' : 'none';
}

// 2. Sua lógica de tema (MANTIDA INTEGRALMENTE)
function updateConfig(campo, valor) {
    let fd = new FormData();
    fd.append('campo', campo);
    fd.append('valor', valor);
    fetch('conf.php?ajax_update=1', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'sucesso' && campo === 'tema') {
            document.body.classList.toggle('modo-claro', valor === 'claro');
            document.body.classList.toggle('claro-mode', valor === 'claro');
        }
    });
}

// 3. A correção da Senha BCRYPT
// site/conf/js/conf.js

// 3. Executar Exclusão usando baseUrl
function executarExclusao() {
    const senha = document.getElementById('senha-mestra').value;
    const erroDiv = document.getElementById('erro-exclusao');
    
    if (!senha) {
        erroDiv.innerText = "⚠️ Digite sua senha.";
        erroDiv.style.display = 'block';
        return;
    }

    let fd = new FormData();
    fd.append('senha_excluir', senha);

    // USANDO BASEURL + CAMINHO FIXO
    fetch(baseUrl + 'site/conf/php/deletar_conta.php', {
        method: 'POST',
        body: fd
    })
    .then(res => {
        if (!res.ok) throw new Error("Erro na rede");
        return res.json();
    })
    .then(data => {
        if (data.status === 'sucesso') {
            alert("Conta encerrada. Seus rastros foram apagados.");
            window.location.href = baseUrl + 'index.php';
        } else {
            erroDiv.innerText = "❌ " + (data.detalhe || "Senha incorreta.");
            erroDiv.style.display = 'block';
        }
    })
    .catch(err => {
        console.error("Erro:", err);
        erroDiv.innerText = "⚠️ Erro de comunicação: Verifique a baseUrl ou o caminho do arquivo.";
        erroDiv.style.display = 'block';
    });
}