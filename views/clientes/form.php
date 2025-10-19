<?php
use App\Core\Helpers;
$csrf = Helpers::csrfToken();
ob_start(); $isEdit = !empty($cliente); ?>
<h1><?=$isEdit?'Editar':'Nuevo'?> cliente</h1>
<?php if (!empty($errors['_global'])): ?><div class="flash error"><?=htmlspecialchars($errors['_global'])?></div><?php endif; ?>
<form method="post" action="?c=clientes&a=save" class="form">
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?=$cliente['id']?>"><?php endif; ?>
  <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf)?>">
  <label>Nombre
    <input name="nombre" required value="<?=htmlspecialchars($cliente['nombre']??'')?>">
    <?php if(!empty($errors['nombre'])): ?><small class="error"><?=$errors['nombre']?></small><?php endif; ?>
  </label>
  <label>Apellido
    <input name="apellido" required value="<?=htmlspecialchars($cliente['apellido']??'')?>">
    <?php if(!empty($errors['apellido'])): ?><small class="error"><?=$errors['apellido']?></small><?php endif; ?>
  </label>
  <label>Correo
    <input type="email" name="correo" required value="<?=htmlspecialchars($cliente['correo']??'')?>">
    <?php if(!empty($errors['correo'])): ?><small class="error"><?=$errors['correo']?></small><?php endif; ?>
  </label>
  <label>DNI
    <input name="dni" required value="<?=htmlspecialchars($cliente['dni']??'')?>">
    <?php if(!empty($errors['dni'])): ?><small class="error"><?=$errors['dni']?></small><?php endif; ?>
  </label>
  <label>Teléfono
    <input name="telefono" value="<?=htmlspecialchars($cliente['telefono']??'')?>">
    <?php if(!empty($errors['telefono'])): ?><small class="error"><?=$errors['telefono']?></small><?php endif; ?>
  </label>
  <button type="submit">Guardar</button>
  <a class="btn" href="?c=clientes&a=index">Volver</a>
</form>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
