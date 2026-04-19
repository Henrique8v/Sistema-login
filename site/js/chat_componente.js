// site/js/chat_componente.js

/* ESTRUTURA VISUAL DO CHAT 
   Injeta o HTML do chat dinamicamente no final do body
*/
const chatHTML = `
<div id="container-chat" style="position: fixed; bottom: 20px; right: 20px; z-index: 10001; font-family: 'Inter', sans-serif;">
    <button id="btn-chat" style="background: #F91313; color: white; border: none; padding: 10px 20px; border-radius: 50px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        Chat da Comunidade
    </button>

    <div id="janela-chat" style="display: none; width: 320px; height: 450px; background: rgba(0,0,0,0.95); border: 2px solid #F91313; border-radius: 10px; margin-bottom: 10px; flex-direction: column; overflow: hidden; box-shadow: 0 0 20px rgba(249, 19, 19, 0.3);">
        
        <div style="background: #F91313; padding: 10px; font-weight: bold; color: white; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 12px;">Membro: ${typeof USUARIO_LOGADO !== 'undefined' ? USUARIO_LOGADO.nome : 'Visitante'}</span>
            <span id="fechar-chat" style="cursor: pointer; padding: 0 5px; background: rgba(0,0,0,0.2); border-radius: 3px;">X</span>
        </div>
        
        <div id="mensagens-box" style="flex: 1; padding: 15px; overflow-y: auto; background: #111;">
            </div>

        <div style="padding: 10px; border-top: 1px solid #333; display: flex; background: #000;">
            <input type="text" id="input-msg-chat" placeholder="Escreva aqui..." 
                   style="flex: 1; background: #222; border: 1px solid #444; color: white; padding: 8px; border-radius: 4px; outline: none; font-size: 13px;">
            <button id="enviar-msg-chat" 
                    style="background: #F91313; border: none; color: white; margin-left: 5px; padding: 0 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">></button>
        </div>
    </div>
</div>
`;

// Injeção do HTML no Body
document.body.insertAdjacentHTML('beforeend', chatHTML);

/* LÓGICA DE FUNCIONAMENTO (AJAX/FETCH)
   Controla a interatividade e atualização sem refresh
*/
const btnChat = document.getElementById('btn-chat');
const janelaChat = document.getElementById('janela-chat');
const fecharChat = document.getElementById('fechar-chat');
const mensagensBox = document.getElementById('mensagens-box');
const inputMsg = document.getElementById('input-msg-chat');
const btnEnviar = document.getElementById('enviar-msg-chat');

// Ação de abrir o chat
btnChat.onclick = () => {
    janelaChat.style.display = 'flex';
    btnChat.style.display = 'none';
    atualizarChat(); // Carrega as mensagens assim que abrir
};

// Ação de fechar o chat
fecharChat.onclick = () => {
    janelaChat.style.display = 'none';
    btnChat.style.display = 'block';
};

// Função para enviar mensagem ao PHP
function enviarMensagem() {
    const msg = inputMsg.value;
    if (msg.trim() === "") return; // Impede envio vazio

    const formData = new FormData();
    formData.append('enviar_msg', '1');
    formData.append('mensagem', msg);

    // Envia para o arquivo de lógica que criamos
    fetch('chat_logica.php', {
        method: 'POST',
        body: formData
    }).then(() => {
        inputMsg.value = ''; // Limpa o campo
        atualizarChat();    // Atualiza a lista
    });
}

// Função para buscar novas mensagens no servidor
function atualizarChat() {
    fetch('../../chat_logica.php?atualizar_chat=1')
        .then(response => response.text())
        .then(html => {
            mensagensBox.innerHTML = html;
            // Faz o scroll descer automaticamente para a última mensagem
            mensagensBox.scrollTop = mensagensBox.scrollHeight;
        });
}

// Escutadores de eventos
btnEnviar.onclick = enviarMensagem;
inputMsg.onkeypress = (e) => { 
    if (e.key === 'Enter') enviarMensagem(); 
};

// Sistema de atualização automática (Pooling)
// Verifica novas mensagens a cada 3 segundos apenas se a janela estiver aberta
setInterval(() => {
    if (janelaChat.style.display === 'flex') {
        atualizarChat();
    }
}, 3000);