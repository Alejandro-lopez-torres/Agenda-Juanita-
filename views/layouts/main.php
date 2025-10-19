<?php use App\Core\Helpers; $csrf = Helpers::csrfToken(); ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Agenda Juanita</title>
  <link rel="stylesheet" href="assets/styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<nav class="topnav">
  <a href="?c=clientes&a=index">Clientes</a>
  <a href="?c=citas&a=index">Agenda</a>
</nav>
<main class="container">
  <?php if (!empty($_GET['msg'])): ?><div class="flash ok">Acción realizada.</div><?php endif; ?>
  <?php if (!empty($_GET['error'])): ?><div class="flash error"><?=htmlspecialchars($_GET['error'])?></div><?php endif; ?>
  <?php echo $content ?? ''; ?>
</main>
<script>window.CSRF_TOKEN="<?=htmlspecialchars($csrf)?>";</script>
<script src="assets/app.js"></script>
</body>
</html>
