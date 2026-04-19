function formatarDataRedeSocial(dataString) {
    const agora = new Date();
    const dataPost = new Date(dataString);
    const segundos = Math.floor((agora - dataPost) / 1000);
    if (segundos < 60) return segundos + "s";
    const minutos = Math.floor(segundos / 60);
    if (minutos < 60) return minutos + "min";
    const horas = Math.floor(minutos / 60);
    if (horas < 24) return horas + "h";
    const dias = Math.floor(horas / 24);
    if (dias < 7) return dias + "d";
    return dataPost.toLocaleDateString('pt-BR');
}
