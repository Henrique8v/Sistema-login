<?php
class Usuarios {
    private $pdo;
    public $msgERRO = ""; 

    public function conectar($nome, $host, $usuario, $senha) {
        try {
            $this->pdo = new PDO("mysql:dbname=".$nome.";host=".$host, $usuario, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->msgERRO = $e->getMessage();
        }
    }

    public function getPdo() { return $this->pdo; }

    public function cadastrar($nome, $telefone, $email, $senha) {
        $sql = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return false; 
        } else {
            // RV Project: Usando password_hash para compatibilidade com deletar_conta.php
            $senha_segura = password_hash($senha, PASSWORD_DEFAULT);
            $sql = $this->pdo->prepare("INSERT INTO usuarios (nome, telefone, email, senha) VALUES (:n, :t, :e, :s)");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":t", $telefone);
            $sql->bindValue(":e", $email);
            $sql->bindValue(":s", $senha_segura); 
            $sql->execute();
            return true;
        }
    }

    public function logar($email, $senha) {
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :e");
        $sql->bindValue(":e", $email);
        $sql->execute();
    
        if ($sql->rowCount() > 0) {
            $dado = $sql->fetch(PDO::FETCH_ASSOC);
            if (password_verify($senha, $dado['senha'])) {
                 if(!isset($_SESSION)) { session_start(); }
                 $_SESSION['id_usuario'] = $dado['id_usuario'];
                 $_SESSION['nome_usuario'] = $dado['nome'];
                 
                 // CORREÇÃO PARA O ERRO DE FOTO E BIO:
                 $_SESSION['foto_usuario'] = (!empty($dado['foto'])) ? $dado['foto'] : 'img/default-user.png';
                 $_SESSION['bio_usuario'] = $dado['bio'] ?? "Este membro ainda não revelou sua história.";
    
                 return true;
            }
        }
        return false;
    }
	
    public function atualizarPerfil($nome, $bio, $senha, $id_usuario) {
            // Limita a bio para não quebrar o layout
            $bio_limitada = mb_substr($bio, 0, 300);
            
            $sql = "UPDATE usuarios SET nome = :n, bio = :b WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":n", $nome);
            $stmt->bindValue(":b", $bio_limitada);
            $stmt->bindValue(":id", $id_usuario);
            $stmt->execute();
    
           // Se o usuário digitou uma nova senha, ela é criptografada antes de salvar
            if (!empty($senha)) {
                $sqlSenha = "UPDATE usuarios SET senha = :s WHERE id_usuario = :id";
                $stmtS = $this->pdo->prepare($sqlSenha);
                $stmtS->bindValue(":s", password_hash($senha, PASSWORD_DEFAULT));
                $stmtS->bindValue(":id", $id_usuario);
                $stmtS->execute();
            }
    
            // Atualiza a sessão para o nome mudar no menu instantaneamente
            if(!isset($_SESSION)) { session_start(); }
            $_SESSION['nome_usuario'] = $nome;
            $_SESSION['bio_usuario'] = $bio_limitada;
            return true;
        }
    
        public function atualizarFoto($caminho_foto, $id_usuario) {
            $sql = "UPDATE usuarios SET foto = :f WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":f", $caminho_foto);
            $stmt->bindValue(":id", $id_usuario);
            
            if($stmt->execute()) {
                if(!isset($_SESSION)) { session_start(); }
                $_SESSION['foto_usuario'] = $caminho_foto;
                return true;
            }
            return false;
        }	
}