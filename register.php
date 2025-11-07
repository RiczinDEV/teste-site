<?php
include 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if(!$name) $errors[] = 'Nome é obrigatório';
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido';
  if(strlen($pass) < 6) $errors[] = 'Senha precisa ter ao menos 6 caracteres';
  if(empty($errors)){
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s',$email); $stmt->execute(); $stmt->store_result();
    if($stmt->num_rows > 0){ $errors[] = 'E-mail já cadastrado'; }
    else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $ins = $conn->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
      $ins->bind_param('sss',$name,$email,$hash);
      if($ins->execute()){
        $_SESSION['user_id'] = $ins->insert_id;
        $_SESSION['user_name'] = $name;
        header('Location: catalog.php'); exit;
      } else { $errors[] = 'Erro ao criar conta'; }
    }
  }
}
?>
<!doctype html><html lang='pt-BR'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Cadastrar</title><link rel='stylesheet' href='css/styles.css'></head><body>
<header class='header'><div class='logo'>Prime Video — Protótipo</div><div class='toplinks'><a class='link' href='login.php'>Entrar</a></div></header>
<main class='container'><div style='max-width:520px;margin:28px auto;background:var(--panel);padding:20px;border-radius:12px'><h3>Criar Conta</h3>
<?php if(!empty($errors)){ echo "<div style='color:#ffb4b4;margin:10px 0'>".implode('<br>',$errors)."</div>"; } ?>
<form method='post'><input class='searchbar' name='name' placeholder='Nome' required><br><br><input class='searchbar' name='email' placeholder='Email' type='email' required><br><br><input class='searchbar' name='password' placeholder='Senha' type='password' required><br><br><button class='btn' type='submit'>Cadastrar</button></form>
<div style='margin-top:12px;color:var(--muted)'>Já tem conta? <a class='link' href='login.php'>Entrar</a></div></div></main></body></html>
