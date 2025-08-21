<?php
    session_start();
    require_once 'conexao.php';

    if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] !=2) {
        echo "<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
        exit();
    }

// Inicializa a variável para evitar erros 
$usuarios = [];

// Se o formulário for enviado, busca o usuário pelo id ou nome.

if ($_SERVER["REQUEST_METHOD"] ==  "POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);

    // Verifica se a busca é um número (id) ou um nome
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome like :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
} else {
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";       
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->fetchALL(PDO::FETCH_ASSOC);

// Obtendo o nome do perfil do usuario logado 
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];


$permissoes = [
    1=> ["Cadastrar"=>["cadastro_usuario.php", "cadastro_perfil.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_produto.php", "cadastro_funcionario.php"],
        "Buscar"=>["buscar_usuario.php", "buscar_perfil.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        "Alterar"=>["alterar_usuario.php", "alterar_perfil.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],
        "Excluir"=>["excluir_usuario.php", "excluir_perfil.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"]],

    2=> ["Cadastrar"=>["cadastro_cliente.php"],
        "Buscar"=>["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar"=>["alterar_cliente.php", "alterar_fornecedor.php"]],
        
    3=> ["Cadastrar"=>[ "cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar"=>[ "buscar_cliente.php", "buscar_fornecedor.php", "buscar_funcionario.php"],
        "Alterar"=>[ "alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir"=>["excluir_produto.php"]],
    
    4=> ["Cadastrar"=>[ "cadastro_cliente.php"],
        "Alterar"=>[ "alterar_cliente.php"]]
];

$opcoes_menu = $permissoes[$id_perfil]; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav>
        <ul class="menu">
            <?php foreach($opcoes_menu as $categoria=>$arquivos): ?>
            <li class="dropdown">
                    <a href="#"><?php echo $categoria; ?></a>
                    <ul class="dropdown-menu">
                    <?php foreach($arquivos as $arquivo):?>
                        <li>
                            <a href="<?=$arquivo ?>"> <?= ucfirst(str_replace("_", " ",basename ($arquivo, ".php")))?></a>
                        </li>
                        <?php endforeach; ?> 
                    </ul>
            </li>
            <?php endforeach; ?>
        </ul>
     </nav>


    <h2> Lista de Usuarios </h2>
    <!-- Formulário para buscar usuário -->
     <form action="buscar_usuario.php" method="POST">
        <label for="busca"> Digite o ID ou Nome (opcional):</label>
        <input type="text" id="busca" name="busca">
        <button type="submit">Pesquisar</button> 
    </form>

    <?php if(!empty($usuarios)):?>
       <center> <table class="tabela">
            <tr>
                <th> ID </th>
                <th> Nome </th>
                <th> Email </th>
                <th> Perfil </th>
                <th> Ações </th>
            </tr>

            <?php foreach($usuarios as $usuario): ?>
                <tr>
                    <td> <?=htmlspecialchars($usuario['id_usuario']) ?></td>
                    <td> <?=htmlspecialchars($usuario['nome']) ?></td>
                    <td> <?=htmlspecialchars($usuario['email']) ?></td>
                    <td> <?=htmlspecialchars($usuario['id_perfil']) ?></td>
                    <td>
                        <a href="alterar_usuario.php?id= <?=htmlspecialchars($usuario['id_usuario'])?>"> Alterar</a>

                        <a href="excluir_usuario.php?id= <?=htmlspecialchars($usuario['id_usuario'])?>"onclick="return confirm
                        ('Tem certeza que deseja excluir este usuario?')"> Excluir</a>
                    </td>
                </tr>
             <?php endforeach; ?>   
        </table> </center>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?> 
    
    <a href="principal.php"> Voltar </a>
</body>
</html>