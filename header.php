<?php
require_once 'config/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logo">Meu App</div>
        <ul>
        <?php if(isset($_SESSION['user_id'])) ://mudar aqui tambem?>
            <li><a href="dashboard.php">Meus Livros</a></li>
            <li><a href="livro_form.php">Novo livro</a></li>
            <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['user_nome']);   // mudar o nome aqui se user não apontar a tabela?>)</a></li> 
        <?php else : ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Cadastrar</a></li>
        <?php endif; ?>

        </ul>
    </nav>
<main class="container">