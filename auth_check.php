<?php
// auth_check.php
// Este script será incluído no topo de todas as páginas protegidas

// config.php já deve ter sido incluído e iniciado a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a sessão 'user_id' NÃO existe
if (empty($_SESSION['user_id'])) {
    // Se não existir, redireciona para o login e para a execução
    header("Location: login.php?error=2"); // error=2 (acesso negado)
    exit;
}

// Se a sessão existe, o script continua e a página protegida é carregada
$user_id_logado = $_SESSION['user_id'];
?>