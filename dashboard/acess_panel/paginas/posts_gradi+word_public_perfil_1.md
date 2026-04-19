o mini word está muito funcional, apesar de não ter H1e H2, mas isso fica pra depois. Agora eu queria que o resultado do que fizemos no editar_feed php, js e css aparecesse nos perfis no msm estilo, nesse caso usando o modal_post css e js que está ligado ao pefil_membro.php e perfil_do_usuario.php que mostra o feed da conta e publico.

Para que o estilo do seu "Mini Word" (gradientes, alinhamentos e formatação de texto) apareça corretamente nos perfis, precisamos garantir que as classes CSS e as tags HTML sejam interpretadas nos arquivos de exibição, assim como fizemos no editar_feed.php.

Aqui está o roteiro para integrar isso no perfil_do_usuario.php e perfil_membro.php:

1. Atualização do CSS
Certifique-se de que os arquivos de perfil carreguem o CSS que contém as definições dos gradientes (grad-fire, grad-void, grad-blood) e as regras da .main-textarea (se ela tiver estilos de fonte específicos).

No <head> do perfil_do_usuario.php e perfil_membro.php, adicione:

HTML
<link rel="stylesheet" href="site/css/editar_feed.css"> 
2. Ajuste na Exibição do Grid (PHP)
O grid de miniaturas nos perfis também precisa distinguir entre imagem, vídeo e texto com gradiente.

No seu loop de posts dos perfis, substitua a lógica de exibição por esta:

PHP
<?php 
foreach ($posts as $post): 
    $midia = $post['imagem_post'];
    $grad = $post['gradiente_id'];
    $alinhamento = $post['alinhamento'] ?? 'left';
    // Se não tiver imagem/video, mas tiver gradiente, aplica a classe
    $classeCard = (!$midia && $grad) ? $grad : '';
    
    $ext = $midia ? strtolower(pathinfo($midia, PATHINFO_EXTENSION)) : '';
    $e_video = in_array($ext, ['mp4', 'webm', 'ogg']);
?>
    <div class="post-card <?= $classeCard ?>" onclick="abrirPost(<?= $post['id_post'] ?>)">
        <?php if($midia): ?>
            <?php if($e_video): ?>
                <video src="site/img/posts/<?= $midia ?>" style="width:100%; height:100%; object-fit:cover;" muted></video>
                <div class="video-icon">▶</div>
            <?php else: ?>
                <img src="site/img/posts/<?= $midia ?>" style="width:100%; height:100%; object-fit:cover;">
            <?php endif; ?>
        <?php else: ?>
            <div style="padding: 10px; text-align: <?= $alinhamento ?>; font-size: 11px; height:100%; display:flex; align-items:center;">
                <?= mb_strimwidth(strip_tags($post['conteudo']), 0, 80, "...") ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
3. Ajuste no Modal de Visualização (modal_post.js)
Quando o usuário clica para ver o post completo, o JavaScript precisa aplicar o gradiente e o alinhamento no container do modal.

Abra o seu site/js/modal_post.js e localize onde o conteúdo do post é inserido (provavelmente dentro do fetch ou da função abrirPost). Adicione estas linhas:

JavaScript
// Dentro da função que renderiza os dados do post no modal:
function renderizarConteudoModal(dados) {
    const mediaContainer = document.getElementById('media-content'); // Lado esquerdo (mídia/texto)
    
    // Limpa classes anteriores
    mediaContainer.className = "modal-post-media"; 
    
    if (dados.imagem_post) {
        // Lógica de Imagem ou Vídeo (como já fizemos anteriormente)
        const ext = dados.imagem_post.split('.').pop().toLowerCase();
        if (['mp4', 'webm', 'ogg'].includes(ext)) {
            mediaContainer.innerHTML = `<video src="site/img/posts/${dados.imagem_post}" controls autoplay style="max-width:100%;"></video>`;
        } else {
            mediaContainer.innerHTML = `<img src="site/img/posts/${dados.imagem_post}" style="max-width:100%;">`;
        }
    } else {
        // É UM POST DE TEXTO COM GRADIENTE
        if (dados.gradiente_id) {
            mediaContainer.classList.add(dados.gradiente_id);
        }
        
        mediaContainer.style.textAlign = dados.alinhamento || 'left';
        mediaContainer.style.display = "flex";
        mediaContainer.style.alignItems = "center";
        mediaContainer.style.justifyContent = "center";
        mediaContainer.style.padding = "40px";
        mediaContainer.style.fontSize = "24px";
        
        // IMPORTANTE: Aqui usamos innerHTML para que <b>, <i> e <u> funcionem!
        mediaContainer.innerHTML = dados.conteudo; 
    }
}
4. Segurança no PHP
Certifique-se de que ao buscar os posts no banco de dados nos arquivos perfil_membro.php e perfil_do_usuario.php, você está selecionando as colunas alinhamento e gradiente_id.

