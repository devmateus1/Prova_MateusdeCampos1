<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php'; // Arquivo com as funções que geram a senha e simulam o envio 

// Verifica se o usuário já está logado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

      // Verifica se o usuário existe
      $sql ="SELECT * FROM usuarios WHERE email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

      if($usuario) {
        // Gera uma nova senha temporário e aleatória 
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // Atualiza a senha do usuario no banco de dados 
        $sql = "UPDATE usuarios SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt-$pdo->prepare($sql);
        $stmt->bindParam(' :senha', $senha_hash);
        $stmt->bindParam(' :email', $email);
        $stmt->execute();

        // Simula o envio do e-mail ou seja grava em txt
        simularEnvioEmail($email, $senha_temporaria);
        echo "<script>alert('Uma senha temporária foi gerada e enviada (Simulação). Verifique o arquivo emails_simulados.txt);window.location.href='login.php';</script>";      }
    }else {
        echo "<script>alert('E-mail não encontrado');</script>";
    }
?>

<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recuperar Senha </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2> Recuperar Senha </h2>
    <form action="recuperar_senha.php" method="POST">
        <label for="email">Digite o seu e-mail cadastrado: </label>
        <input type="email" name="email" id="email" required>

        <button type="submit">Enviar a Senha Temporária</button>
    </form>
</body>
</html>