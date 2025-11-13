<?php
require_once 'config/config.php'; // Inicia sessão e conecta ao DB

// Se o usuário já estiver logado, redireciona para o dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$email = "";
$error = "";
$success = "";

// Verifica se veio da página de registro com sucesso
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "Cadastro realizado com sucesso! Faça o login.";
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $error = "Email e senha são obrigatórios.";
    } else {
        // Busca o usuário pelo email (prepared statement)
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verifica a senha (REQUISITO OBRIGATÓRIO)
            if (password_verify($senha, $user['senha'])) {
                // Senha correta! Inicia a sessão.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];

                // Redireciona para a página interna
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Senha incorreta.";
            }
        } else {
            $error = "Usuário não encontrado.";
        }
        $stmt->close();
    }
}
$conn->close();

// Inclui o cabeçalho
include 'header.php';
?>

<div class="form-container">
    <h1>Login</h1>

    <!-- Exibe mensagens de erro ou sucesso -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button type="submit" class="btn">Entrar</button>
    </form>
    <p style="text-align: center; margin-top: 10px;">
        Não tem uma conta? <a href="register.php">Cadastre-se</a>
    </p>
</div>

<?php include 'footer.php'; ?>