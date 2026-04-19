<?php
//notificacoes_funcoes.php
function contarNotificacoes($pdo, $id_usuario) {
    $sql = "SELECT COUNT(*) FROM notificacoes WHERE id_destinatario = :id AND lida = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_usuario]);
    return $stmt->fetchColumn();
}

function buscarNotificacoes($pdo, $id_usuario) {
    // Buscamos a mensagem e o tipo para o loop híbrido funcionar
    $sql = "SELECT n.*, u.nome, u.foto 
            FROM notificacoes n 
            JOIN usuarios u ON n.id_remetente = u.id_usuario 
            WHERE n.id_destinatario = :id 
            ORDER BY n.data_notificacao DESC LIMIT 10";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_usuario]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}