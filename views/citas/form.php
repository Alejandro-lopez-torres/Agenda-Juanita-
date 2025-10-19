<?php
use App\Core\Helpers;
$csrf = Helpers::csrfToken();
ob_start(); $isEdit = !empty($cita); ?>
<h1><?=$isEdit?'Editar':'Nueva'?> cita</h1>
<?php if (!empty($errors['_global'])): ?><div class="flash error"><?=htmlspecialchars($errors['_global'])?></div><?php endif; ?>
<form method="post" action="?c=citas&a=save" class="form" id="cita-form">
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?=$cita['id']?>"><?php endif; ?>
  <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf)?>">

  <label>Cliente
    <input type="hidden" name="client_id" id="client_id" value="<?=htmlspecialchars($cita['client_id']??'')?>">
    <input id="cliente_search" placeholder="Buscar cliente por nombre, correo o DNI" autocomplete="off"
           value="<?=htmlspecialchars($cita['client_id']??'' ? ($cita['client_id']) : '')?>">
    <div id="cliente_results" class="autocomplete"></div>
    <?php if(!empty($errors['client_id'])): ?><small class="error"><?=$errors['client_id']?></small><?php endif; ?>
    <small class="help">Escribe al menos 2 caracteres y selecciona un cliente.</small>
  </label>

  <label>Asunto
    <input name="asunto" required value="<?=htmlspecialchars($cita['asunto']??'')?>">
    <?php if(!empty($errors['asunto'])): ?><small class="error"><?=$errors['asunto']?></small><?php endif; ?>
  </label>
  <div class="row">
    <label>Fecha
      <input type="date" name="fecha" required value="<?=htmlspecialchars($cita['fecha']??'')?>">
      <?php if(!empty($errors['fecha'])): ?><small class="error"><?=$errors['fecha']?></small><?php endif; ?>
    </label>
    <label>Hora
      <input type="time" name="hora" required value="<?=htmlspecialchars($cita['hora']??'')?>">
      <?php if(!empty($errors['hora'])): ?><small class="error"><?=$errors['hora']?></small><?php endif; ?>
    </label>
  </div>
  <label>Dirección
    <input name="direccion" required value="<?=htmlspecialchars($cita['direccion']??'')?>">
    <?php if(!empty($errors['direccion'])): ?><small class="error"><?=$errors['direccion']?></small><?php endif; ?>
  </label>
  <label>Referencia
    <input name="referencia" value="<?=htmlspecialchars($cita['referencia']??'')?>">
  </label>
  <label>Notas
    <textarea name="notas"><?=htmlspecialchars($cita['notas']??'')?></textarea>
  </label>
  <label>Estado
    <select name="estado">
      <?php foreach (['Pendiente','Confirmada','Cancelada','Completada'] as $e): ?>
        <option <?=$e==($cita['estado']??'Pendiente')?'selected':''?>><?=$e?></option>
      <?php endforeach; ?>
    </select>
  </label>

  <button type="submit">Guardar</button>
  <a class="btn" href="?c=citas&a=index">Volver</a>
</form>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
