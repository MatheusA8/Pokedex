<?php
if ($_SERVER['REQUEST_METHOD']==='POST') {
  include 'conexao.php';
  $nome=$_POST['nome']??'';
  $email=$_POST['email']??'';
  $hash=password_hash($_POST['senha'],PASSWORD_DEFAULT);
  $stmt=$pdo->prepare("INSERT INTO usuario(nome,email,senha)VALUES(?,?,?)");
  if($stmt->execute([$nome,$email,$hash])){
    header("Location: login.php");
    exit;
  } else $erro="Erro ao cadastrar!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Cadastro</title>
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
    <h2>Cadastro</h2>
    <form method="POST">
      <input type="text" name="nome" placeholder="Seu nome" required>
      <input type="email" name="email" placeholder="Seu email" required>
      <input type="password" name="senha" placeholder="Crie uma senha" required>
      <button type="submit">Cadastrar</button>
    </form>
    <?php if(!empty($erro)):?><p class="error"><?=$erro?></p><?php endif;?>
    <a href="login.php">Já tem conta? Faça login</a>
  </div>
</body>
</html>
