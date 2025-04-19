<?php
session_start();
$nome = $_GET['nome'];
$favoritos = $_SESSION['favoritos'] ?? [];
echo in_array($nome, $favoritos) ? 'true' : 'false';