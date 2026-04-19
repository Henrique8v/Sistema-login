document.getElementById('form-perfil').addEventListener('submit', function(e) {
    // 1. Opcional: Mostrar um feedback visual de "Salvando..."
    const btn = this.querySelector('button');
    const originalText = btn.innerText;
    btn.innerText = "SALVANDO E ORGANIZANDO...";
    btn.disabled = true;

    // 2. Disparar a limpeza via fetch (AJAX) em segundo plano
    // Isso chama o seu arquivo PHP que executa o Python
    fetch('site/data/limpeza_arquivos.php')
        .then(response => response.text())
        .then(data => {
            console.log("Faxina Python concluída em segundo plano.");
        });

    // O formulário seguirá seu curso normal para o atualizar_user.php
    // Se quiser que TUDO seja sem reload, usaríamos e.preventDefault(), 
    // mas para manter sua estrutura atual, deixaremos ele seguir após o fetch.
});