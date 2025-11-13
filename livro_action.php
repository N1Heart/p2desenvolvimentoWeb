<?php
// livro_action.php (Processa Create, Update e Delete)
require_once 'config/config.php';
require_once 'auth_check.php'; // Protege as ações

$user_id = $user_id_logado;

// --- Ação de CREATE e UPDATE (via POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Dados comuns
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $genero = trim($_POST['genero']);
    // Garante que o ano seja um inteiro ou null
    $ano_publicacao = !empty($_POST['ano_publicacao']) ? (int)$_POST['ano_publicacao'] : null;
    $action = $_POST['action'];

    // Validação simples
    if (empty($titulo) || empty($autor)) {
        die("Título e Autor são obrigatórios.");
    }

    if ($action == 'create') {
        // --- Lógica de CREATE ---
        $stmt = $conn->prepare("INSERT INTO livros (usuario_id, titulo, autor, genero, ano_publicacao) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $user_id, $titulo, $autor, $genero, $ano_publicacao);
        $stmt->execute();
        $stmt->close();
    } 
    elseif ($action == 'update' && isset($_POST['id'])) {
        // --- Lógica de UPDATE ---
        $livro_id = (int)$_POST['id'];
        
        // IMPORTANTE: Atualiza APENAS se o ID do livro pertencer ao user_id logado
        $stmt = $conn->prepare("UPDATE livros SET titulo = ?, autor = ?, genero = ?, ano_publicacao = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("sssiii", $titulo, $autor, $genero, $ano_publicacao, $livro_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

} 
// --- Ação de DELETE (via GET) ---
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    
    $livro_id = (int)$_GET['id'];

    // IMPORTANTE: Deleta APENAS se o ID do livro pertencer ao user_id logado
    $stmt = $conn->prepare("DELETE FROM livros WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $livro_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fecha a conexão
$conn->close();

// Após qualquer ação, redireciona de volta para o dashboard
header("Location: dashboard.php");
exit;
?>