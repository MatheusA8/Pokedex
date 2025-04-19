<?php
session_start();
include 'conexao.php';  // Certifique-se de que o $pdo está disponível aqui

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo "Usuário não logado.";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$nome = $_POST['nome'] ?? '';

if (!$nome) {
    http_response_code(400);
    echo "Nome inválido.";
    exit;
}

try {
    // Verifica se já existe nos favoritos
    $stmt = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id = ? AND nome = ?");
    $stmt->execute([$usuario_id, $nome]);

    if ($stmt->rowCount() > 0) {
        // Se já está, remove do banco
        $del = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id = ? AND nome = ?");
        $del->execute([$usuario_id, $nome]);
        echo "desfavoritado";
    } else {
        // Caso contrário, adiciona
        $ins = $pdo->prepare("INSERT INTO favoritos (usuario_id, nome) VALUES (?, ?)");
        $ins->execute([$usuario_id, $nome]);
        echo "adicionado";
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erro no banco: " . $e->getMessage();
}
