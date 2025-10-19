<?php
use App\Core\Helpers;
ob_start();
?>
<h1>Clientes</h1>
<form method="get" class="toolbar">
  <input type="hidden" name="c" value="clientes">
  <input type="hidden" name="a" value="index">
  <input type="search" name="q" placeholder="Buscar nombre, correo o DNI" value="<?=htmlspecialchars($q??'')?>">
  <button>Buscar</button>
  <a class="btn" href="?c=clientes&a=form">Nuevo</a>
</form>

<table class="table">
  <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>DNI</th><th>Teléfono</th><th></th></tr></thead>
  <tbody>
  <?php foreach($clientes as $cli): ?>
    <tr>
      <td><?=$cli['id']?></td>
      <td><?=htmlspecialchars($cli['nombre'].' '.$cli['apellido'])?></td>
      <td><?=htmlspecialchars($cli['correo'])?></td>
      <td><?=htmlspecialchars($cli['dni'])?></td>
      <td><?=htmlspecialchars($cli['telefono'])?></td>
      <td class="actions">
        <a href="?c=clientes&a=form&id=<?=$cli['id']?>">Editar</a>
        <form method="post" action="?c=clientes&a=delete" style="display:inline" onsubmit="return confirm('¿Eliminar cliente?');">
          <input type="hidden" name="id" value="<?=$cli['id']?>">
          <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf)?>">
          <button class="link danger">Eliminar</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php
// pagination
$pages = $pagination['pages']; $page = $pagination['page'];
if ($pages > 1): ?>
<nav class="pagination">
  <?php for($i=1;$i<=$pages;$i++): ?>
    <a class="<?=$i==$page?'active':''?>" href="?c=clientes&a=index&page=<?=$i?>&q=<?=urlencode($q??'')?>"><?=$i?></a>
  <?php endfor; ?>
</nav>
<?php endif; ?>

<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
