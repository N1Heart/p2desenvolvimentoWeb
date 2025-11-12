<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

define('DB_HOST',   'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'prova_web');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn-> set_charset("utf8mb4");
} catch (mysqli_sql_exception $e){
    error_log($e->getMessage());
    die("Erro ao conectar com o Banco de dados. Tente novamente mais tarde.");

}


define('BASE_URL', 'http://localhost/');

?>