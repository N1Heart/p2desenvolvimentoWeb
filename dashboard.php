<?php
// dashboard.php (Página principal do CRUD de Livros - Read)
require_once 'config/config.php';
require_once 'auth_check.php'; // REQUISITO: Protege a página
include 'header.php'; // Inclui o cabeçalho

$user_id = $user_id_logado; // Vem do auth_check.php

echo "<h1>Meus Livros</h1>";
echo "<p>Bem-vindo, " . htmlspecialchars($_SESSION['user_nome']) . "!</p>";

// Lógica para buscar (READ) os livros do usuário logado
$stmt = $conn->prepare("SELECT id, titulo, autor, genero, ano_publicacao FROM livros WHERE usuario_id = ? ORDER BY data_criacao DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="task-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($livro = $result->fetch_assoc()): ?>
            <div class="task-item">
                <div class="task-item-details">
                    <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p>
                        <strong>Autor:</strong> <?php echo htmlspecialchars($livro['autor']); ?><br>
                        <strong>Gênero:</strong> <?php echo htmlspecialchars($livro['genero']); ?><br>
                        <strong>Ano:</strong> <?php echo htmlspecialchars($livro['ano_publicacao']); ?>
                    </p>
                </div>
                <div class="task-item-actions">
                    <!-- REQUISITO: Uso de GET para passar ID para edição -->
                    <a href="livro_form.php?id=<?php echo $livro['id']; ?>" class="action-link edit-link">Editar</a>
                    
                    <!-- REQUISITO: Uso de GET para passar ID para exclusão -->
                    <a href="livro_action.php?action=delete&id=<?php echo $livro['id']; ?>" class="action-link delete-link">Excluir</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Você ainda não tem nenhum livro cadastrado.</p>
        <a href="livro_form.php" class="btn" style="width: auto; margin-top: 10px;">Cadastrar primeiro livro</a>
    <?php endif; ?>
</div>

<?php
$stmt->close();
$conn->close();
include 'footer.php'; // Inclui o rodapé
?>