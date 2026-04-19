const { app, BrowserWindow, session } = require('electron');
const path = require('path');

function createWindow() {
    const win = new BrowserWindow({
        width: 1280,
        height: 720,
        icon: path.join(__dirname, '../logo.ico'), //
        webPreferences: {
            nodeIntegration: false,
            contextIsolation: true,
            // O segredo está aqui: define um nome fixo para a partição de cookies
            partition: 'persist:korvado_session' 
        }
    });

    win.setMenuBarVisibility(false);

    // Carrega o nervo de redirecionamento que você criou
    win.loadURL('http://localhost:8080/Sistema-login/korvado.php');
}

// Isso garante que o nome da pasta em Roaming seja exatamente 'korvado'
app.name = 'korvado'; 

app.whenReady().then(createWindow);