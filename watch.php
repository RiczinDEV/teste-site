<?php
include 'includes/db.php';
include 'includes/auth.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM movies WHERE id = ? LIMIT 1');
$stmt->bind_param('i',$id); $stmt->execute(); $res = $stmt->get_result();
if(!$movie = $res->fetch_assoc()){ header('Location: catalog.php'); exit; }
?>
<!doctype html><html lang='pt-BR'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Assistir - <?php echo htmlspecialchars($movie['title']); ?></title><link rel='stylesheet' href='css/styles.css'></head><body>
<header class='header'><div class='logo'>Prime Video — Protótipo</div><div class='toplinks'><a class='link' href='catalog.php'>Voltar</a></div></header>
<main class='container'>
  <div style='max-width:1100px;margin:18px auto'><h2><?php echo htmlspecialchars($movie['title']); ?></h2>
    <div style='background:rgba(0,0,0,0.6);padding:12px;border-radius:10px'>
      <video id='player' controls playsinline style='width:100%;border-radius:8px;background:black'>
        <source src='videos/<?php echo htmlspecialchars($movie['filename']); ?>' type='video/mp4'>
        Seu navegador não suporta o elemento de vídeo.
      </video>
    </div>
    <p class='small' style='margin-top:10px'><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
  </div>
</main></body></html>