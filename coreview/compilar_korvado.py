import os
import subprocess

# Caminho da pasta do Electron
BASE_DIR = r"C:\xampp\htdocs\Sistema-login\coreview\core_electron"
# CAMINHO ABSOLUTO DO NPM (Ajuste se o seu for diferente de Program Files)
NPM_PATH = r"C:\Program Files\nodejs\npm.cmd"

def forjar_estacao_electron():
    print("--- INICIANDO FORJA KORVADO (ELECTRON) ---")
    
    if not os.path.exists(BASE_DIR):
        print(f"[!] ERRO: Pasta não encontrada: {BASE_DIR}")
        return

    try:
        print("[+] Disparando motor Chromium via NPM Absoluto...")
        # Usamos o caminho completo do NPM para o PHP não se perder
        subprocess.Popen([NPM_PATH, "start"], cwd=BASE_DIR, shell=True)
        print("--- OPERACAO CONCLUIDA COM SUCESSO ---")
    except Exception as e:
        print(f"[!] Falha crítica: {e}")

if __name__ == "__main__":
    forjar_estacao_electron()