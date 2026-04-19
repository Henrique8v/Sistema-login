import subprocess
import os

def run_core():
    # 1. RASTRO ABSOLUTO DO COMPILADOR (Code::Blocks)
    gpp_path = r'C:\Program Files\CodeBlocks\MinGW\bin\g++.exe'
    
    # 2. CAMINHOS DO PROJETO
    base_dir = r"C:\xampp\htdocs\Sistema-login\services\cpp"
    source = os.path.join(base_dir, "analisador.cpp")
    output = os.path.join(base_dir, "analisador.exe")

    # Comando: Entra na pasta, Compila e Executa. 
    cmd = f'"{gpp_path}" "{source}" -static -static-libgcc -static-libstdc++ -o "{output}" && "{output}"'    

    try:
        # Mudamos o cwd (Current Working Directory) para a pasta do CPP
        # Isso facilita para o C++ achar os logs usando "../../logs/"
        result = subprocess.run(cmd, shell=True, capture_output=True, text=True, cwd=base_dir)
        
        if result.returncode == 0:
            # Se tudo deu certo, imprime o output dourado pro Dashboard
            print(result.stdout)
        else:
            # Se houver erro de sintaxe no C++, ele avisa aqui
            print(f"ERRO DE COMPILAÇÃO C++:\n{result.stderr}")
            
    except Exception as e:
        print(f"FALHA NO ORQUESTRADOR: {str(e)}")

if __name__ == "__main__":
    run_core()