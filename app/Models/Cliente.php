<?php
namespace App\Models;


use App\Core\Database;

class Cliente extends BaseModel {

  public function all(string $q = null, int $limit = 20, int $offset = 0): array {
    if ($q) {
      $sql = "SELECT * FROM clientes
              WHERE nombre LIKE :q OR apellido LIKE :q OR correo LIKE :q OR dni LIKE :q
              ORDER BY id DESC LIMIT :o, :l";
      $st = $this->db->prepare($sql);
      $like = "%$q%";
      $st->bindValue(':q', $like);
    } else {
      $sql = "SELECT * FROM clientes ORDER BY id DESC LIMIT :o, :l";
      $st = $this->db->prepare($sql);
    }
    $st->bindValue(':o', $offset, \PDO::PARAM_INT);
    $st->bindValue(':l', $limit, \PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
  }

  public function count(string $q = null): int {
    if ($q) {
      $st = $this->db->prepare("SELECT COUNT(*) c FROM clientes
        WHERE nombre LIKE :q OR apellido LIKE :q OR correo LIKE :q OR dni LIKE :q");
      $st->execute([':q' => "%$q%"]);
    } else {
      $st = $this->db->query("SELECT COUNT(*) c FROM clientes");
    }
    return (int)($st->fetch()['c'] ?? 0);
  }

  public function find(int $id): ?array {
    $st = $this->db->prepare("SELECT * FROM clientes WHERE id = ?");
    $st->execute([$id]);
    return $st->fetch() ?: null;
  }

  public function create(array $data): int {
    $st = $this->db->prepare("INSERT INTO clientes (nombre,apellido,correo,dni,telefono)
                              VALUES (?,?,?,?,?)");
    $st->execute([
      trim($data['nombre']), trim($data['apellido']), trim($data['correo']),
      trim($data['dni']), $data['telefono'] ?? null
    ]);
    return (int)$this->db->lastInsertId();
  }

  public function update(int $id, array $data): bool {
    $st = $this->db->prepare("UPDATE clientes
      SET nombre=?, apellido=?, correo=?, dni=?, telefono=? WHERE id=?");
    return $st->execute([
      trim($data['nombre']), trim($data['apellido']), trim($data['correo']),
      trim($data['dni']), $data['telefono'] ?? null, $id
    ]);
  }

  public function delete(int $id): bool {
    $st = $this->db->prepare("DELETE FROM clientes WHERE id=?");
    return $st->execute([$id]);
  }

  public function search(string $q, int $limit = 10): array {
    $st = $this->db->prepare("SELECT id, nombre, apellido, dni, correo
      FROM clientes
      WHERE nombre LIKE :q OR apellido LIKE :q OR correo LIKE :q OR dni LIKE :q
      ORDER BY nombre ASC LIMIT :l");
    $st->bindValue(':q', "%$q%");
    $st->bindValue(':l', $limit, \PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
  }
}
