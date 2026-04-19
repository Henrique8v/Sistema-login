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
				   
            <?php endforeach; else: ?>