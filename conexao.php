<?php
$host = 'localhost';  // Ou o endereço do seu servidor de banco de dados
$dbname = 'pokedex';
$username = 'root';  // O nome de usuário do banco de dados
$password = '';  // A senha do banco de dados

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
