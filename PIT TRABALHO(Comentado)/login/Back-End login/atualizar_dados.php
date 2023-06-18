<?php
$con = new PDO("mysql:host=localhost;dbname=teste", "root", "root");
// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome'])) {
    // Redirecionar para a página de login se não estiver logado
    header("Location: index.html");
    exit();
}

// Verificar se o formulário de alteração foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se o botão de exclusão foi acionado
    if (isset($_POST['delete'])) {
        // Deletar os dados da tabela
        $query = "DELETE FROM conta WHERE email = :email";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':email', $_SESSION['email']);
        $stmt->execute();

        // Redirecionar para a página de login após a exclusão
        header("Location: index.html");
        exit();
    } else {
        // Obter os dados do formulário
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $senha = $_POST['senha'];
        $email = $_POST['email'];
        $informacao = $_POST['informacao'];
        $telefone = $_POST['telefone'];
        $cpf = $_SESSION['cpf'];

        // Atualizar os dados na tabela
        $query = "UPDATE conta SET Nome = :nome, Sobrenome = :sobrenome, Senha = :senha, Email = :email, Informacao = :informacao, tel = :telefone WHERE cpf = :cpf";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':informacao', $informacao);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
    }
}

// Criar a tabela "conta" caso não exista
$query = "CREATE TABLE IF NOT EXISTS conta (
    Nome VARCHAR(50),
    Sobrenome VARCHAR(50),
    Senha VARCHAR(50),
    Email VARCHAR(50),
    Informacao VARCHAR(50),
    tel VARCHAR(50),
    cpf VARCHAR(50)
)";
$con->exec($query);

// Obter os dados atuais da tabela
$query = "SELECT * FROM conta WHERE email = :email";
$stmt = $con->prepare($query);
$stmt->bindParam(':email', $_SESSION['email']);
$stmt->execute();
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Alterar Dados</title>
</head>
<body>
    <h1>Alterar Dados</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $dados['Nome']; ?>" required><br><br>

        <label for="sobrenome">Sobrenome:</label>
        <input type="text" name="sobrenome" value="<?php echo $dados['Sobrenome']; ?>" required><br><br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" value="<?php echo $dados['Senha']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $dados['Email']; ?>" required><br><br>

        <label for="informacao">Informação:</label>
        <input type="text" name="informacao" value="<?php echo $dados['Informacao']; ?>" required><br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" value="<?php echo $dados['tel']; ?>" required><br><br>

        <input type="submit" value="Alterar Dados">
    </form>

    <form method="POST" action="">
        <input type="hidden" name="delete" value="true">
        <input type="submit" value="Deletar Conta">
    </form>

    <a href="pagina_inicial.php">Voltar</a>
</body>
</html>

