Como você está usando python para compilar o cpp em milissegundos com o code::blocks sem usar diretamente o code::blocks:

1. Localize o g++.exe

Normalmente, o rastro no Code::Blocks é este:
C:\Program Files\CodeBlocks\MinGW\bin\g++.exe
(Ou C:\Program Files (x86)\CodeBlocks\MinGW\bin\g++.exe se for uma versão mais antiga).

Geralmente: C:\Program Files\CodeBlocks\MinGW\bin\g++.exe

2. Faça o path

Com o rastro mapeado e com o caminho padrão da instalação completa do Code::Blocks o segredo para o Python não se perder é o uso correto das aspas duplas no comando shell: gpp_path = r'C:\Program Files\CodeBlocks\MinGW\bin\g++.exe'

---------------------------------------------------------------------------
Erros comuns: libs

Falata de "libstdc++-6.dll" e "libgcc_s_seh-1.dll"

Erros de "libstdc++-6.dll" e "libgcc_s_seh-1.dll" são super comuns quando compilamos algo no Windows. O que está acontecendo é o seguinte: o g++ do Code::Blocks compilou o seu analisador.exe, mas o Windows tenta rodar ele e não encontra as "bibliotecas de suporte" (as DLLs) que o compilador usa. É como se você tivesse o carro, mas as rodas ficaram guardadas na garagem do Code::Blocks.

o g++ do Code::Blocks compilou o seu analisador.exe, mas o Windows tenta rodar ele e não encontra as "bibliotecas de suporte" (as DLLs) que o compilador usa. É como se você tivesse o carro, mas as rodas ficaram guardadas na garagem do Code::Blocks.

Vamos resolver isso da forma mais "Brotherhood" possível: Estática. Vamos forçar o compilador a colocar as rodas dentro do carro.

A Solução: Linkagem Estática
No seu script Python executar_cpp.py, vamos adicionar as flags -static-libgcc e -static-libstdc++. Isso diz ao compilador: "Ei, pegue todo o código das DLLs e coloque direto dentro do .exe".

Atualize a linha do comando no executar_cpp.py:
Python

# Mude a linha do 'cmd' para esta:
cmd = f'"{gpp_path}" "{source}" -static-libgcc -static-libstdc++ -o "{output}" && "{output}"'
Por que isso resolve?
Independência: O seu analisador.exe vai ficar um pouquinho maior em KB, mas ele vai rodar em qualquer lugar sem pedir DLL nenhuma.

Fim dos Pop-ups: Aquelas janelas de erro chatas do Windows vão sumir de vez.

Robustez: É o jeito certo de criar ferramentas de sistema que precisam rodar de forma isolada.

---------------------------------------------------------------------------
Erros comuns: 0xc000007b.

O rastro complicou um pouco, mas esse erro 0xc000007b é um sinal clássico de conflito entre 32 bits e 64 bits. Basicamente, o seu Python (64-bit) está tentando rodar um .exe que foi compilado ou linkado com bibliotecas de 32 bits (ou vice-versa), criando essa confusão no coração do Windows.

Para resolver isso e fazer o terminal brilhar em dourado sem pop-ups de erro, vamos usar a linkagem estática total. Isso vai incluir absolutamente tudo o que o programa precisa dentro do .exe, ignorando as DLLs problemáticas do sistema.

O Ajuste Mestre no executar_cpp.py
Substitua a linha do comando cmd por esta versão "tanque de guerra". Note que adicionei a flag -static (que é o golpe final contra dependências de DLL):

Python

# No seu executar_cpp.py, mude o comando para:
cmd = f'"{gpp_path}" "{source}" -static -static-libgcc -static-libstdc++ -o "{output}" && "{output}"'
Por que isso deve matar o erro:
-static: Esta flag diz ao compilador: "Não use nenhuma DLL externa. Se precisar de algo, coloque dentro do binário".

Fim do conflito 32/64: Ao embutir as bibliotecas, o Windows para de buscar versões erradas nas pastas do sistema, resolvendo o erro 0xc000007b.




