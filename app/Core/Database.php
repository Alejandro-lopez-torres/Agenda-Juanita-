<?php
 namespace App\Core;

use PDO;
class Database {
  private static ?PDO $pdo = null;
  public static function conn(): PDO {
    if (self::$pdo === null) {
      $cfg = require __DIR__ . '/../../config/config.php';
      $db = $cfg['db'];
      $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
      self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    }
    return self::$pdo;
  }
}
