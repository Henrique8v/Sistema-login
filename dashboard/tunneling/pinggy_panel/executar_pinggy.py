import subprocess, re, json, os, time

# Caminho atualizado para a nova pasta
BASE_DIR    = r"C:\xampp\htdocs\Sistema-login\dashboard\tunneling\pinggy_panel"
CONFIG_FILE = os.path.join(BASE_DIR, "config.json")
LOG_FILE    = os.path.join(BASE_DIR, "logs", "pinggy_live.log")
SENHA_SSH   = "123@#56aA!"

def run_tunnel():
    if not os.path.exists(os.path.dirname(LOG_FILE)):
        os.makedirs(os.path.dirname(LOG_FILE))
    
    with open(LOG_FILE, "w", encoding="utf-8") as f:
        f.write("--- INICIANDO CONEXÃO PINGGY ---\n")

    cmd = f'echo {SENHA_SSH}| ssh -p 443 -R0:127.0.0.1:8080 -o StrictHostKeyChecking=no jc4eUYKAA0C@br.free.pinggy.io'
    
    processo = subprocess.Popen(
        cmd, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, 
        text=True, encoding='utf-8', errors='ignore', bufsize=1
    )

    url_encontrada = False
    while True:
        linha = processo.stdout.readline()
        if not linha and processo.poll() is not None: break
        
        if linha:
            with open(LOG_FILE, "a", encoding="utf-8") as f_log:
                f_log.write(linha)
                f_log.flush()
            
            match = re.search(r'(https://[a-zA-Z0-9.-]+\.a\.free\.pinggy\.link)', linha)
            if match and not url_encontrada:
                url_base = match.group(1).rstrip('/')
                dados_config = {
                    "link_login": f"{url_base}/Sistema-login/index.php",
                    "link_perfil": f"{url_base}/Sistema-login/site/public/index.php"
                }
                with open(CONFIG_FILE, "w") as f_js:
                    json.dump(dados_config, f_js)
                url_encontrada = True

if __name__ == "__main__":
    run_tunnel()