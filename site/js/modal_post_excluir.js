function excluirComentario(idC, idP) {
    if(!confirm("Eliminar este rastro?")) return;
    let fd = new FormData();
    fd.append('excluir_id', idC);
    fetch('site/data/comentar_logica.php', { method: 'POST', body: fd }).then(() => recarregarDadosPost(idP));
}