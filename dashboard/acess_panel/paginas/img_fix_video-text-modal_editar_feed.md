Para corrigir o problema onde vídeos são tratados como imagens e textos puros não aparecem corretamente no grid, precisamos adicionar uma verificação de extensão de arquivo.

Atualmente, o seu código assume que se existe algo em $post['imagem_post'], é uma imagem (<img>). Vamos mudar essa lógica tanto no grid (as miniaturas) quanto no JavaScript (que abre o modal).

1. No PHP: Ajuste do Grid (editar_feed.php)
Substitua o bloco que exibe a mídia no loop while por este, que identifica vídeos:

PHP

<div class="grid-feed">
    <?php
    $sql = "SELECT * FROM timeline WHERE usuario_id = :id ORDER BY data_post DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_logado]);
    while($post = $stmt->fetch(PDO::FETCH_ASSOC)):
        $midia = $post['imagem_post'];
        $grad = $post['gradiente_id'];
        $classeGrad = (!$midia && $grad) ? $grad : '';
        
        // Lógica para detectar se é vídeo
        $extensao = $midia ? strtolower(pathinfo($midia, PATHINFO_EXTENSION)) : '';
        $e_video = in_array($extensao, ['mp4', 'webm', 'ogg']);
    ?>
        <div class="post-card <?= $classeGrad ?>" onclick="abrirPost(<?= $post['id_post'] ?>)">
            <div class="btn-controls">
                <button type="button" class="btn-mini btn-abrir-editor" 
                        data-id="<?= $post['id_post'] ?>" 
                        data-texto="<?= htmlspecialchars($post['conteudo'], ENT_QUOTES, 'UTF-8') ?>" 
                        data-img="<?= $midia ?>" 
                        data-grad="<?= $grad ?>"
                        onclick="event.stopPropagation();"> EDITAR
                </button>
                <a href="editar_feed.php?del=<?= $post['id_post'] ?>" class="btn-mini" style="color: #ff4d4d;" onclick="event.stopPropagation(); return confirm('Excluir?')">X</a>
            </div>

            <?php if($midia): ?>
                <?php if($e_video): ?>
                    <video src="site/img/posts/<?= $midia ?>" style="width:100%; height:100%; object-fit:cover;" muted></video>
                    <div style="position:absolute; bottom:5px; right:5px; color:white; text-shadow:1px 1px 2px black;">▶</div>
                <?php else: ?>
                    <img src="site/img/posts/<?= $midia ?>" style="width:100%; height:100%; object-fit:cover;">
                <?php endif; ?>
            <?php else: ?>
                <div style="padding: 15px; text-align: center; font-size: 12px; height:100%; display:flex; align-items:center; justify-content:center;">
                    <?= mb_strimwidth(strip_tags($post['conteudo']), 0, 100, "...") ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
2. No JavaScript: Ajuste do Modal de Edição (editar_feed.js)
Você deve atualizar a função abrirEdicao para que o preview do lado esquerdo mostre o vídeo ou o gradiente corretamente.

JavaScript

function abrirEdicao(id, texto, alinhamento, gradiente, midiaPath) {
    const modal = document.getElementById('modalEdit');
    const area = document.getElementById('edit-texto');
    const preview = document.getElementById('edit-preview');
    
    document.getElementById('edit-id').value = id;
    area.value = texto;
    
    // Alinhamento e Gradiente na área de texto
    area.style.textAlign = alinhamento || 'left';
    document.getElementById('alinhamento_edit').value = alinhamento || 'left';
    area.className = 'main-textarea ' + (gradiente || '');
    document.getElementById('gradiente_edit').value = gradiente || '';

    // LÓGICA DE PREVIEW DE MÍDIA
    if (midiaPath) {
        const ext = midiaPath.split('.').pop().toLowerCase();
        const videoExts = ['mp4', 'webm', 'ogg'];

        if (videoExts.includes(ext)) {
            preview.innerHTML = `<video src="site/img/posts/${midiaPath}" controls style="max-width:100%; max-height:100%;"></video>`;
        } else {
            preview.innerHTML = `<img src="site/img/posts/${midiaPath}" style="max-width:100%; max-height:100%; object-fit:contain;">`;
        }
    } else {
        // Se não tem mídia, o preview mostra o gradiente (opcional)
        preview.innerHTML = `<div class="${gradiente}" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#555; font-size:12px;">Postagem apenas de texto</div>`;
    }
    
    modal.style.display = 'flex';
}
3. No JavaScript: Ajuste do modal_post.js (Onde você clica para abrir o post)
Para que o abrirPost (que carrega comentários e likes) também entenda vídeos, verifique se no seu fetch que busca os dados do post, você está injetando a tag <video> caso a extensão coincida.

O que corrigimos:
Grid: Agora o PHP olha a extensão do arquivo. Se for mp4, ele usa a tag <video> em vez de <img>.

Edit Modal: O preview agora é inteligente. Ele "saca" se você está editando um vídeo ou uma imagem.

Texto Puro: Posts sem mídia agora centralizam o texto no card do grid para não ficar visualmente quebrado.