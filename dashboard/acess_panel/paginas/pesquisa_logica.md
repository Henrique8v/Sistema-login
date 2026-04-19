O arquivo pesquisa_logica.php foi desenhado para ser um "endpoint" de API. Ou seja, ele deve ser chamado apenas pelo JavaScript (fetch) quando o usuário digita algo.

Se você o coloca no topo da página via PHP:

Execução Precoce: Ele vai tentar rodar a busca assim que a página carregar, mas sem o termo q, ele não fará nada ou pode gerar avisos de variável indefinida.

Saída de Dados: Se por acaso ele encontrar algo, ele vai "cuspir" o HTML dos resultados no topo do seu site, antes mesmo do <html>, quebrando o seu layout.

✅ O jeito certo de integrar
Para que a ferramenta de busca apareça em todas as páginas (como o_principio.php, galeria.php, etc.), você só precisa garantir que:

O CSS esteja no <head>:

HTML

<link href="../../../css/search.css" rel="stylesheet" />
O JS esteja no final do <body>:

HTML

<script src="../../../js/header.js"></script>
<script src="../../../js/search.js"></script>
O search.js vai se encarregar de chamar o pesquisa_logica.php nos bastidores via rede (AJAX/Fetch), sem precisar dar require no PHP da página principal.