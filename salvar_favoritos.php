<?php
session_start();
require 'conexao.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
  http_response_code(401);
  echo json_encode(['status'=>'erro','mensagem'=>'Usuário não autenticado']);
  exit;
}

$data        = json_decode(file_get_contents('php://input'), true);
$nomePokemon = $data['pokemon'] ?? null;
$usuario_id  = $_SESSION['user']['id'];

if (!$nomePokemon) {
  http_response_code(400);
  echo json_encode(['status'=>'erro','mensagem'=>'Pokémon não especificado']);
  exit;
}

// Verifica existência
$stmt = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id = ? AND nome = ?");
$stmt->execute([$usuario_id, $nomePokemon]);

if ($stmt->rowCount() === 0) {
  // adiciona
  $ins = $pdo->prepare("INSERT INTO favoritos (usuario_id, nome) VALUES (?, ?)");
  $ins->execute([$usuario_id, $nomePokemon]);
  echo json_encode(['status'=>'ok','acao'=>'favoritado','nome'=>$nomePokemon]);
} else {
  // remove
  $del = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND nome = ?");
  $del->execute([$usuario_id, $nomePokemon]);
  echo json_encode(['status'=>'ok','acao'=>'desfavoritado','nome'=>$nomePokemon]);
}
