import subprocess
import time
import os

# Caminho atualizado para a nova pasta
BASE_DIR = r"C:\xampp\htdocs\Sistema-login\dashboard\tunneling\pinggy_panel"
STATUS_FILE = os.path.join(BASE_DIR, "status.txt")
STATUS_RASTROS = os.path.join(BASE_DIR, "status_rastros.txt")
LOG_RASTROS = os.path.join(BASE_DIR, "logs", "rastros_live.log")

def check_system():
    if not os.path.exists(os.path.dirname(LOG_RASTROS)):
        os.makedirs(os.path.dirname(LOG_RASTROS))
    
    while True:
        status_ssh = "OFFLINE"
        try:
            output = subprocess.check_output('tasklist /FI "IMAGENAME eq ssh.exe"', shell=True).decode('cp850')
            if "ssh.exe" in output:
                status_ssh = "ONLINE"
        except:
            pass
        
        with open(STATUS_FILE, "w") as f: f.write(status_ssh)
        with open(STATUS_RASTROS, "w") as f: f.write("ONLINE")
        
        with open(LOG_RASTROS, "a", encoding="utf-8") as f:
            f.write(f"[{time.strftime('%H:%M:%S')}] Rastro Ativo | Túnel: {status_ssh}\n")
            f.flush()
        
        time.sleep(3)

if __name__ == "__main__":
    check_system()