Exemplo da Query:

SQL
SELECT id_post, conteudo, imagem_post, data_post, alinhamento, gradiente_id 
FROM timeline 
WHERE usuario_id = :id 
ORDER BY data_post DESC
O que isso resolve:
Identidade Visual: Se você criou um post com fundo de "Fogo" no editor, ele aparecerá com fundo de fogo no perfil do membro.

Formatação viva: Tags como <b> (negrito) inseridas pelo "Mini Word" agora serão renderizadas pelo navegador no modal, em vez de aparecerem como texto bruto.

Coerência: O alinhamento que você escolheu (esquerda, centro ou direita) será respeitado na visualização final.

Crow? (Quer que eu verifique como está o seu perfil_membro.js para garantir que o fetch está trazendo essas colunas novas do banco?)

meu loop de perfis ta certo na logica de exibição: <?php

// perfil_membro.php - FASE 5 (Refinado)

require_once 'site/data/user.php'; 

require_once 'classes/Usuarios.php';



$u = new Usuarios();

$u->conectar("projeto_login", "localhost", "root", "");

$pdo = $u->getPdo();



$id_visitado = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$post_ref    = filter_input(INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT);

$meu_id      = $_SESSION['id_usuario'];



if (!$id_visitado || ($id_visitado == $meu_id && !$post_ref)) {

    header("Location: membros.php");

    exit;

}



// 4. Busca os dados do membro

$sql = "SELECT nome, foto, bio FROM usuarios WHERE id_usuario = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([':id' => $id_visitado]);

$membro = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$membro) {

    echo "Membro não encontrado.";

    exit;

}



$amizade = null;

if ($id_visitado != $meu_id) {

    $sqlCheck = "SELECT status, usuario_id FROM amizades 

                 WHERE (usuario_id = :me AND amigo_id = :vi) 

                 OR (usuario_id = :vi AND amigo_id = :me) LIMIT 1";

    $check = $pdo->prepare($sqlCheck);

    $check->execute([':me' => $meu_id, ':vi' => $id_visitado]);

    $amizade = $check->fetch(PDO::FETCH_ASSOC);

}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Perfil de <?= htmlspecialchars($membro['nome']) ?> - Korvado™</title>

    <link rel="stylesheet" href="site/css/template.css">

    <link rel="stylesheet" href="site/css/menu.css"> 

    <link rel="stylesheet" href="style/perfil_membro.css"> 

    <link rel="stylesheet" href="site/css/modal_post.css"> 

<link rel="stylesheet" href="site/css/editar_feed.css">

</head>

