// footer.js

// Detecta a base da URL automaticamente
const baseUrlFooter = window.location.origin + "/Sistema-login"; 

const footerContent = `
<footer id="footer-main" style="margin-top: 50px; width: 100%;">
    <link rel="stylesheet" href="${baseUrlFooter}/site/css/footer.css">
    <div id="footer-box">
        <p id="footer-header">
            <span id="footer-area">
                <a href="#" class="footer-area-text">Advertências</a>
                <a href="#" class="footer-area-text">Termos</a>
                <a href="#" class="footer-area-text">Politica de Privacidade</a>
                <a href="#" class="footer-area-text">Copyright</a>
                <a href="#" class="footer-area-text">Créditos</a>
                <a href="#" class="footer-area-text">Abuso</a>
                <a href="#" class="footer-area-text">Mais...</a>
            </span>
        </p>
        <p class="copyright">
            © 2026 RV Project - Brotherhood System
        </p>
    </div>
</footer>
`;

document.body.insertAdjacentHTML('beforeend', footerContent);