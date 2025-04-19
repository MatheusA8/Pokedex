<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include 'conexao.php';

// Busca todos os nomes favoritados
$id = $_SESSION['usuario_id'];
$stmt = $pdo->prepare("SELECT nome FROM favoritos WHERE usuario_id = ?");
$stmt->execute([$id]);
$lista = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Meus Favoritos</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family:'Rubik',sans-serif;
      background-image:
        url('https://img.freepik.com/vetores-gratis/fundo-de-efeito-de-zoom-gradiente_23-2149722799.jpg?semt=ais_hybrid&w=740'),
        linear-gradient(135deg,#1e3c72,#2a5298);
      background-size:cover;
      background-position:center;
      background-attachment:fixed;
      background-blend-mode:overlay;
      color:#fff;
      min-height:100vh;
      display:flex;
      flex-direction:column;
    }
    header {
      text-align:center;
      padding:2rem 1rem;
    }
    header h1 {
      font-family:'Pokemon Solid',sans-serif;
      font-size:3rem;
      color:#ffcb05;
      text-shadow:2px 2px #0008;
      margin-bottom:1rem;
    }
    .top-buttons {
      text-align:center;
      margin-bottom:2rem;
    }
    .top-buttons a {
      display:inline-block;
      margin:0 .5rem;
      padding:.6rem 1.2rem;
      background:#ffc107;
      color:#000;
      border-radius:999px;
      text-decoration:none;
      font-weight:600;
      transition:background .2s;
    }
    .top-buttons a:hover { background:#ffdd4b; }

    main {
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem 0.5rem;
      padding:0 2rem 2rem;
      justify-items:center;
    }
    .pokemon-card {
      position:relative;
      background:rgba(255,255,255,0.1);
      backdrop-filter:blur(12px);
      border-radius:1rem;
      padding:1rem;
      width:200px;
      text-align:center;
      box-shadow:0 8px 20px rgba(0,0,0,0.3);
      transition:transform .3s;
    }
    .pokemon-card:hover {
      transform:translateY(-5px) scale(1.02);
    }
    .pokemon-card img { width:120px; }
    .pokemon-card h2 {
      margin:.5rem 0;
      text-transform:capitalize;
      color:#fff;
      text-shadow:1px 1px #0008;
    }

    .desfavoritar-btn {
      margin-top:10px;
      background: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 8px 16px;
      font-size: 0.95rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }
    .desfavoritar-btn:hover {
      background: #c0392b;
      transform: scale(1.05);
    }
  </style>
</head>
<body>
  <header>
    <h1>Meus Favoritos</h1>
    <div class="top-buttons">
      <a href="index.php">🔍 Voltar</a>
      <a href="logout.php">🚪 Sair</a>
    </div>
  </header>

  <main id="favContainer">
    <?php foreach($lista as $nome): 
      $data = @file_get_contents("https://pokeapi.co/api/v2/pokemon/{$nome}");
      if (!$data) continue;
      $poke = json_decode($data);
      if (!$poke || empty($poke->sprites->front_default)) continue;
    ?>
      <div class="pokemon-card" data-name="<?= htmlspecialchars($nome) ?>">
        <img src="<?= $poke->sprites->front_default ?>" alt="<?= $poke->name ?>">
        <h2><?= $poke->name ?></h2>
        <button class="desfavoritar-btn">Desfavoritar</button>
      </div>
    <?php endforeach ?>
  </main>

  <script>
    document.querySelectorAll('.desfavoritar-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const card = btn.closest('.pokemon-card');
        const nome = card.getAttribute('data-name');
        const form = new FormData();
        form.append('nome', nome);
        const res = await fetch('favoritar.php', { method:'POST', body: form });
        const txt = (await res.text()).trim().toLowerCase();
        if (txt === 'desfavoritado') {
          card.remove();
        }
      });
    });
  </script>
</body>
</html>
