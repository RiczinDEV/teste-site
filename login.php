<?php
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido';
  if(empty($errors)){
    $stmt = $conn->prepare('SELECT id,name,password FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s',$email); $stmt->execute(); $res = $stmt->get_result();
    if($row = $res->fetch_assoc()){
      if(password_verify($pass, $row['password'])){
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        header('Location: catalog.php'); exit;
      } else { $errors[] = 'Senha incorreta'; }
    } else { $errors[] = 'E-mail não encontrado'; }
  }
}
?>
<!doctype html><html lang='pt-BR'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Entrar</title><link rel='stylesheet' href='css/styles.css'></head><body>
<header class='header'><div class='logo'>Prime Video — Protótipo</div><div class='toplinks'><a class='link' href='register.php'>Criar conta</a></div></header>
<main class='container'><div style='max-width:520px;margin:28px auto;background:var(--panel);padding:20px;border-radius:12px'><h3>Entrar</h3>
<?php if(!empty($errors)){ echo "<div style='color:#ffb4b4;margin:10px 0'>".implode('<br>',$errors)."</div>"; } ?>
<form method='post'><input class='searchbar' name='email' placeholder='Email' type='email' required><br><br><input class='searchbar' name='password' placeholder='Senha' type='password' required><br><br><button class='btn' type='submit'>Entrar</button></form>
<div style='margin-top:12px;color:var(--muted)'>Não tem conta? <a class='link' href='register.php'>Criar agora</a></div></div></main></body></html>