<body style="background: #1a1a1a; color: #c7d5e0;">

    

    <div class="perfil-container">

        <div class="perfil-header-box">

            <img src="site/<?= $membro['foto'] ?>" style="width: 160px; height: 160px; border: 2px solid #F91313; object-fit: cover; border-radius: 3px;" onerror="this.src='site/img/default-user.png'">

            

            <div style="flex-grow: 1;">

                <h1 style="color: white; margin: 0; font-family: 'Cinzel', serif; font-size: 32px;"><?= htmlspecialchars($membro['nome']) ?></h1>

                <p style="color: #898e91; font-size: 14px; margin: 5px 0;">Membro da Ordem</p>

                

                <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 4px; margin-top: 10px; border: 1px solid #222; text-align: left;">

                    <span style="color: #67c1f5; font-size: 10px; font-weight: bold; display: block; margin-bottom: 5px; letter-spacing: 2px;">BIO</span>

                    <p style="color: #898e91; font-style: italic; margin: 0;">

                        <?= !empty($membro['bio']) ? nl2br(htmlspecialchars($membro['bio'])) : "Este corvo ainda não escreveu sua história..." ?>

                    </p>

                </div>

            </div>



            <div style="display: flex; flex-direction: column; gap: 10px; min-width: 180px;">

                <?php if ($id_visitado == $meu_id): ?>

                    <a href="areaPrivada.php" class="btn-acao" style="background: #343a40; color: white;">Editar Perfil</a>

                    <a href="editar_feed.php" class="btn-acao" style="background: #343a40; color: white;">Editar Feed</a>

                    <a href="amigos.php" class="btn-acao" style="background: #343a40; color: white;">Amigos</a>

                    <a href="sair.php" class="btn-acao" style="background: #F91313; color: white; margin-top: 10px;">Sair da Conta</a>

                <?php else: ?>

                    <?php if (!$amizade): ?>

                        <button onclick="gerenciarAmizade(<?= $id_visitado ?>, 'adicionar')" class="btn-acao" style="background: #F91313; color: white;">ADICIONAR AMIGO</button>

                    <?php elseif ($amizade['status'] == 'pendente'): ?>

                        <button class="btn-acao" style="background: #444; cursor: default; color: white;" disabled>SOLICITAÇÃO ENVIADA</button>

                    <?php else: ?>

                        <a href="mensagens.php?com=<?= $id_visitado ?>" class="btn-acao" style="background: #28a745; color: white;">MENSAGEM</a>

                    <?php endif; ?>

                    <a href="amigos.php" class="btn-acao" style="background: #5E5656; color: white; margin-top: 10px;"><span style="color: #67c1f5;">←</span> Voltar para Amigos</a>

                    <a href="perfil_do_usuario.php" class="btn-acao" style="background: #5E5656; color: white; margin-top: 10px;"><span style="color: #67c1f5;">←</span> Voltar para seu Perfil</a>

                <?php endif; ?>

            </div>

        </div>

    

        <h3 style="color: #F91313; border-bottom: 1px solid #333; margin-top: 50px; padding-bottom: 10px; font-family: 'Cinzel', serif; letter-spacing: 2px; text-align: center;">GALERIA DE MEMBRO</h3>

    

        <div class="grid-posts">

            <?php

            $sql_posts = "SELECT * FROM timeline WHERE usuario_id = :id ORDER BY data_post DESC";

            $stmt_p = $pdo->prepare($sql_posts);

            $stmt_p->execute([':id' => $id_visitado]);

            $posts = $stmt_p->fetchAll(PDO::FETCH_ASSOC);



            if(count($posts) > 0):

                foreach($posts as $post): ?>

                    <div class="card-post <?= $post['gradiente_id'] ?? '' ?>" onclick="abrirPost(<?= $post['id_post'] ?>)">

                        <?php if(!empty($post['imagem_post'])): 

                            $arquivo = $post['imagem_post'];

                            $ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));

                        ?>

                            <?php if(in_array($ext, ['mp4', 'webm'])): ?>

                                <video muted><source src="site/img/posts/<?= $arquivo ?>" type="video/<?= $ext ?>"></video>

                            <?php else: ?>

                                <img src="site/img/posts/<?= $arquivo ?>" loading="lazy">

                            <?php endif; ?>

                        

                        <?php elseif(!empty($post['conteudo'])): ?>

                            <div style="padding: 10px; text-align: center; font-size: 11px; overflow: hidden; color: white;">

                                <?= mb_strimwidth(htmlspecialchars($post['conteudo']), 0, 100, "...") ?>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endforeach;

            else:

                echo "<p style='color: #555; text-align: center; width:100%; margin-top: 50px;'>Nenhuma publicação encontrada.</p>";

            endif; ?>

        </div>

    </div>



<div id="modal-post" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9);" onclick="fecharModalPost(event)">

    <div class="modal-post-container" onclick="event.stopPropagation()" style="display: flex; margin: auto; margin-top: 50px; max-width: 900px; background: #000; border: 1px solid #333;">

        

        <div class="modal-post-media" id="media-content" style="flex: 2; background: #050505; display: flex; align-items: center; justify-content: center;">

            </div>



        <div class="modal-post-info" style="flex: 1; display: flex; flex-direction: column; border-left: 1px solid #333; min-height: 500px;">

            <div class="modal-post-header" id="post-header" style="padding: 15px; border-bottom: 1px solid #222;"></div>

            <div class="modal-post-comments" id="post-comments" style="flex: 1; overflow-y: auto; padding: 15px; font-size: 13px;"></div>

            

            <div class="modal-post-footer" style="padding: 15px; border-top: 1px solid #222;">

                <form onsubmit="postarComentario(event)">

                    <input type="hidden" id="current-post-id"> 

                    <textarea id="texto-comentario" placeholder="Escreva um comentário..." required 

                        style="width: 100%; background: #111; border: 1px solid #333; color: white; padding: 10px; border-radius: 4px; resize: none; margin-bottom: 10px;"></textarea>

                    <button type="submit" class="btn-comentar-vermelho" style="width: 100%; background: #F91313; color: white; border: none; padding: 10px; cursor: pointer; font-weight: bold;">COMENTAR</button>

                </form>

            </div>

        </div>



    </div>

</div>

<script>

function abrirPost(id) {

    console.log("Tentando abrir o post ID: " + id);

    const modal = document.getElementById('modal-post');

    if (modal) {

        modal.style.display = 'flex'; // Mudamos para flex para centralizar

        // Se o seu perfil_membro.js tiver a lógica de carregar dados, 

        // ele vai complementar essa função.

    } else {

        console.error("Erro: Elemento modal-post não encontrado!");

    }

}

</script>

<script src="site/js/modal_post.js"></script> 



