@echo off
echo Iniciando a listagem...
:: Lista arquivos e pastas (/s), formato simples (/b) e ordena por nome
dir /s /b /on > "lista_conteudo.txt"
echo Concluido. O arquivo lista_conteudo.txt foi gerado.
pause
