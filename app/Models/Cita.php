<?php
namespace App\Models;


use App\Core\Database;
class Cita extends BaseModel {

  public function all(?string $q = null, ?string $desde = null, ?string $hasta = null, ?string $estado=null, int $limit=20, int $offset=0): array {
    $where = [];
    $params = [];

    if ($q) {
      $where[] = "(c.asunto LIKE :q OR cli.nombre LIKE :q OR cli.apellido LIKE :q OR cli.dni LIKE :q)";
      $params[':q'] = "%$q%";
    }
    if ($desde) { $where[] = "c.fecha >= :d"; $params[':d'] = $desde; }
    if ($hasta) { $where[] = "c.fecha <= :h"; $params[':h'] = $hasta; }
    if ($estado) { $where[] = "c.estado = :e"; $params[':e'] = $estado; }

    $sql = "SELECT c.*, CONCAT(cli.nombre,' ',cli.apellido) AS cliente
            FROM citas c
            JOIN clientes cli ON cli.id = c.client_id";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY c.fecha DESC, c.hora DESC LIMIT :o, :l";

    $st = $this->db->prepare($sql);
    foreach ($params as $k=>$v) $st->bindValue($k, $v);
    $st->bindValue(':o', $offset, \PDO::PARAM_INT);
    $st->bindValue(':l', $limit, \PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
  }

  public function count(?string $q = null, ?string $desde = null, ?string $hasta = null, ?string $estado=null): int {
    $where = [];
    $params = [];

    if ($q) {
      $where[] = "(c.asunto LIKE :q OR cli.nombre LIKE :q OR cli.apellido LIKE :q OR cli.dni LIKE :q)";
      $params[':q'] = "%$q%";
    }
    if ($desde) { $where[] = "c.fecha >= :d"; $params[':d'] = $desde; }
    if ($hasta) { $where[] = "c.fecha <= :h"; $params[':h'] = $hasta; }
    if ($estado) { $where[] = "c.estado = :e"; $params[':e'] = $estado; }

    $sql = "SELECT COUNT(*) c FROM citas c
            JOIN clientes cli ON cli.id = c.client_id";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);

    $st = $this->db->prepare($sql);
    $st->execute($params);
    return (int)($st->fetch()['c'] ?? 0);
  }

  public function find(int $id): ?array {
    $st = $this->db->prepare("SELECT * FROM citas WHERE id=?");
    $st->execute([$id]);
    return $st->fetch() ?: null;
  }

  public function hasOverlap(int $client_id, string $fecha, string $hora, int $excludeId = 0): bool {
    // Simple check: same client & date & same hour
    $sql = "SELECT COUNT(*) c FROM citas WHERE client_id=? AND fecha=? AND hora=?";
    $params = [$client_id, $fecha, $hora];
    if ($excludeId > 0) {
      $sql .= " AND id <> ?";
      $params[] = $excludeId;
    }
    $st = $this->db->prepare($sql);
    $st->execute($params);
    return ((int)$st->fetch()['c']) > 0;
  }

  public function create(array $data): int {
    $st = $this->db->prepare("INSERT INTO citas
      (client_id, asunto, fecha, hora, direccion, referencia, notas, estado)
      VALUES (?,?,?,?,?,?,?,?)");
    $st->execute([
      (int)$data['client_id'], trim($data['asunto']),
      $data['fecha'], $data['hora'],
      trim($data['direccion']), $data['referencia'] ?? null,
      $data['notas'] ?? null, $data['estado'] ?? 'Pendiente'
    ]);
    return (int)$this->db->lastInsertId();
  }

  public function update(int $id, array $data): bool {
    $st = $this->db->prepare("UPDATE citas SET
      client_id=?, asunto=?, fecha=?, hora=?, direccion=?, referencia=?, notas=?, estado=?
      WHERE id=?");
    return $st->execute([
      (int)$data['client_id'], trim($data['asunto']),
      $data['fecha'], $data['hora'],
      trim($data['direccion']), $data['referencia'] ?? null,
      $data['notas'] ?? null, $data['estado'] ?? 'Pendiente',
      $id
    ]);
  }

  public function delete(int $id): bool {
    $st = $this->db->prepare("DELETE FROM citas WHERE id=?");
    return $st->execute([$id]);
  }
}
