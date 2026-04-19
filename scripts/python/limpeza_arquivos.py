import os
import mysql.connector
import sys
import io

# Força a saída do console a usar UTF-8
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Configurações de pastas baseadas no seu print
PASTA_IMG_RAIZ = r'C:/xampp/htdocs/Sistema-login/site/img/perfis/'
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'projeto_login'
}

# Lista de arquivos que NUNCA devem ser deletados (Sistema)
PROTEGIDOS = [
    'nome_d0_arquivo.png',
]

def faxina_korvado():
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()
        
        # Pega todas as fotos de perfil registradas no banco
        cursor.execute("SELECT foto FROM usuarios WHERE foto IS NOT NULL")
        # Limpamos o caminho para pegar apenas o nome do arquivo
        fotos_ativas = {os.path.basename(linha[0]) for linha in cursor.fetchall()}
        
        print("--- Iniciando Varredura de Fotos Órfãs ---")
        
        # Varre a pasta site/img/
        for arquivo in os.listdir(PASTA_IMG_RAIZ):
            caminho_completo = os.path.join(PASTA_IMG_RAIZ, arquivo)
            
            # Pula se for diretório (como 'avatar', 'icons', 'perfil', 'posts')
            if os.path.isdir(caminho_completo):
                continue
                
            # Pula se for arquivo de sistema protegido
            if arquivo in PROTEGIDOS or arquivo.endswith('.zip'):
                continue
            
            # Se for uma imagem (jpg, png, gif) e NÃO estiver no banco: DELETA
            if arquivo.lower().endswith(('.jpg', '.jpeg', '.png', '.gif')):
                if arquivo not in fotos_ativas:
                    try:
                        os.remove(caminho_completo)
                        print(f"🗑️ Removido: {arquivo} (Sem vínculo no banco)")
                    except Exception as e:
                        print(f"⚠️ Erro ao apagar {arquivo}: {e}")

        # O UPDATE deve ficar DENTRO do try, antes de fechar a conexão
        cursor.execute("UPDATE configuracoes_sistema SET valor = NOW() WHERE chave = 'ultima_limpeza'")
        conn.commit()
        
        conn.close()
    except Exception as e:
        print(f"🚨 Erro: {e}")

if __name__ == "__main__":
    faxina_korvado()