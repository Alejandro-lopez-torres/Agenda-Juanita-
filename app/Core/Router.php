<?php
namespace App\Core;

class Router {
  public static function dispatch() {
    Helpers::ensureSession();
    $c = $_GET['c'] ?? 'clientes';
    $a = $_GET['a'] ?? 'index';

    // Nombre directo (p.ej. "clientes" -> "ClientesController")
    $candidate = '\\App\\Controllers\\' . ucfirst(rtrim($c, '/')) . 'Controller';

    // Si no existe, intenta singular (quita una 's' final)
    if (!class_exists($candidate)) {
      $maybeSingular = rtrim($c, '/');
      if (substr($maybeSingular, -1) === 's') {
        $maybeSingular = substr($maybeSingular, 0, -1);
      }
      $candidate2 = '\\App\\Controllers\\' . ucfirst($maybeSingular) . 'Controller';
      if (class_exists($candidate2)) {
        $controller = new $candidate2();
      } else {
        http_response_code(404);
        exit('Controlador no encontrado');
      }
    } else {
      $controller = new $candidate();
    }

    if (!method_exists($controller, $a)) {
      http_response_code(404);
      exit('Acción no encontrada');
    }
    return $controller->$a();
  }
}
