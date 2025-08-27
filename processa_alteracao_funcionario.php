<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão de adm 
if ($_SESSION['perfil']!= 1) {
    echo"<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_POST['id_funcinario'];
    $nome = $_POST['nome_funcionario'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $id_perfil = $_POST['id_perfil'];
    $nova_senha = !empty($_POST['senha']) ? password_hash($_POST['nova_senha'], PASSWORD_DEFAULT) : null;

// Atualiza os dados do usuario
if ($nova_senha){
    $sql = "UPDATE funcionario set nome_funcionario = :nome_funcionario, email = :email, telefone = :telefone,  id_perfil = :id_perfil, senha = :senha WHERE id_funcionario = :nome_funcionario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':senha', $nova_senha);
}else {
    $sql = "UPDATE funcionario set nome_funcionario = :nome_funcionario, email = :email, telefone = :telefone, id_perfil = :id_perfil WHERE id_funcionario = :id_funcionario";
    $stmt = $pdo->prepare($sql);
}

$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':telefone', $telefone);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':id_perfil', $id_perfil);
$stmt->bindParam(':id_funcionario', $id_funcionario);

if ($stmt->execute()){
    echo"<script>alert('Funcioário atualizado com sucesso!');window.location.href='buscar_usuario.php';</script>";
}else {
    echo"<script>alert('Erro ao atualizar o Funcionários.');window.location.href='alterar_funcionario.php?id=$funcionario';</script>";
}
}
?>