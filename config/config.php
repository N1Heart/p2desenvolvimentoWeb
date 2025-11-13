<?php
// config/config.php
// Inicia a sessão em todas as páginas
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- LEITURA DAS VARIÁVEIS DE AMBIENTE (do docker-compose.yml) ---
// getenv() lê uma variável de ambiente definida no 'environment' do docker-compose
$db_host = getenv('DB_HOST');      // Vai ler "mysql"
$db_user = getenv('DB_USER');      // Vai ler "php_user"
$db_pass = getenv('DB_PASS');      // Vai ler "php123"
$db_name = getenv('DB_NAME');      // Vai ler "aula_php_pdo"

// Se alguma variável não for encontrada, define um padrão (fallback)
define('DB_HOST', $db_host ?: 'localhost');
define('DB_USER', $db_user ?: 'root');
define('DB_PASS', $db_pass ?: '');
define('DB_NAME', $db_name ?: 'projeto_php_crud');

// Habilitar relatórios de erros do mysqli para depuração
// Esta é a linha que estava dando erro, mas agora vai funcionar
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Conexão com o banco de dados usando mysqli
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    // Mostra um erro mais claro se a conexão falhar
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}

// URL base da aplicação (ajuste se estiver em uma subpasta)
define('BASE_URL', 'http://localhost:8000/'); // <-- ATENÇÃO NA PORTA 8000
?>