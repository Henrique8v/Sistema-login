import os
import time

# Caminhos Absolutos e Seguros
PASTA_LOGS = r"C:\xampp\htdocs\Sistema-login\logs"

def protocolo_limpeza():
    print(f"[{time.strftime('%Y-%m-%d %H:%M:%S')}] Iniciando varredura em Sistema-login/logs/...")
    
    if not os.path.exists(PASTA_LOGS):
        print("[!] Erro: Pasta de logs não encontrada. Abortando.")
        return

    arquivos = os.listdir(PASTA_LOGS)
    removidos = 0
    agora = time.time()

    for arquivo in arquivos:
        caminho = os.path.join(PASTA_LOGS, arquivo)
        # Remove ficheiros com mais de 24 horas (86400 segundos)
        if os.stat(caminho).st_mtime < agora - 86400:
            try:
                os.remove(caminho)
                removidos += 1
            except Exception as e:
                print(f"Erro ao remover {arquivo}: {e}")

    print(f"[+] Limpeza concluída. Ficheiros removidos: {removidos}")

if __name__ == "__main__":
    protocolo_limpeza()