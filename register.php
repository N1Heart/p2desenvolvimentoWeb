<?php
require_once 'config/config.php';

$nome = "";
$email = "";
$senha = "";
$errors =[];


if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome)){
        $errors[] = "O campo nome é Obrigatorio.";
    }
    if (empty($email)){
        $errors[] = "O campo email é obrigatorio";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Formato do email invalido";
    }
    if (empty($senha)){
        $errors[] = "O campo senha é obrigatório";
    }elseif(strlen($senha) < 6){
        $errors[] = "A senha deve ter no minimo 6 caracteres.";
    }


    if (empty($errors)){
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0){
            $errors[] = "Este email ja esta cadastrado";
        }
        $stmt->close();
}    

    if (empty($errors)){
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (nome , email, senha) VALUES (?,?,?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        if ($stmt->execute()){
            header("Location: login.php?success=1");
            exit;
        }   else{
            $errors[] = "Erro ao cadastrar. Tente novamente";
        }
        $stmt->close();
    }
}
$conn->close();

include 'header.php';

?>

<div class="form-container">
    <h1>Cadastre-se</h1>

    <?php if(!empty($errors)):?>
        <div class="alert alert-danger">
            <?php foreach($errors as $error):  ?>
                <p><?php echo $error;?></p>
            <?php endforeach;?>

        </div>
    <?php endif;?>
    
    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome) ?>" required>
        </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email) ?>" required>
        </div>
          <div class="form-group">
            <label for="senha">Senha (min. 6 caracteres):</label>
            <input type="password" id="senha" name="senha"  required>
        </div>
        <button type="submit" class="btn">Cadastrar</button>
    </form>

</div>
 
  </script>

<?php include 'footer.php' ?>