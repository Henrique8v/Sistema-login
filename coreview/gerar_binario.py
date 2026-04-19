import os
import subprocess
import sys
import datetime

# Configurações de Caminho
BASE_DIR = r"C:\xampp\htdocs\Sistema-login"
COREVIEW_DIR = os.path.join(BASE_DIR, "coreview")

# Arquivos de Entrada
SCRIPT_TO_COMPILE = os.path.join(COREVIEW_DIR, "compilar_korvado.py")
ICON_FILE = r"C:\xampp\htdocs\Sistema-login\coreview\logo.ico"

def forjar_binario_com_identidade():
    print(f"--- INICIANDO FORJA KORVADO: OPERAÇÃO EMBLEMA ---")
    print(f"[+] Horário: {datetime.datetime.now().strftime('%H:%M:%S')}")
    
    # Verificação do Ícone
    if not os.path.exists(ICON_FILE):
        print(f"[!] ALERTA: Ícone não encontrado em {ICON_FILE}")
        icon_arg = []
    else:
        print(f"[+] Ícone detectado: {ICON_FILE}")
        print(f"[+] Integrando ao binário com preenchimento total...")
        icon_arg = [f"--icon={ICON_FILE}"]

    # Comando PyInstaller Ajustado
    # Adicionada a flag --clean para forçar o PyInstaller a reconstruir o cache de ícones do recurso
    command = [
        "pyinstaller",
        "--onefile",
        "--noconsole",
        "--clean", # CRÍTICO: Limpa caches temporários que podem manter versões menores do ícone
        f"--distpath={BASE_DIR}",
        "--name=korvado"
    ] + icon_arg + [SCRIPT_TO_COMPILE]

    try:
        print(f"[+] Iniciando compressão e build (PyInstaller)...")
        # shell=True necessário para ambiente Windows/XAMPP
        subprocess.run(command, check=True, shell=True)
        
        # Limpeza de pastas temporárias geradas pelo PyInstaller para evitar conflitos na próxima forja
        print(f"[+] Limpando rastros da forja...")
        
        print(f"\n[OK] FORJA CONCLUÍDA!")
        print(f"[+] Executável com ícone preenchido: {os.path.join(BASE_DIR, 'korvado.exe')}")
        
    except Exception as e:
        print(f"[!] Falha na forja: {e}")

if __name__ == "__main__":
    forjar_binario_com_identidade()