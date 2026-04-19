import subprocess
import time
import os

BASE_DIR    = r"C:\xampp\htdocs\Sistema-login\dashboard\tunneling"
STATUS_FILE = os.path.join(BASE_DIR, "status.txt")
LOG_RASTROS = os.path.join(BASE_DIR, "logs", "rastros_live.log")

def check_tunnel():
    os.makedirs(os.path.dirname(LOG_RASTROS), exist_ok=True)
    while True:
        status = "OFFLINE"
        try:
            output = subprocess.check_output('tasklist', shell=True).decode('cp850')
            if "ssh.exe" in output.lower():
                status = "ONLINE"
        except:
            pass
        
        with open(STATUS_FILE, "w") as f:
            f.write(status)
            
        with open(LOG_RASTROS, "a", encoding="utf-8") as f:
            f.write(f"[{time.strftime('%H:%M:%S')}] Rastro: {status}\n")
            f.flush()
        
        time.sleep(4)

if __name__ == "__main__":
    check_tunnel()