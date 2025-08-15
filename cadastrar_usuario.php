<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuario tem permissao 
// Supondo que o perfil 1 seja o adm
if(!isset$_SESSION['perfil'] != 1 ){
    echo"Acesso negado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $nome = $_POST['nome'];
   $email = $_POST['email'];
   $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
   $id_perfil = $_POST['id_perfil'];
    
    $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sqlPerfil);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':id_perfil', $id_perfil);

    if ( $stmt->execute()){

    };
    
}