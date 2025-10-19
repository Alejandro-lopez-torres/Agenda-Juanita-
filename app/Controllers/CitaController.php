<?php
namespace App\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Core\Helpers;

class CitaController {
  private Cita $model;
  private Cliente $clientes;
  public function __construct() { $this->model = new Cita(); $this->clientes = new Cliente(); }

  public function index() {
    $q = $_GET['q'] ?? null;
    $estado = $_GET['estado'] ?? null;
    $range = $_GET['range'] ?? null;
    [$desde, $hasta] = Helpers::rangeToDates($range);
    // Overrides direct fechas if provided
    $desde = $_GET['desde'] ?? $desde;
    $hasta = $_GET['hasta'] ?? $hasta;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 10; $offset = ($page-1)*$limit;
    $citas = $this->model->all($q, $desde, $hasta, $estado, $limit, $offset);
    $total = $this->model->count($q, $desde, $hasta, $estado);
    $pagination = Helpers::paginate($total, $page, $limit);
    include __DIR__ . '/../../views/citas/index.php';
  }

  public function form() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $cita = $id ? $this->model->find($id) : null;
    $errors = [];
    include __DIR__ . '/../../views/citas/form.php';
  }

  public function save() {
    Helpers::verifyCsrfOrFail($_POST['csrf_token'] ?? null);
    $id = $_POST['id'] ?? null;
    $data = Helpers::sanitize($_POST);
    $errors = Helpers::validateCita($data);

    // validar choque de citas
    $overlap = $this->model->hasOverlap((int)$data['client_id'], $data['fecha'], $data['hora'], (int)($id ?? 0));
    if ($overlap) $errors['hora'] = 'El cliente ya tiene una cita a esa hora.';

    if ($errors) {
      $cita = $id ? array_merge($data, ['id'=>$id]) : $data;
      include __DIR__ . '/../../views/citas/form.php';
      return;
    }
    try {
      if ($id) { $this->model->update((int)$id, $data); }
      else { $id = $this->model->create($data); }
      header("Location: ?c=citas&a=index&msg=ok");
    } catch (\Throwable $e) {
      $cita = $id ? array_merge($data, ['id'=>$id]) : $data;
      $errors = ['_global' => $e->getMessage()];
      include __DIR__ . '/../../views/citas/form.php';
    }
  }

  public function delete() {
    Helpers::verifyCsrfOrFail($_POST['csrf_token'] ?? null);
    $id = (int)($_POST['id'] ?? 0);
    if ($id) $this->model->delete($id);
    header("Location: ?c=citas&a=index&msg=deleted");
  }
}
