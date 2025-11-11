<?php
include("config/database.php");
$db = new Database();
$mensagem = '';
$usuario_para_editar = null;


// 1. AÇÃO DE DELETE (via GET)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_para_deletar = (int)$_GET['id'];
    $result = $db->delete('usuarios', ['id' => $id_para_deletar]);
    $mensagem = $result['message'];
    header("Location: index.php?msg=" . urlencode($mensagem)); // Redireciona para limpar a URL
    exit;
}

// 2. AÇÃO DE CREATE ou UPDATE (via POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dados = [
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'],
        'idade' => (int)$_POST['idade'],
        'cidade' => $_POST['cidade']
    ];

    if ($_POST['action'] == 'create') {
        // --- CREATE ---
        $result = $db->create('usuarios', $dados);
        $mensagem = $result['message'];

    } elseif ($_POST['action'] == 'update' && isset($_POST['id'])) {
        // --- UPDATE ---
        $id_para_atualizar = (int)$_POST['id'];
        
        // Remove a senha do array se estiver vazia (para não sobrescrever a senha atual)
        if(empty($dados['senha'])) {
            unset($dados['senha']);
        }
        
        $result = $db->update('usuarios', $dados, ['id' => $id_para_atualizar]);
        $mensagem = $result['message'];
    }

    header("Location: index.php?msg=" . urlencode($mensagem)); // Redireciona para evitar reenvio do formulário
    exit;
}

// 3. AÇÃO DE EDITAR (Preencher o formulário para edição)
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id_para_editar = (int)$_GET['id'];
    $result = $db->read('usuarios', ['id' => $id_para_editar]);
    if ($result['success'] && $result['count'] > 0) {
        $usuario_para_editar = $result['data'][0];
    } else {
        $mensagem = "Usuário não encontrado.";
    }
}

// 4. AÇÃO DE READ (Sempre executa para listar)
$result_usuarios = $db->read('usuarios');
$usuarios = $result_usuarios['success'] ? $result_usuarios['data'] : [];

// Pega mensagem da URL (se houver)
if(isset($_GET['msg'])) {
    $mensagem = htmlspecialchars($_GET['msg']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-R">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
        h1, h2 { color: #333; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-crud { background: #eee; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .form-crud div { margin-bottom: 10px; }
        .form-crud label { display: inline-block; width: 80px; }
        .form-crud input[type="text"], .form-crud input[type="email"], .form-crud input[type="password"], .form-crud input[type="number"] { width: calc(100% - 100px); padding: 8px; border-radius: 4px; border: 1px solid #ccc; }
        .form-crud button { background: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .form-crud button.cancelar { background: #888; margin-left: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f9f9f9; }
        tr:nth-child(even) { background: #f2f2f2; }
        a { color: #007bff; text-decoration: none; }
        a.delete { color: #dc3545; margin-left: 10px; }
        .mensagem { padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Atividade CRUD com PHP e Docker</h1>
        
        <?php if ($mensagem): ?>
            <div class="mensagem"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <h2><?php echo $usuario_para_editar ? 'Editar Usuário' : 'Cadastrar Novo Usuário'; ?></h2>
        <form class="form-crud" action="index.php" method="POST">
            
            <input type="hidden" name="action" value="<?php echo $usuario_para_editar ? 'update' : 'create'; ?>">
            
            <?php if ($usuario_para_editar): ?>
                <input type="hidden" name="id" value="<?php echo $usuario_para_editar['id']; ?>">
            <?php endif; ?>

            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $usuario_para_editar['nome'] ?? ''; ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $usuario_para_editar['email'] ?? ''; ?>" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="<?php echo $usuario_para_editar ? '(Deixe em branco para não alterar)' : ''; ?>">
            </div>
            <div>
                <label for="idade">Idade:</label>
                <input type="number" id="idade" name="idade" value="<?php echo $usuario_para_editar['idade'] ?? ''; ?>" required>
            </div>
            <div>
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?php echo $usuario_para_editar['cidade'] ?? ''; ?>" required>
            </div>
            <div>
                <label></label>
                <button type="submit"><?php echo $usuario_para_editar ? 'Atualizar' : 'Salvar'; ?></button>
                <?php if ($usuario_para_editar): ?>
                    <a href="index.php" class="cancelar">Cancelar Edição</a>
                <?php endif; ?>
            </div>
        </form>

        <h2>Usuários Cadastrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Idade</th>
                    <th>Cidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="6">Nenhum usuário cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo $usuario['idade']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['cidade']); ?></td>
                            <td>
                                <a href="index.php?action=edit&id=<?php echo $usuario['id']; ?>">Editar</a>
                                
                                <a href="index.php?action=delete&id=<?php echo $usuario['id']; ?>" class="delete" onclick="return confirm('Tem certeza que deseja deletar este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>