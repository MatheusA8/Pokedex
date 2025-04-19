<?php
session_start();
require 'conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
  http_response_code(401);
  echo json_encode([]);
  exit;
}

$userId = $_SESSION['user']['id'];
$stmt   = $pdo->prepare("SELECT nome FROM favoritos WHERE usuario_id = ?");
$stmt->execute([$userId]);
$favs = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($favs);