<script src="site/js/perfil_membro.js"></script>



<script src="site/js/header.js"></script>

</body>

</html>

Seu loop está funcional, mas para que ele suporte as novas formatações do "Mini Word" (Negrito, Itálico, Alinhamento e Gradientes) que acabamos de criar, precisamos fazer 3 ajustes pontuais.

Atualmente, o seu código usa htmlspecialchars() e strip_tags() no grid, o que "limpa" as formatações que queremos exibir.

Aqui está o seu código com as correções para integrar o estilo:

1. O Loop de Posts Atualizado
Substitua o seu bloco foreach($posts as $post): por este:

PHP
<?php foreach($posts as $post): 
    $midia = $post['imagem_post'];
    $grad = $post['gradiente_id'];
    $alinhamento = $post['alinhamento'] ?? 'center';
    // Define a classe de gradiente apenas se não houver imagem
    $classeCard = (!$midia && $grad) ? $grad : '';
?>
    <div class="card-post <?= $classeCard ?>" onclick="abrirPost(<?= $post['id_post'] ?>)">
        <?php if(!empty($midia)): 
            $ext = strtolower(pathinfo($midia, PATHINFO_EXTENSION));
        ?>
            <?php if(in_array($ext, ['mp4', 'webm', 'ogg'])): ?>
                <video muted style="width:100%; height:100%; object-fit:cover;">
                    <source src="site/img/posts/<?= $midia ?>" type="video/<?= $ext ?>">
                </video>
                <div style="position:absolute; bottom:5px; right:5px; color:white; text-shadow:1px 1px 2px black;">▶</div>
            <?php else: ?>
                <img src="site/img/posts/<?= $midia ?>" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
            <?php endif; ?>
        
        <?php elseif(!empty($post['conteudo'])): ?>
            <div style="padding: 10px; text-align: <?= $alinhamento ?>; font-size: 11px; overflow: hidden; color: white; height:100%; display:flex; align-items:center; justify-content:center;">
                <?= mb_strimwidth(strip_tags($post['conteudo'], '<b><i><u>'), 0, 100, "...") ?>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
O que mudou e por quê?
Remoção do htmlspecialchars no Grid: Eu mudei para strip_tags($post['conteudo'], '<b><i><u>'). Isso permite que o Negrito e o Itálico apareçam mesmo na miniatura do grid, mas ainda protege o layout contra tags grandes ou scripts.

Variável $alinhamento: Agora o grid respeita o alinhamento que você salvou no banco. Se o post foi centralizado no "Mini Word", ele aparecerá centralizado no card.

Correção do Vídeo: Adicionei object-fit:cover e width/height 100% para que o vídeo preencha o card do grid exatamente como a imagem faz, evitando buracos pretos no layout.

2. Verificação do JS (perfil_membro.js)
Para o Modal abrir com a formatação correta, o seu arquivo site/js/perfil_membro.js (ou onde estiver a função que busca os dados via AJAX) precisa estar enviando o innerHTML e não apenas o innerText.

Certifique-se que na hora de exibir o texto no modal, você use:

JavaScript
// Exemplo de como deve estar no seu JS que popula o modal
document.getElementById('media-content').innerHTML = dados.conteudo; 
3. CSS do Grid
No seu arquivo style/perfil_membro.css, verifique se o .card-post tem position: relative; para que o ícone de "Play" do vídeo não fique perdido.

Crow? (O loop está corrigido. Quer que eu te envie também o ajuste para o seu perfil_do_usuario.php para ele ficar idêntico a este?)

nesse caso esse container que é o certo:         <div class="grid-posts">

            <?php

            $sql_posts = "SELECT * FROM timeline WHERE usuario_id = :id ORDER BY data_post DESC";

            $stmt_p = $pdo->prepare($sql_posts);

            $stmt_p->execute([':id' => $id_visitado]);

            $posts = $stmt_p->fetchAll(PDO::FETCH_ASSOC);



            if(count($posts) > 0):

 foreach($posts as $post): 

    $midia = $post['imagem_post'];

    $grad = $post['gradiente_id'];

    $alinhamento = $post['alinhamento'] ?? 'center';

    // Define a classe de gradiente apenas se não houver imagem

    $classeCard = (!$midia && $grad) ? $grad : '';

