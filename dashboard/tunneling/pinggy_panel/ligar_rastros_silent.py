import subprocess
import os

PYTHON_BIN = r"C:\Users\lorde\AppData\Local\Programs\Python\Python313\python.exe"
BASE_DIR   = r"C:\xampp\htdocs\Sistema-login\dashboard\tunneling"
MONITOR_PY = os.path.join(BASE_DIR, "monitor_tunnel.py")
LOG_FILE   = os.path.join(BASE_DIR, "logs", "rastros_live.log")

def start_rastros():
    os.makedirs(os.path.dirname(LOG_FILE), exist_ok=True)
    # Cria o log imediatamente com cabeçalho
    with open(LOG_FILE, "w", encoding="utf-8") as f:
        f.write("--- SISTEMA BROTHERHOOD: MOTOR ATIVADO ---\n")
    
    # Inicia o monitor em background (Sem janela)
    subprocess.Popen([PYTHON_BIN, MONITOR_PY], creationflags=subprocess.CREATE_NO_WINDOW)

if __name__ == "__main__":
    start_rastros()