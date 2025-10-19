<?php
namespace App\Controllers;

use App\Models\Cliente;
use App\Core\Helpers;

class ClienteController {
  private Cliente $model;
  public function __construct() { $this->model = new Cliente(); }

  public function index() {
    $q = $_GET['q'] ?? null;
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 10; $offset = ($page-1)*$limit;
    $clientes = $this->model->all($q, $limit, $offset);
    $total = $this->model->count($q);
    $pagination = Helpers::paginate($total, $page, $limit);
    include __DIR__ . '/../../views/clientes/index.php';
  }

  public function form() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $cliente = $id ? $this->model->find($id) : null;
    $errors = [];
    include __DIR__ . '/../../views/clientes/form.php';
  }

  public function save() {
    Helpers::verifyCsrfOrFail($_POST['csrf_token'] ?? null);
    $id = $_POST['id'] ?? null;
    $data = Helpers::sanitize($_POST);
    $errors = Helpers::validateCliente($data);
    if ($errors) {
      $cliente = $id ? array_merge($data, ['id'=>$id]) : $data;
      include __DIR__ . '/../../views/clientes/form.php';
      return;
    }
    try {
      if ($id) { $this->model->update((int)$id, $data); }
      else { $id = $this->model->create($data); }
      header("Location: ?c=clientes&a=index&msg=ok");
    } catch (\Throwable $e) {
      $cliente = $id ? array_merge($data, ['id'=>$id]) : $data;
      $errors = ['_global' => $e->getMessage()];
      include __DIR__ . '/../../views/clientes/form.php';
    }
  }

  public function delete() {
    Helpers::verifyCsrfOrFail($_POST['csrf_token'] ?? null);
    $id = (int)($_POST['id'] ?? 0);
    if ($id) $this->model->delete($id);
    header("Location: ?c=clientes&a=index&msg=deleted");
  }

  // Endpoint JSON para autocompletar
  public function search() {
    $q = $_GET['q'] ?? '';
    $items = $this->model->search($q, 10);
    Helpers::json(['items'=>$items]);
  }
}