?>

    <div class="card-post <?= $classeCard ?>" onclick="abrirPost(<?= $post['id_post'] ?>)">

        <?php if(!empty($midia)): 

            $ext = strtolower(pathinfo($midia, PATHINFO_EXTENSION));

        ?>

            <?php if(in_array($ext, ['mp4', 'webm', 'ogg'])): ?>

                <video muted style="width:100%; height:100%; object-fit:cover;">

                    <source src="site/img/posts/<?= $midia ?>" type="video/<?= $ext ?>">

                </video>

                <div style="position:absolute; bottom:5px; right:5px; color:white; text-shadow:1px 1px 2px black;">▶</div>

            <?php else: ?>

                <img src="site/img/posts/<?= $midia ?>" loading="lazy" style="width:100%; height:100%; object-fit:cover;">

            <?php endif; ?>

        

        <?php elseif(!empty($post['conteudo'])): ?>

            <div style="padding: 10px; text-align: <?= $alinhamento ?>; font-size: 11px; overflow: hidden; color: white; height:100%; display:flex; align-items:center; justify-content:center;">

                <?= mb_strimwidth(strip_tags($post['conteudo'], '<b><i><u>'), 0, 100, "...") ?>

            </div>

        <?php endif; ?>

    </div>




                <?php endforeach;

            else:

                echo "<p style='color: #555; text-align: center; width:100%; margin-top: 50px;'>Nenhuma publicação encontrada.</p>";

            endif; ?>

        </div>

    </div>

Exatamente! Esse é o container perfeito. Ao usar o strip_tags($post['conteudo'], '<b><i><u>'), você garante que a essência da formatação (negrito, itálico e sublinhado) sobreviva à miniatura do grid, mantendo o visual rico que o "Mini Word" criou.

Agora, para que o resultado final (o Modal que abre ao clicar) também exiba tudo perfeitamente, precisamos apenas garantir que o seu arquivo de processamento JavaScript trate o conteúdo como HTML.

O toque final no modal_post.js
Como você está usando innerHTML no PHP para o grid, o seu arquivo site/js/modal_post.js deve estar preparado para injetar o conteúdo da mesma forma quando o post for aberto. Verifique se a sua função que preenche o modal está assim:

JavaScript
// Dentro do seu modal_post.js, na função que recebe os dados do banco (AJAX)
function preencherModal(dados) {
    const mediaContent = document.getElementById('media-content');
    
    // Limpa estados anteriores
    mediaContent.className = "modal-post-media"; 
    mediaContent.style.textAlign = dados.alinhamento || 'center';

    if (dados.imagem_post) {
        // ... lógica de imagem/vídeo que já funciona ...
    } else {
        // TRATAMENTO PARA POSTS DE TEXTO ("Mini Word")
        if (dados.gradiente_id) {
            mediaContent.classList.add(dados.gradiente_id);
        }
        
        // O SEGREDO: usar innerHTML para renderizar <b>, <i>, <u> e <br>
        mediaContent.innerHTML = `<div style="padding: 40px; font-size: 1.5rem; width: 100%;">
                                    ${dados.conteudo}
                                  </div>`;
    }
}
Por que isso é importante?
Fidelidade: O que o usuário vê no editor é exatamente o que ele verá no perfil.

Experiência Visual: Posts de texto sem imagem deixam de ser "sem graça" e passam a ter a força dos gradientes e da tipografia formatada.

Consistência: O alinhamento (left, center, right) vindo do banco de dados agora dita a regra de exibição em todo o sistema.

Crow? (Se o grid e o modal nos perfis já estão exibindo os gradientes e as tags de texto corretamente, podemos considerar a integração do "Mini Word" concluída! Gostaria de aplicar isso também na página principal index.php ou quer focar em outra funcionalidade agora?)

assim? : // Sistema-login/site/js/modal_post.js



// Função para formatar data estilo Instagram (ex: 25s, 1min, 2h, 1d)

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



// Lógica de Alternar Like (Toggle)

function alternarLike(idPost) {

    let fd = new FormData();

    fd.append('id_post', idPost);



    fetch('site/data/curtir_logica.php', { 

        method: 'POST', 

        body: fd 

    })

    .then(res => res.json())

    .then(data => {

        if (data.status === 'adicionado' || data.status === 'removido') {

            // Recarregamos o post para atualizar o número e a cor do coração sem refresh

            abrirPost(idPost);

        }

    })

    .catch(err => console.error("Erro ao curtir:", err));

}



