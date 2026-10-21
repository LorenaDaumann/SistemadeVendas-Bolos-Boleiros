<?php
// Configurações do Banco de Dados
$host = 'localhost';
$db   = 'bolosboleiros';
$user = '';
$pass = '';

try {
    // 1. Conexão com o banco
    $pdo = new PDO("mysql:host=$host;dbname=$;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Consulta dos dados
    $stmt = $pdo->query("SELECT id, nome_curso FROM cursos ORDER BY nome_curso ASC");
    
    // 3. Armazena o resultado na variável que a View vai ler
    $listaCursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Tratamento de erro simplificado
    error_log("Erro no banco de dados: " . $e->getMessage());
    $listaCursos = []; 
}