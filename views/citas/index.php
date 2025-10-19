<?php
use App\Core\Helpers;
ob_start();
?>
<h1>Agenda</h1>
<form method="get" class="toolbar">
  <input type="hidden" name="c" value="citas">
  <input type="hidden" name="a" value="index">
  <input type="search" name="q" placeholder="Buscar por asunto o cliente" value="<?=htmlspecialchars($q??'')?>">
  <select name="estado">
    <option value="">-- Estado --</option>
    <?php foreach (['Pendiente','Confirmada','Cancelada','Completada'] as $e): ?>
      <option value="<?=$e?>" <?=($estado??'')===$e?'selected':''?>><?=$e?></option>
    <?php endforeach; ?>
  </select>
  <select name="range">
    <option value="">Rango</option>
    <option value="hoy"     <?=($range??'')==='hoy'?'selected':''?>>Hoy</option>
    <option value="semana"  <?=($range??'')==='semana'?'selected':''?>>Semana</option>
    <option value="mes"     <?=($range??'')==='mes'?'selected':''?>>Mes</option>
  </select>
  <span>o</span>
  <input type="date" name="desde" value="<?=htmlspecialchars($desde??'')?>"> —
  <input type="date" name="hasta" value="<?=htmlspecialchars($hasta??'')?>">
  <button>Filtrar</button>
  <a class="btn" href="?c=citas&a=form">Nueva</a>
</form>

<table class="table">
  <thead><tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Asunto</th><th>Estado</th><th></th></tr></thead>
  <tbody>
  <?php foreach($citas as $c): ?>
    <tr>
      <td><?=$c['fecha']?></td>
      <td><?=$c['hora']?></td>
      <td><?=htmlspecialchars($c['cliente'])?></td>
      <td><?=htmlspecialchars($c['asunto'])?></td>
      <td><?=htmlspecialchars($c['estado'])?></td>
      <td class="actions">
        <a href="?c=citas&a=form&id=<?=$c['id']?>">Editar</a>
        <form method="post" action="?c=citas&a=delete" style="display:inline" onsubmit="return confirm('¿Eliminar cita?');">
          <input type="hidden" name="id" value="<?=$c['id']?>">
          <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf)?>">
          <button class="link danger">Eliminar</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php
$pages = $pagination['pages']; $page = $pagination['page'];
$query = http_build_query(['c'=>'citas','a'=>'index','q'=>$q??'','estado'=>$estado??'','range'=>$range??'','desde'=>$desde??'','hasta'=>$hasta??'']);
if ($pages > 1): ?>
<nav class="pagination">
  <?php for($i=1;$i<=$pages;$i++): ?>
    <a class="<?=$i==$page?'active':''?>" href="?<?=$query?>&page=<?=$i?>"><?=$i?></a>
  <?php endfor; ?>
</nav>
<?php endif; ?>

<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
