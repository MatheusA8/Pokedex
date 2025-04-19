<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  include 'conexao.php';
  $email=$_POST['email']??'';
  $senha=$_POST['senha']??'';
  $stmt=$pdo->prepare("SELECT * FROM usuario WHERE email=?");
  $stmt->execute([$email]);
  $u=$stmt->fetch();
  if ($u && password_verify($senha,$u['senha'])) {
    $_SESSION['usuario_id']=$u['id'];
    header("Location: index.php");
    exit;
  } else $erro="Email ou senha inválidos!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Login</title>
  <style>
    /* ===== mesmo fundo ===== */
    body {
      margin:0;
      font-family:'Rubik',sans-serif;
      background-image:
        url('https://img.freepik.com/vetores-gratis/fundo-de-efeito-de-zoom-gradiente_23-2149722799.jpg?semt=ais_hybrid&w=740'),
        linear-gradient(135deg,#1e3c72,#2a5298);
      background-size:cover;
      background-position:center;
      background-attachment:fixed;
      background-blend-mode:overlay;
      display:flex;
      align-items:center;
      justify-content:center;
      height:100vh;
      color:#fff;
    }
    /* ===== box central ===== */
    .box {
      background:rgba(255,255,255,0.15);
      backdrop-filter:blur(10px);
      padding:2rem;
      border-radius:1rem;
      box-shadow:0 8px 20px rgba(0,0,0,0.4);
      width:320px;
      text-align:center;
    }
    .box h2 { color:#ffcb05; margin-bottom:1rem; }
    .box input, .box button {
      width:100%;
      padding:.75rem;
      margin:.5rem 0;
      border:none;
      border-radius:999px;
      font-size:1rem;
    }
    .box button {
      background:#ffc107;
      color:#000;
      font-weight:600;
      cursor:pointer;
      transition:.2s;
    }
    .box button:hover { background:#ffdd4b; }
    .box a { color:#ffc107; display:block; margin-top:.5rem; }
    .error { color:#f66; font-size:.9rem; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar</button>
    </form>
    <?php if(isset($erro)):?><p class="error"><?=$erro?></p><?php endif;?>
    <a href="cadastro.php">Cadastre‑se</a>
  </div>
</body>
</html>
