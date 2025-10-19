<?php
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Helpers.php';

spl_autoload_register(function($class){
  $prefix = 'App\\';
  $baseDir = __DIR__ . '/../app/'; // carpeta real en minúscula

  // ¿La clase usa el prefijo App\ ?
  $len = strlen($prefix);
  if (strncmp($prefix, $class, $len) !== 0) {
    return; // otra cosa, ignorar
  }

  // ruta relativa (sin el prefijo)
  $relativeClass = substr($class, $len);
  $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

  if (file_exists($file)) {
    require $file;
  }
});

use App\Core\Router;

Router::dispatch();
