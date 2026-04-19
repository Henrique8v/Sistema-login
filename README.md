# Korvado Social Network Site

🦅 Ecossistema completo de rede social e gestão, operando sob uma arquitetura Cliente-Servidor. O núcleo do projeto é desenvolvido em PHP e MySQL, permitindo acesso universal via navegador, com suporte a automações em Python e uma interface desktop opcional.

🛠️ Arquitetura do Sistema
O projeto está dividido em três camadas principais:

Backend (Servidor): Processamento em PHP 8.2+ e base de dados MySQL (XAMPP).

Automação & Monitorização: Scripts em Python 3.13 que gerem logs, limpeza de ficheiros e túneis de acesso.

Frontend (Interface): Web Design responsivo (HTML5/CSS3) acessível por qualquer navegador.

🚀 Como Instalar e Usar (Modo Padrão)
1. Configuração do Servidor Local
Instale o XAMPP (Apache e MySQL).

Crie uma base de dados no phpMyAdmin chamada projeto_login.

Importe o ficheiro SQL do projeto para estruturar as tabelas da Brotherhood.

Certifique-se de que a pasta do projeto está em C:\xampp\htdocs\Sistema-login\.

2. Configuração de Acesso
Ajuste as credenciais no ficheiro config.php para ligar o sistema ao banco de dados:

PHP
$host    = 'localhost';
$dbname  = 'projeto_login';
$user    = 'root';
$pass    = ''; // Padrão do XAMPP
3. Motores de Background (Python)
Para que o sistema de "Rastros" (logs) e monitorização funcione, execute:

ligar_rastros_silent.py: Inicia o monitor em modo invisível.

limpeza_arquivos.py: Mantém a integridade da pasta de uploads.

🖥️ Opções de Acesso (Client-Side)
O RV Project é flexível quanto à forma de interação:

A. Via Navegador (Recomendado para Usuários)
Basta aceder a qualquer um dos pontos de entrada:

localhost/Sistema-login/index.php (Login)

localhost/Sistema-login/acess_panel.htm (Painel de Controlo)

B. Via Electron (Interface Desktop Opcional)
Se desejar uma UI dedicada que funcione como um aplicativo nativo:

Requer Node.js e Electron instalados.

Aceda à pasta core_electron e execute npm start.

O Electron servirá apenas como uma janela (WebView) para o servidor PHP, oferecendo persistência de sessão avançada no diretório AppData/Roaming/korvado.

🔐 Funcionalidades Core
Timeline Interativa: Suporte a vídeos com auto-play inteligente e imagens.

Sistema de Rastros: Comentários recursivos e logs de atividade em tempo real.

Deep Analysis: Binário em C++ para verificação física de integridade de ficheiros.

Gestão de Túneis: Integração com Pinggy/Ngrok para expor o servidor local à web.

🤝 Contribuições e Incentivo
Este projeto foi idealizado por Lorde Raveno para incentivar novos programadores a explorarem a integração entre diferentes linguagens (PHP, Python, C++, JS). Sinta-se à vontade para clonar, modificar e expandir a Brotherhood.

<<<<<<< HEAD
Desenvolvido por Lorde Raveno // RV Project Inc. 🦾
=======
Desenvolvido por Lorde Raveno // RV Project Inc. 🦾
>>>>>>> 8dbbedd6402602890fa20b31884c9dda5b3ea120