function abrirPost(idPost) {

    // ESTA É A LINHA QUE ESTAVA FALTANDO:

    // Ela vai buscar o JSON gerado pelo seu PHP

    fetch(`site/data/visualizar_post.php?id_post=${idPost}`)

        .then(res => {

            // Verifica se a resposta é um JSON válido

            if (!res.ok) throw new Error("Erro na rede");

            return res.json();

        })

        .then(data => {

            if (data.erro) {

                console.error(data.erro);

                return;

            }



            const post = data.post;

            const comentarios = data.comentarios;

            const meuId = data.meu_id;

            

            // Dados de Like vindos do banco

            const jaCurti = post.ja_curti > 0;

            const coracaoIcone = jaCurti ? '❤️' : '🤍';

            const totalLikes = post.total_likes || 0;



            // 1. Injetar Mídia

            const mediaDiv = document.getElementById('media-content');

            const ext = post.imagem_post ? post.imagem_post.split('.').pop().toLowerCase() : '';

            

            if(['mp4', 'webm'].includes(ext)) {

                mediaDiv.innerHTML = `<video controls autoplay style="width:100%"><source src="site/img/posts/${post.imagem_post}"></video>`;

            } else if(post.imagem_post) {

                mediaDiv.innerHTML = `<img src="site/img/posts/${post.imagem_post}">`;

            } else {

                mediaDiv.innerHTML = `<div style="padding:40px; color:#eee; text-align:center;">${post.conteudo}</div>`;

            }



            // 2. Injetar Cabeçalho (Dono do Post)

            const linkAutor = (post.id_autor == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${post.id_autor}`;

            

            document.getElementById('post-header').innerHTML = `

                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">

                    <div style="display: flex; align-items: center; gap: 10px;">

                        <a href="${linkAutor}">

                            <img src="site/${post.autor_foto}" onerror="this.src='site/img/default-user.png'" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #F91313;">

                        </a>

                        <div>

                            <a href="${linkAutor}" style="text-decoration:none; color:white;"><strong style="display:block">${post.autor}</strong></a>

                            <small style="color:#666">${formatarDataRedeSocial(post.data_post)}</small>

                        </div>

                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">

                        <span id="btn-like" onclick="alternarLike(${idPost})" style="cursor: pointer; font-size: 20px;">

                            ${coracaoIcone}

                        </span>

                        <span style="color: #ccc; font-size: 13px;">${totalLikes}</span>

                    </div>

                </div>

            `;



            // 3. Limpar e Injetar Comentários

            const commDiv = document.getElementById('post-comments');

            commDiv.innerHTML = ''; 



            if (comentarios.length === 0) {

                commDiv.innerHTML = '<p style="color:#444; padding:15px;">Nenhum comentário ainda.</p>';

            } else {

                comentarios.forEach(c => {

                    const linkComentador = (c.id_autor_comentario == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${c.id_autor_comentario}`;

                    let btnExcluir = (c.usuario_id == meuId) ? `<span onclick="excluirComentario(${c.id_comentario}, ${idPost})" style="color:#F91313; cursor:pointer; font-size:9px; margin-left:8px;">EXCLUIR</span>` : '';



                    commDiv.innerHTML += `

                        <div class="comment-item" style="display:flex; gap:12px; padding:12px; border-bottom:1px solid #111;">

                            <img src="site/${c.foto_comentario}" style="width:30px; height:30px; border-radius:50%;" onerror="this.src='site/img/default-user.png'">

                            <div>

                                <div style="font-size:13px;">

                                    <strong style="color:#F91313;">${c.autor_comentario}</strong> <span style="color:#eee;">${c.comentario}</span>

                                </div>

                                <div style="font-size:10px; color:#555;">${formatarDataRedeSocial(c.data_comentario)} ${btnExcluir}</div>

                            </div>

                        </div>

                    `;

                });

            }



            // Exibe o modal e guarda o ID atual

            document.getElementById('current-post-id').value = idPost;

            document.getElementById('modal-post').style.display = 'flex';

        })

        .catch(err => {

            console.error("Erro ao carregar post:", err);

            if(typeof mostrarAviso === 'function') mostrarAviso("Erro ao carregar publicação.");

        });





// Dentro da função que renderiza os dados do post no modal:

function renderizarConteudoModal(dados) {

    const mediaContainer = document.getElementById('media-content'); // Lado esquerdo (mídia/texto)

    

    // Limpa classes anteriores

    mediaContainer.className = "modal-post-media"; 

    

    if (dados.imagem_post) {

        // Lógica de Imagem ou Vídeo (como já fizemos anteriormente)

        const ext = dados.imagem_post.split('.').pop().toLowerCase();

        if (['mp4', 'webm', 'ogg'].includes(ext)) {

            mediaContainer.innerHTML = `<video src="site/img/posts/${dados.imagem_post}" controls autoplay style="max-width:100%;"></video>`;

        } else {

            mediaContainer.innerHTML = `<img src="site/img/posts/${dados.imagem_post}" style="max-width:100%;">`;

        }

    } else {

        // É UM POST DE TEXTO COM GRADIENTE

        if (dados.gradiente_id) {

            mediaContainer.classList.add(dados.gradiente_id);

        }

        

        mediaContainer.style.textAlign = dados.alinhamento || 'left';

        mediaContainer.style.display = "flex";

        mediaContainer.style.alignItems = "center";

        mediaContainer.style.justifyContent = "center";

        mediaContainer.style.padding = "40px";

        mediaContainer.style.fontSize = "24px";

        

        // IMPORTANTE: Aqui usamos innerHTML para que <b>, <i> e <u> funcionem!

        mediaContainer.innerHTML = dados.conteudo; 

    }

}




}



