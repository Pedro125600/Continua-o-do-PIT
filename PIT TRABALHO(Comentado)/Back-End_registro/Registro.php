<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>
<body>





      

<?php
// Estabelece a conexão com o banco de dados MySQL
$con = new PDO("mysql:host=localhost;dbname=teste", "root", "root");

// Classe Pessoa
class Pessoa
{
    private $nome;
    private $sobrenome;
    private $senha;
    private $confirmarSenha;
    private $email;
    private $info;
    private $tel;
    private $cpf;

    // Método para definir o valor de uma propriedade
    public function set($propriedade, $valor)
    {
        $this->$propriedade = $valor;
    }

    // Método para obter o valor de uma propriedade
    public function getPropriedades($propriedade)
    {
        return $this->$propriedade;
    }
}

// Instanciação da classe Pessoa
$obj = new Pessoa();

// Verifica se o formulário foi enviado via método POST
if (isset($_POST['enviar'])) {
    $obj->set("nome", $_POST['Nome']);
    $obj->set("sobrenome", $_POST['lastname']);
    $obj->set("senha", $_POST['Senha']);
    $obj->set("confirmarSenha", $_POST['Senha2']);
    $obj->set("email", $_POST['Email']);
    $obj->set("info", $_POST['Info']);
    $obj->set("tel", $_POST['Tel']);
    $obj->set("cpf", $_POST['CPF']);

    // Verifica se a senha e a confirmação de senha coincidem
    if ($obj->getPropriedades("senha") !== $obj->getPropriedades("confirmarSenha")) {
        echo "As senhas não coincidem. Por favor, tente novamente.";
        exit;
    }

    // Prepara uma declaração SQL para selecionar emails semelhantes ao email fornecido no banco de dados
    $stmt = $con->prepare("SELECT email FROM conta WHERE email LIKE :email");
    $stmt->bindValue(':email', $obj->getPropriedades("email") . '%');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        foreach ($result as $row) {
            // Verifica se um email idêntico já está cadastrado no banco de dados
            if ($row['email'] === $obj->getPropriedades("email")) {
                echo "Email semelhante já cadastrado: " . $row['email'];
                exit;
            }
        }
    }

    // Insere os dados no banco de dados
    $stmt = $con->prepare("INSERT INTO conta(nome, sobrenome, senha, email, Informacao, tel, cpf) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$obj->getPropriedades("nome"), $obj->getPropriedades("sobrenome"), $obj->getPropriedades("senha"), $obj->getPropriedades("email"), $obj->getPropriedades("info"), $obj->getPropriedades("tel"), $obj->getPropriedades("cpf")]);

    echo "Cadastro realizado com sucesso!";
}
?>



<html>
<body>

</body>
</html>
