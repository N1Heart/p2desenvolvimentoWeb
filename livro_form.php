<?php
// livro_form.php (Formulário de Create e Update)
require_once 'config/config.php';
require_once 'auth_check.php'; // Protege a página

$user_id = $user_id_logado;

// Inicializa variáveis
$is_edit_mode = false;
$livro_id = null;
$titulo = "";
$autor = "";
$genero = "";
$ano_publicacao = "";

// REQUISITO: Verifica se um ID foi passado via GET (modo de Edição)
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $livro_id = (int)$_GET['id'];

    // Busca o livro NO BANCO para preencher o formulário
    // IMPORTANTE: Verifica se o livro pertence ao usuário logado
    $stmt = $conn->prepare("SELECT titulo, autor, genero, ano_publicacao FROM livros WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $livro_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $livro = $result->fetch_assoc();
        $titulo = $livro['titulo'];
        $autor = $livro['autor'];
        $genero = $livro['genero'];
        $ano_publicacao = $livro['ano_publicacao'];
    } else {
        // Livro não encontrado ou não pertence ao usuário
        header("Location: dashboard.php");
        exit;
    }
    $stmt->close();
}

// Inclui o cabeçalho
include 'header.php';
?>

<!-- O título muda dependendo do modo -->
<h1><?php echo $is_edit_mode ? 'Editar Livro' : 'Cadastrar Novo Livro'; ?></h1>

<!-- REQUISITO: O formulário usa POST -->
<form action="livro_action.php" method="POST">
    
    <!-- Campo oculto para definir a AÇÃO (criar ou atualizar) -->
    <input type="hidden" name="action" value="<?php echo $is_edit_mode ? 'update' : 'create'; ?>">
    
    <!-- Se estiver editando, envia o ID do livro -->
    <?php if ($is_edit_mode): ?>
        <input type="hidden" name="id" value="<?php echo $livro_id; ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($autor); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="genero">Gênero:</label>
        <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($genero); ?>">
    </div>
    
    <div class="form-group">
        <label for="ano_publicacao">Ano de Publicação:</label>
        <input type="number" id="ano_publicacao" name="ano_publicacao" value="<?php echo htmlspecialchars($ano_publicacao); ?>" min="0" max="<?php echo date('Y'); ?>">
    </div>
    
    <button type="submit" class="btn">
        <?php echo $is_edit_mode ? 'Salvar Alterações' : 'Cadastrar Livro'; ?>
    </button>
    <a href="dashboard.php" class="btn btn-secondary" style="margin-top: 10px;">Cancelar</a>
</form>

<?php
$conn->close();
include 'footer.php';
?>