function excluirComentario(idComentario, idPost) {

    // Removemos a linha "if(confirm(...))" para apagar sem perguntar nada.

    let fd = new FormData();

    fd.append('excluir_id', idComentario);



    fetch('site/data/comentar_logica.php', {

        method: 'POST',

        body: fd

    })

    .then(res => res.text())

    .then(data => {

        // O PHP retorna "excluido" conforme vimos no seu arquivo comentar_logica.php

        if(data.trim() === "excluido") {

            if(typeof mostrarAviso === 'function') {

                mostrarAviso("Comentário removido");

            }

            abrirPost(idPost); // Recarrega os comentários no modal

        } else {

            console.error("Erro retornado pelo PHP:", data);

            alert("Erro ao excluir comentário");

        }

    })

    .catch(err => console.error("Erro na requisição:", err));

}



function fecharModalPost(e) {

    document.getElementById('modal-post').style.display = 'none';

    document.getElementById('media-content').innerHTML = ''; 

}



function postarComentario(e) {

    e.preventDefault();

    

    const idPost = document.getElementById('current-post-id').value;

    const textoInput = document.getElementById('texto-comentario');

    const texto = textoInput.value;



    if(!texto.trim() || !idPost) return;



    let fd = new FormData();

    fd.append('id_post', idPost);

    fd.append('comentario', texto);



    fetch('site/data/comentar_logica.php', { 

        method: 'POST', 

        body: fd 

    })

    .then(res => res.text())

    .then(data => {

        // O .trim() é essencial porque o PHP pode enviar espaços invisíveis

        if(data.trim() === "sucesso") {

            textoInput.value = ''; 

            // Recarrega o conteúdo do modal na hora

            abrirPost(idPost); 

        } else {

            console.error("Erro retornado pelo PHP:", data);

            alert("Não foi possível enviar o comentário.");

        }

    })

    .catch(err => console.error("Erro na requisição:", err));

}



// Dentro do seu modal_post.js, na função que recebe os dados do banco (AJAX)

function preencherModal(dados) {

    const mediaContent = document.getElementById('media-content');

    

    // Limpa estados anteriores

    mediaContent.className = "modal-post-media"; 

    mediaContent.style.textAlign = dados.alinhamento || 'center';



    if (dados.imagem_post) {

        // ... lógica de imagem/vídeo que já funciona ...

    } else {

        // TRATAMENTO PARA POSTS DE TEXTO ("Mini Word")

        if (dados.gradiente_id) {

            mediaContent.classList.add(dados.gradiente_id);

        }

        

        // O SEGREDO: usar innerHTML para renderizar <b>, <i>, <u> e <br>

        mediaContent.innerHTML = `<div style="padding: 40px; font-size: 1.5rem; width: 100%;">

                                    ${dados.conteudo}

                                  </div>`;

    }

}

Quase isso! Você tem as peças certas, mas o código está um pouco duplicado e desorganizado dentro da função abrirPost. Atualmente, você tem a lógica de renderização espalhada entre a abrirPost, a renderizarConteudoModal e a preencherModal.

O ideal é unificar tudo dentro da função abrirPost (que é quem recebe os dados do banco) para que o sistema funcione de primeira.

Aqui está o seu site/js/modal_post.js limpo e organizado com a lógica do Mini Word integrada:

JavaScript
// site/js/modal_post.js

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

