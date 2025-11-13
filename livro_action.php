<?php
require_once 'config/config.php';
require_once 'auth_check.php'; 
$user_id = $user_id_logado;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $genero = trim($_POST['genero']);
    $ano_publicacao = !empty($_POST['ano_publicacao']) ? (int)$_POST['ano_publicacao'] : null;
    $action = $_POST['action'];

    if (empty($titulo) || empty($autor)) {
        die("Título e Autor são obrigatórios.");
    }

    if ($action == 'create') {
        $stmt = $conn->prepare("INSERT INTO livros (usuario_id, titulo, autor, genero, ano_publicacao) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $user_id, $titulo, $autor, $genero, $ano_publicacao);
        $stmt->execute();
        $stmt->close();
    } 
    elseif ($action == 'update' && isset($_POST['id'])) {
        $livro_id = (int)$_POST['id'];
        
        $stmt = $conn->prepare("UPDATE livros SET titulo = ?, autor = ?, genero = ?, ano_publicacao = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("sssiii", $titulo, $autor, $genero, $ano_publicacao, $livro_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

} 
elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    
    $livro_id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM livros WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $livro_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

header("Location: dashboard.php");
exit;
?>