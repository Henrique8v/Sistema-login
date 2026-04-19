#include <iostream>
#include <fstream>
#include <string>
#include <sys/stat.h>
#include <vector>

using namespace std;

// Função para obter o tamanho do ficheiro em bytes
long getFileSize(const string& filename) {
    struct stat stat_buf;
    int rc = stat(filename.c_str(), &stat_buf);
    return rc == 0 ? stat_buf.st_size : -1;
}

int main() {
    cout << ">>> CORE ANALYSIS & MAINTENANCE ENGINE <<<" << endl;
    cout << "------------------------------------------" << endl;

    // 1. SAÚDE DO MYSQL
    string mysqlPath = "C:/xampp/mysql/data/ibdata1";
    cout << "[CHECK] MySQL Status: ";
    if (getFileSize(mysqlPath) != -1) cout << "ONLINE" << endl;
    else cout << "ALERTA: ibdata1 nao encontrado!" << endl;

    // 2. ANÁLISE E LIMPEZA DE LOGS (LOG ROTATION)
    vector<string> logs = {"../../dashboard/tunneling/pinggy_panel/logs/pinggy_live.log", "../../dashboard/tunneling/pinggy_panel/logs/rastros_live.log"};
    long limit = 5 * 1024 * 1024; // Limite de 5MB

    for (const string& logPath : logs) {
        long size = getFileSize(logPath);
        cout << "[LOG] " << logPath << ": ";
        
        if (size == -1) {
            cout << "Vazio ou Inexistente." << endl;
        } else {
            cout << (size / 1024) << " KB";
            if (size > limit) {
                // Se passar do limite, limpa o ficheiro
                ofstream ofs(logPath, ios::trunc); 
                ofs.close();
                cout << " -> [LIMPEZA EXECUTADA (Limite Excedido)]";
            }
            cout << endl;
        }
    }

    cout << "------------------------------------------" << endl;
    cout << ">>> CORE OPERATION COMPLETE. <<<" << endl;

    return 0;
}