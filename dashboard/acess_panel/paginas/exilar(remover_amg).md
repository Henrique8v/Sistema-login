Para resolver o problema do botão "Exilar" não funcionar, a mudança deve ser feita no arquivo amizade_logica.php.

Por que no amizade_logica.php?
Analisando o seu arquivo amigos.php, a função JavaScript exilarAmigo(id) faz um fetch exatamente para este arquivo:

JavaScript

fetch('site/data/amizade_logica.php', { method: 'POST', body: fd })
Além disso, no amizade_logica.php que você enviou, a lógica de remoção está incompleta porque ela só tenta deletar se você foi quem enviou o convite original (id_usuario = :me). Se a outra pessoa te adicionou, o seu ID estará na coluna id_amigo, e a query atual não encontrará a linha para deletar.

A Correção (Copie e cole este bloco no seu amizade_logica.php)
Substitua o bloco // AÇÃO: REMOVER (EXILAR) pelo código abaixo:

PHP

// AÇÃO: REMOVER (EXILAR)
if (isset($_POST['remover_id'])) {
    $id_exilado = (int)$_POST['remover_id'];

    // CORREÇÃO: Deleta a amizade independente de quem enviou o convite (quem é usuario ou quem é amigo)
    $sql = "DELETE FROM amizades 
            WHERE (id_usuario = :me AND id_amigo = :exilado) 
               OR (id_usuario = :exilado AND id_amigo = :me)";
    
    $stmt = $pdo->prepare($sql);
    $executou = $stmt->execute([
        ':me' => $meu_id,
        ':exilado' => $id_exilado
    ]);

    if ($executou) {
        echo "removido";
    } else {
        echo "erro";
    }
    exit;
}
O que foi corrigido:
Lógica Bidirecional: Agora o SQL usa OR, verificando as duas posições possíveis dos IDs na tabela amizades.

Sincronização com o Front-end: O arquivo amigos.php espera a resposta "removido" para esconder o card do membro exilado com o efeito de fade.

Segurança: Mantivemos o uso de Prepared Statements com o seu objeto $pdo.

Sobre o processa_amizade.php: Você pode mantê-lo como está ou até desativá-lo futuramente, pois ele parece ser um arquivo antigo que lida com uma tabela chamada amigos (no plural), enquanto o seu banco de dados oficial do projeto usa a tabela amizades. Foque no amizade_logica.php que é o que o seu sistema moderno está chamando.

############################################################################################

amigos.php:

function exilarAmigo(id) {
        const card = document.getElementById('amigo-' + id);
        let fd = new FormData();
        fd.append('remover_id', id);

        fetch('site/data/amizade_logica.php', { method: 'POST', body: fd })
        .then(res => res.text())
        .then((data) => {
            // Se o seu amizade_logica retornar "removido" conforme o código enviado
            card.style.opacity = '0.3'; // Feedback visual imediato
            setTimeout(() => { 
                card.remove(); 
                if(document.querySelectorAll('.card-amigo').length === 0) {
                    location.reload();
                }
            }, 500);
        });
}