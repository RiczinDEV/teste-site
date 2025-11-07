<?php
include 'includes/db.php';
include 'includes/auth.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$logged = is_logged_in();
$name = $_SESSION['user_name'] ?? null;
$movies_res = $conn->query('SELECT * FROM movies ORDER BY id DESC')->fetch_all(MYSQLI_ASSOC);

$groups = [];
foreach($movies_res as $m){
  $cat = $m['category'] ?: 'Outros';
  if(!isset($groups[$cat])) $groups[$cat] = [];
  $groups[$cat][] = $m;
}
$hero_slides = array_slice($movies_res, 0, 6);
?>
<!doctype html><html lang='pt-BR'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width,initial-scale=1'><title>Prime - Catálogo</title><link rel='stylesheet' href='css/styles.css'></head><body>
<header class='header'><div class='logo'>Prime Video — Protótipo</div>
<div class='toplinks'>
<?php if($logged): ?>
  <span style='margin-right:8px'>Olá, <?php echo htmlspecialchars($name); ?></span><a class='link' href='logout.php'>Sair</a>
<?php else: ?>
  <a class='link' href='login.php'>Entrar</a><a class='link' href='register.php'>Cadastrar</a>
<?php endif; ?>
</div></header>
<main class='container'>
  <div class='hero'><div id='heroInner' class='hero-inner'>
  <?php foreach($hero_slides as $h): ?>
    <div class='hero-slide' style='background-image:url(<?php echo htmlspecialchars($h['hero']); ?>)'><div class='hero-caption'><h1><?php echo htmlspecialchars($h['title']); ?></h1><p class='small'><?php echo htmlspecialchars($h['description']); ?></p><br><a class='btn' href='watch.php?id=<?php echo $h['id']; ?>'>Assistir</a></div></div>
  <?php endforeach; ?>
  </div></div>
  <?php foreach($groups as $cat => $list): ?>
    <div class='row'><h3><?php echo htmlspecialchars($cat); ?></h3>
      <div style='display:flex;align-items:center;gap:8px;margin-bottom:8px'><button class='btn' onclick="scrollRow('row_<?php echo md5($cat); ?>', -1)">◀</button><button class='btn' onclick="scrollRow('row_<?php echo md5($cat); ?>', 1)">▶</button></div>
      <div id='row_<?php echo md5($cat); ?>' class='carousel-row'><div class='track'>
        <?php foreach($list as $m): ?>
          <div class='card' onclick="location.href='watch.php?id=<?php echo $m['id']; ?>'">
            <div class='badge'><?php echo htmlspecialchars($m['rating']); ?></div>
            <img src='<?php echo htmlspecialchars($m['thumb']); ?>' alt='thumb'>
            <div class='hover-panel'><div class='hover-title'><?php echo htmlspecialchars($m['title']); ?></div><div class='hover-desc'><?php echo htmlspecialchars($m['description']); ?></div><div style='margin-top:8px;display:flex;justify-content:space-between;align-items:center'><span class='btn'>Detalhes</span><a class='btn' href='watch.php?id=<?php echo $m['id']; ?>'>Assistir</a></div></div>
            <div class='meta'><div class='title'><?php echo htmlspecialchars($m['title']); ?></div><div class='sub'><?php echo htmlspecialchars($m['category']); ?></div></div>
          </div>
        <?php endforeach; ?>
      </div></div>
    </div>
  <?php endforeach; ?>
</main><footer class='footer'>Protótipo acadêmico — adaptado para apresentação.</footer><script src='js/script.js'></script></body></html>