<?php 
// membros.php
require_once 'site/data/user.php'; 
require_once 'site/data/user_menu.php'; 
require_once 'classes/Usuarios.php';

$u = new Usuarios();
$u->conectar("projeto_login", "localhost", "root", "");
$pdo = $u->getPdo();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Membros - Korvado™</title> 
    <link rel="stylesheet" href="site/css/menu.css">
    <link rel="stylesheet" href="site/css/template.css">

    <style>
        body{
            background-image: URL(imagens/crowswp.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
            background-attachment: fixed;
            color: white;
            font-size: 19px;
            font-family: 'Inter', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.7;
        }     
        .busca-container { 
            max-width: 800px;
            width: 90%;			
            margin: 100px auto; 
            padding: 20px; 
            background: rgba(0,0,0,0.8); 
            border-radius: 8px; 
            border: 1px solid #333; 
        }
        #input-busca { 
            width: 100%; 
            padding: 15px; 
            background: #222; 
            border: 1px solid #444; 
            color: white; 
            border-radius: 4px; 
            font-size: 18px; 
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        .resultado-card { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 15px; 
            border-bottom: 1px solid #333; 
            transition: 0.3s; 
        }
        .resultado-card:hover { background: rgba(249, 19, 19, 0.1); }
        .info-membro { display: flex; align-items: center; gap: 15px; }
        .info-membro img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #F91313; }
        .btn-perfil { text-decoration: none; color: #67c1f5; font-size: 14px; font-weight: bold; }
        .btn-add { background: #F91313; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-add:disabled { background: #444; cursor: default; }
    </style>
</head>
<body>

    <?php require_once 'site/data/user_menu.php'; ?>

    <div class="busca-container">
        <h1 style="font-family: 'Cinzel', serif; color: #F91313; text-align: center; margin-bottom: 30px; letter-spacing: 3px;">BUSCAR CORVOS</h1>
        <input type="text" id="input-busca" placeholder="Digite o nome do membro da Irmandade..." onkeyup="pesquisar()">

        <div id="lista-resultados">
            <p style="text-align: center; color: #555; font-size: 14px;">Inicie a busca para encontrar aliados.</p>
        </div>
    </div>

    <script>
    function pesquisar() {
        let texto = document.getElementById('input-busca').value;
        // Só pesquisa se tiver mais de 2 caracteres para não sobrecarregar o MySQL
        if(texto.length > 2) {
            fetch(baseUrl + '/site/data/pesquisa_logica_membros.php?q=' + encodeURIComponent(texto))
                .then(res => res.text())
                .then(html => {
                    document.getElementById('lista-resultados').innerHTML = html;
                });
        } else if (texto.length == 0) {
            document.getElementById('lista-resultados').innerHTML = "";
        }
    }

    function adicionarAmigo(id) {
        let data = new FormData();
        data.append('adicionar_id', id);
        
        const botaoClicado = document.getElementById('btn-add-' + id);
        
        // Desabilita imediatamente para evitar cliques duplos
        if(botaoClicado) botaoClicado.disabled = true;

        fetch(baseUrl + '/site/data/amizade_logica.php', {
            method: 'POST',
            body: data
        })
        .then(res => res.text())
        .then(resultado => {
            // Chama a função global do header.js para avisos
            if(typeof mostrarAviso === "function") {
                mostrarAviso("Solicitação de aliança enviada");
            }

            if(botaoClicado) {
                botaoClicado.style.opacity = '0.5';
                setTimeout(() => {
                    botaoClicado.innerHTML = "PENDENTE";
                    botaoClicado.style.backgroundColor = "#2a475e";
                    botaoClicado.style.color = "#67c1f5";
                    botaoClicado.style.cursor = "default";
                    botaoClicado.disabled = true;
                    botaoClicado.style.opacity = '1';
                }, 300);
            }
        })
        .catch(err => {
            console.error("Erro na irmandade:", err);
            if(botaoClicado) botaoClicado.disabled = false;
        });
    }
    </script>
    
    <script src="site/js/header.js"></script>
    <script src="site/js/footer.js"></script>
</body>
</html>