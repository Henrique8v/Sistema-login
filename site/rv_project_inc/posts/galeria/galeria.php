<?php 
require_once '../../../data/user.php';      
require_once '../../../data/user_menu.php'; 
?>
    
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../../img/logo.png">
    <title>Galeria RV</title>
    
    <link rel="stylesheet" href="../../../css/template.css">
    <link rel="stylesheet" href="../../../css/menu.css">
    <link rel="stylesheet" href="../../../css/search.css">
    <link rel="stylesheet" href="../../css/galeria/galeria.css">
</head>
<body>

    <table border="0" align="center" cellspacing="0" cellpadding="0" class="tableWidth" style="margin-top: 60px;">
        <tr>
            <td id="subTitle">G A L E R I A</td>
        </tr>
    </table>

    <table border="0" align="center" cellspacing="0" cellpadding="0" class="tableWidth">
        <tr class="blak">
            <td id="pageContent" style="padding: 20px;">
                
                <div id="gallery" class="gallery-grid"></div>
                
                <div id="pagination" class="pagination" style="margin-top: 30px;"></div>
                
                <div style="margin-top: 50px; text-align: right;">
                    <i style="color: #666;">Acervo visual do <font color="red">RV Project</font></i>
                </div>
            </td>
        </tr>
    </table>

    <div id="modal" onclick="closeModal()" style="display:none;">
        <span class="close-btn">&times;</span>
        <img id="modal-content" src="" alt="Preview" onclick="openFullImage(event)">
    </div>

    <script src="../../../js/imagens_da_galeria.js"></script>
    <script>
        const basePath = "../../img/galeria/";
        const postsPerPage = 6;
        let currentPage = 1;

        function renderGallery() {
            const container = document.getElementById('gallery');
            container.innerHTML = "";
            
            const start = (currentPage - 1) * postsPerPage;
            const end = start + postsPerPage;
            const pageItems = imageFiles.slice(start, end);

            pageItems.forEach(file => {
                const card = document.createElement('div');
                card.className = 'post-card';
                card.onclick = () => openModal(`${basePath}${file}`);
                card.innerHTML = `<img src="${basePath}${file}" alt="Imagem" loading="lazy" style="border: 1px solid #333; border-radius: 5px;">`;
                container.appendChild(card);
            });

            renderPagination();
        }

        function renderPagination() {
            const nav = document.getElementById('pagination');
            nav.innerHTML = "";
            const totalPages = Math.ceil(imageFiles.length / postsPerPage);
            if(totalPages <= 1) return;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                // Aplica estilo de botão neon simplificado para paginação
                btn.style.cssText = "margin: 5px; padding: 5px 12px; cursor: pointer; background: transparent; border: 1px solid red; color: white;";
                if (i === currentPage) {
                    btn.style.background = "red";
                }
                btn.onclick = () => { 
                    currentPage = i; 
                    renderGallery(); 
                    window.scrollTo({top: 0, behavior: 'smooth'});
                };
                nav.appendChild(btn);
            }
        }

        function openModal(src) {
            const modal = document.getElementById('modal');
            const modalImg = document.getElementById('modal-content');
            modal.style.display = "flex";
            modalImg.src = src;
        }

        function closeModal() {
            document.getElementById('modal').style.display = "none";
        }

        function openFullImage(event) {
            event.stopPropagation();
            const imgSrc = document.getElementById('modal-content').src;
            const fileName = imgSrc.split('/').pop();
            
            const newTab = window.open("");
            newTab.document.write(`
                <html>
                <title>Download - ${fileName}</title>
                <body style="background: #000; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; overflow: hidden;">
                    <div style="position: relative;">
                        <a href="${imgSrc}" download="${fileName}" style="position: absolute; top: 15px; right: 15px; background: rgba(0, 0, 0, 0.7); border: 1px solid red; padding: 10px; border-radius: 50%; z-index: 100;">
                            <img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" style="width: 24px; height: 24px; filter: invert(1);">
                        </a>
                        <img src="${imgSrc}" style="max-width: 95vw; max-height: 95vh; border: 1px solid #333;">
                    </div>
                </body>
                </html>
            `);
            newTab.document.close();
        }

        renderGallery();
    </script>

    <script src="../../../js/header.js"></script>
    <script src="../../../js/footer.js"></script>
    <script src="../../../js/search.js"></script>

</body>
</html>