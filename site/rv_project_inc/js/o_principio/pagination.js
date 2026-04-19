//pagination.js

// como o seletor de páginas (paginação) geralmente fica antes do footer e logo após o conteúdo principal, 
// você não deve usar o insertAdjacentHTML no body diretamente.
// Se você injetar no final do body com beforeend, ele vai ficar grudado embaixo do footer. 
// O segredo é criar um contêiner alvo (um placeholder) no seu HTML principal para determinar exatamente onde ele deve "quebrar" com o conteúdo.

// pagination.js
const paginationContent = `
<div style="display: flex; justify-content: center; align-items: center; position: relative; margin: 20px auto; border: 0px solid white; width: 100%; max-width: 500px; font-family: trebuchet, sans-serif;">
    <a href="#" style="margin: 0px 8px; text-decoration: none;"><font color="red"> anterior </font></a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 2 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 3 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 4 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 5 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 6 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 7 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 8 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none; color: white;"> 9 </a>
    <a href="#" style="margin: 0px 8px; text-decoration: none;"><font color="red"> seguinte </font></a>
</div>
`;

// Injeta o conteúdo dentro do elemento que tem o ID 'pagination-container'
const target = document.getElementById('pagination-container');
if (target) {
    target.innerHTML = paginationContent;
}