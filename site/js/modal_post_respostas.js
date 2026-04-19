function prepararResposta(idPai, nome) {
    comentarioParentId = idPai;
    const input = document.getElementById('texto-comentario');
    input.placeholder = "Respondendo a " + nome + "...";
    input.focus();
}

function toggleRespostas(id) {
    const c = document.getElementById(`respostas-container-${id}`);
    const b = document.getElementById(`btn-respostas-${id}`);
    const isHidden = c.style.display === "none";
    c.style.display = isHidden ? "block" : "none";
    b.innerText = isHidden ? "── Ocultar" : "── Ver respostas";
}