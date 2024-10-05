<?php
// Conectar ao banco de dados
$host = 'localhost';
$db = 'techplanting';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verificar se as senhas correspondem
    if ($senha != $confirmar_senha) {
        die("As senhas não correspondem.");
    }

    // Gerar o hash da senha
    $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

    // Preparar a consulta para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO clientes (nome, cpf, endereco, telefone, email, senha) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $cpf, $endereco, $telefone, $email, $senha_hash);

    // Executar a consulta
    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        header("Location: login.html");  // Redirecionar para a página de login
        exit();
    } else {
        echo "Erro ao cadastrar cliente: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
