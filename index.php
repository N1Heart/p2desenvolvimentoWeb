<?php
// index.php
require_once 'config/config.php'; // Apenas para iniciar a sessão

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Se sim, vai para o dashboard
    header("Location: dashboard.php");
} else {
    // Se não, vai para o login
    header("Location: login.php");
}
exit;
?>