function adicionarAmigo(idAmigo) {
    const formData = new FormData();
    formData.append('adicionar_id', idAmigo);

    fetch('site/data/logica_amizade.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === "sucesso") {
            alert("Corvo adicionado à sua irmandade!");
            location.reload(); // Atualiza para mostrar o status
        } else if (data === "ja_existe") {
            alert("Este membro já faz parte do seu círculo.");
        }
    });
}

// Função para ver perfil alheio (limitado)
function verPerfilMembro(id) {
    window.location.href = `perfil_membro.php?id=${id}`;
}