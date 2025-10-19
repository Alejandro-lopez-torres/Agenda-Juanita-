<?php
namespace App\Core;

class Helpers {

  public static function ensureSession() {
    if (session_status() === PHP_SESSION_NONE) session_start();
  }

  public static function csrfToken(): string {
    self::ensureSession();
    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
  }

  public static function verifyCsrfOrFail(?string $token) {
    self::ensureSession();
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
      http_response_code(400);
      exit('Token CSRF inválido.');
    }
  }

  public static function sanitize(array $data): array {
    // Trim all strings
    foreach ($data as $k=>$v) if (is_string($v)) $data[$k] = trim($v);
    return $data;
  }

  public static function validateCliente(array $data): array {
    $errors = [];
    if (empty($data['nombre'])) $errors['nombre'] = 'Nombre es obligatorio';
    if (empty($data['apellido'])) $errors['apellido'] = 'Apellido es obligatorio';
    if (empty($data['correo']) || !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) $errors['correo'] = 'Correo inválido';
    if (empty($data['dni'])) $errors['dni'] = 'DNI es obligatorio';
    if (!empty($data['dni']) && !preg_match('/^\d{6,12}$/', $data['dni'])) $errors['dni'] = 'DNI debe ser numérico (6-12)';
    if (!empty($data['telefono']) && !preg_match('/^[0-9\-\+\s]{6,20}$/', $data['telefono'])) $errors['telefono'] = 'Teléfono inválido';
    return $errors;
  }

  public static function validateCita(array $data): array {
    $errors = [];
    if (empty($data['client_id']) || !ctype_digit((string)$data['client_id'])) $errors['client_id'] = 'Seleccione un cliente';
    if (empty($data['asunto'])) $errors['asunto'] = 'Asunto es obligatorio';
    if (empty($data['fecha']) || !preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $data['fecha'])) $errors['fecha'] = 'Fecha inválida';
    if (empty($data['hora']) || !preg_match('/^\d{2}:\d{2}/', $data['hora'])) $errors['hora'] = 'Hora inválida';
    if (empty($data['direccion'])) $errors['direccion'] = 'Dirección es obligatoria';
    if (!empty($data['estado']) && !in_array($data['estado'], ['Pendiente','Confirmada','Cancelada','Completada'])) {
      $errors['estado'] = 'Estado inválido';
    }
    return $errors;
  }

  public static function paginate(int $total, int $page, int $limit): array {
    $pages = max(1, (int)ceil($total / $limit));
    $page = max(1, min($page, $pages));
    return ['pages'=>$pages,'page'=>$page,'limit'=>$limit];
  }

  public static function rangeToDates(?string $range): array {
    // range: hoy | semana | mes
    $hoy = new \DateTimeImmutable('today');
    if ($range === 'hoy') return [$hoy->format('Y-m-d'), $hoy->format('Y-m-d')];
    if ($range === 'semana') {
      $ini = $hoy->modify('monday this week');
      $fin = $ini->modify('+6 days');
      return [$ini->format('Y-m-d'), $fin->format('Y-m-d')];
    }
    if ($range === 'mes') {
      $ini = $hoy->modify('first day of this month');
      $fin = $hoy->modify('last day of this month');
      return [$ini->format('Y-m-d'), $fin->format('Y-m-d')];
    }
    return [null, null];
  }

  public static function json($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }
}
