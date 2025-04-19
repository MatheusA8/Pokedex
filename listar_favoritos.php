<?php
$conn = new mysqli("localhost", "root", "", "pokedex");

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode([]);
  exit;
}

$result = $conn->query("SELECT nome FROM favoritos");
$favoritos = [];

while ($row = $result->fetch_assoc()) {
  $favoritos[] = $row['nome'];
}

echo json_encode($favoritos);
$conn->close();
?>
