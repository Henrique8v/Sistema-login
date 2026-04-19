O problema acontece porque a sua Lógica de Edição no PHP e no modalEdit só está preparada para salvar o texto puro (conteudo). Ela ignora completamente as novas colunas de alinhamento e gradiente. Quando você edita, o sistema sobrescreve o texto e "reseta" o resto para o padrão.

Para consertar isso, precisamos que o processo de edição também envie e salve essas informações.

1. Atualizar o Banco de Dados (SQL)
Se você ainda não tem as colunas para alinhamento, execute isso no seu MySQL:

SQL

ALTER TABLE timeline ADD COLUMN alinhamento VARCHAR(20) DEFAULT 'left';
ALTER TABLE timeline ADD COLUMN gradiente_id VARCHAR(50) DEFAULT '';
2. Atualizar o formulário no modalEdit (editar_feed.php)
O seu modal de edição precisa de campos ocultos para segurar esses valores, assim como o seu editor principal. Localize o id="modalEdit" e adicione os inputs e a toolbar:

PHP

<div class="toolbar" style="margin-bottom: 5px;">
    <button type="button" class="format-btn" onclick="alinharEdicao('left')">⫷</button>
    <button type="button" class="format-btn" onclick="alinharEdicao('center')">⫸⫷</button>
    <button type="button" class="format-btn" onclick="alinharEdicao('right')">⫸</button>
</div>

<textarea id="texto_editado" name="texto_editado"></textarea>

<input type="hidden" name="alinhamento_edit" id="alinhamento_edit">
<input type="hidden" name="gradiente_edit" id="gradiente_edit">
3. Atualizar a Lógica PHP (editar_feed.php)
Modifique o bloco if (isset($_POST['salvar_edicao'])) no topo do seu arquivo para processar os novos campos:

PHP

if (isset($_POST['salvar_edicao'])) {
    $id_edit = (int)$_POST['id_post'];
    $novo_texto = filter_input(INPUT_POST, 'texto_editado', FILTER_SANITIZE_SPECIAL_CHARS);
    $alinhamento = $_POST['alinhamento_edit'] ?? 'left';
    $gradiente = $_POST['gradiente_edit'] ?? '';
    
    // ATUALIZADO: Agora salvamos texto, alinhamento e gradiente
    $sql = "UPDATE timeline SET 
            conteudo = :c, 
            alinhamento = :ali, 
            gradiente_id = :grad 
            WHERE id_post = :id_p AND usuario_id = :id_u";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':c' => $novo_texto, 
        ':ali' => $alinhamento, 
        ':grad' => $gradiente, 
        ':id_p' => $id_edit, 
        ':id_u' => $id_logado
    ]);
    
    header("location: editar_feed.php?sucesso=1");
    exit;
}
4. Ajustar o JavaScript (editar_feed.js)
Precisamos que, ao clicar em editar, o JS pegue a formatação atual do post e coloque dentro do modal.

JavaScript

function abrirEdicao(id, texto, alinhamento, gradiente) {
    document.getElementById('id_edit').value = id;
    const area = document.getElementById('texto_editado');
    area.value = texto;
    
    // Aplica visualmente no modal
    area.style.textAlign = alinhamento;
    area.className = gradiente; // Aplica o gradiente na textarea do modal
    
    // Preenche os inputs hidden para o PHP ler
    document.getElementById('alinhamento_edit').value = alinhamento;
    document.getElementById('gradiente_edit').value = gradiente;
    
    document.getElementById('modalEdit').style.display = 'flex';
}

function alinharEdicao(alinhamento) {
    const area = document.getElementById('texto_editado');
    area.style.textAlign = alinhamento;
    document.getElementById('alinhamento_edit').value = alinhamento;
}
Por que bugava?
Quando você clicava em "Salvar", o PHP executava um UPDATE que só olhava para o texto. Como as colunas de alinhamento e gradiente não eram enviadas no formulário de edição, o banco de dados perdia essa informação ou voltava para o vazio. Agora, o fluxo de dados está completo: Banco -> Modal -> PHP -> Banco.

Crow? (Quer que eu revise como os cards estão sendo exibidos na grid para garantir que eles leiam o alinhamento do banco corretamente?)