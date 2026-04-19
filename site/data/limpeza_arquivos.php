<?php
/**
 * RV Project - Módulo de Limpeza de Arquivos Mortos
 * Localização: Sistema-login/site/data/limpeza_configuracoes.php
 */

// 1. Definição dos caminhos com barras seguras para Windows
$python = "C:\Users\lorde\AppData\Local\Programs\Python\Python313\python.exe";
$script = "C:/xampp/htdocs/Sistema-login/scripts/python/limpeza_arquivos.py";

// 2. Verificação de Integridade do Ambiente
if (!file_exists($python)) {
    die("Erro Crítico: O motor Python não foi encontrado em: $python");
}

if (!file_exists($script)) {
    die("Erro Crítico: O script de automação não foi encontrado em: $script");
}

// 3. Execução do Script
// Usamos 2>&1 para capturar erros do Python e exibir no navegador
$comando = "\"$python\" \"$script\" 2>&1";
$resultado = shell_exec($comando);

// 4. Tratamento de Exibição (UTF-8)
// Como o Windows pode enviar em CP850, garantimos a conversão para o site
$resultado_limpo = mb_convert_encoding($resultado, "UTF-8", "auto");

?>

<!-- <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Manutenção de Sistema - Brotherhood</title>
    <style>
        body { background: #1a1a1a; color: #00ff00; font-family: monospace; padding: 20px; }
        .log-container { background: #000; border: 1px solid #333; padding: 15px; border-radius: 5px; }
        .status-ok { color: #00ff00; }
        .status-err { color: #ff0000; }
    </style>
</head>
<body>
    <h2>--- Relatório de Varredura de Arquivos ---</h2>
    <div class="log-container">
        <pre><?php echo $resultado_limpo; ?></pre>
    </div>
    <p>Caminho monitorado: <code>site/img/perfis/</code></p>
</body>
</html> -->