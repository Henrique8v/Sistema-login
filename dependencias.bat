echo [1/4] Atualizando o Gerenciador PIP...
python -m pip install --upgrade pip

echo [2/4] Instalando bibliotecas base (Requirements)...
python -m pip install -r requirements.txt

echo [3/4] Recurso de Conexão
python -m pip install mysql-connector-python

echo [4/4] Instalando Motor WebView2 Chromium (pywebview + .NET)...
python -m pip install pywebview pythonnet bottle proxy_tools typing_extensions

echo: Nota: o restante da "mágica" do seu projeto depende da organização das pastas e da presença do compilador C++ e do cliente SSH no seu Windows.

python -m pip install pygame 2.6.1
python -m pip install pyinstaller