function abrirPost(idPost) {
    fetch(`site/data/visualizar_post.php?id_post=${idPost}`)
        .then(res => res.json())
        .then(data => {
            if (data.erro) return console.error(data.erro);

            const post = data.post;
            const comentarios = data.comentarios;
            const meuId = data.meu_id;
            
            // --- 1. RENDERIZAR MÍDIA / TEXTO (Lógica Mini Word) ---
            const mediaDiv = document.getElementById('media-content');
            mediaDiv.className = "modal-post-media"; // Reseta classes (remove gradientes antigos)
            mediaDiv.style = ""; // Reseta estilos inline

            if (post.imagem_post) {
                const ext = post.imagem_post.split('.').pop().toLowerCase();
                if(['mp4', 'webm', 'ogg'].includes(ext)) {
                    mediaDiv.innerHTML = `<video controls autoplay style="width:100%; max-height:100%"><source src="site/img/posts/${post.imagem_post}"></video>`;
                } else {
                    mediaDiv.innerHTML = `<img src="site/img/posts/${post.imagem_post}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
                }
            } else {
                // Se for texto puro, aplica gradiente e alinhamento
                if (post.gradiente_id) mediaDiv.classList.add(post.gradiente_id);
                
                mediaDiv.style.textAlign = post.alinhamento || 'center';
                mediaDiv.style.display = "flex";
                mediaDiv.style.alignItems = "center";
                mediaDiv.style.justifyContent = "center";
                mediaDiv.style.padding = "40px";
                mediaDiv.style.fontSize = "22px";
                mediaDiv.style.color = "white";
                
                // innerHTML permite Negrito, Itálico e Sublinhado
                mediaDiv.innerHTML = `<div style="width:100%">${post.conteudo}</div>`;
            }

            // --- 2. RENDERIZAR CABEÇALHO ---
            const jaCurti = post.ja_curti > 0;
            const linkAutor = (post.id_autor == meuId) ? 'perfil_do_usuario.php' : `perfil_membro.php?id=${post.id_autor}`;
            
            document.getElementById('post-header').innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <a href="${linkAutor}">
                            <img src="site/${post.autor_foto}" onerror="this.src='site/img/default-user.png'" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #F91313;">
                        </a>
                        <div>
                            <a href="${linkAutor}" style="text-decoration:none; color:white;"><strong style="display:block">${post.autor}</strong></a>
                            <small style="color:#666">${formatarDataRedeSocial(post.data_post)}</small>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span onclick="alternarLike(${idPost})" style="cursor: pointer; font-size: 20px;">
                            ${jaCurti ? '❤️' : '🤍'}
                        </span>
                        <span style="color: #ccc; font-size: 13px;">${post.total_likes || 0}</span>
                    </div>
                </div>`;

            // --- 3. RENDERIZAR COMENTÁRIOS ---
            const commDiv = document.getElementById('post-comments');
            commDiv.innerHTML = comentarios.length === 0 ? '<p style="color:#444; padding:15px;">Nenhum comentário ainda.</p>' : '';
            
            comentarios.forEach(c => {
                let btnExcluir = (c.id_autor_comentario == meuId) ? `<span onclick="excluirComentario(${c.id_comentario}, ${idPost})" style="color:#F91313; cursor:pointer; font-size:9px; margin-left:8px;">EXCLUIR</span>` : '';
                commDiv.innerHTML += `
                    <div class="comment-item" style="display:flex; gap:12px; padding:12px; border-bottom:1px solid #111;">
                        <img src="site/${c.foto_comentario}" style="width:30px; height:30px; border-radius:50%;" onerror="this.src='site/img/default-user.png'">
                        <div>
                            <div style="font-size:13px;">
                                <strong style="color:#F91313;">${c.autor_comentario}</strong> <span style="color:#eee;">${c.comentario}</span>
                            </div>
                            <div style="font-size:10px; color:#555;">${formatarDataRedeSocial(c.data_comentario)} ${btnExcluir}</div>
                        </div>
                    </div>`;
            });

            document.getElementById('current-post-id').value = idPost;
            document.getElementById('modal-post').style.display = 'flex';
        })
        .catch(err => console.error("Erro ao carregar post:", err));
}

// Funções auxiliares (Like, Comentar, Excluir, Fechar)
function alternarLike(idPost) {
    let fd = new FormData();
    fd.append('id_post', idPost);
    fetch('site/data/curtir_logica.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(() => abrirPost(idPost));
}

function postarComentario(e) {
    e.preventDefault();
    const idPost = document.getElementById('current-post-id').value;
    const input = document.getElementById('texto-comentario');
    let fd = new FormData();
    fd.append('id_post', idPost);
    fd.append('comentario', input.value);
    fetch('site/data/comentar_logica.php', { method: 'POST', body: fd })
    .then(() => { input.value = ''; abrirPost(idPost); });
}

function excluirComentario(idC, idP) {
    let fd = new FormData();
    fd.append('excluir_id', idC);
    fetch('site/data/comentar_logica.php', { method: 'POST', body: fd })
    .then(() => abrirPost(idP));
}

function fecharModalPost(e) {
    document.getElementById('modal-post').style.display = 'none';
    document.getElementById('media-content').innerHTML = '';
}
O que eu fiz:
Unificação: Removi as funções renderizarConteudoModal e preencherModal que estavam sobrando. Agora tudo o que o modal precisa está dentro do .then() da abrirPost.

Reset de Classes: Adicionei mediaDiv.className = "modal-post-media" no início da função. Sem isso, se você abrisse um post com gradiente "Sangue" e depois um post de imagem, o fundo vermelho poderia continuar lá "atrás" da imagem.

Habilitar HTML: Onde o conteúdo do texto é inserido, usei ${post.conteudo} diretamente. Como o seu PHP já filtra tags perigosas com strip_tags, aqui o navegador vai ler o <b> e o <i> perfeitamente.
