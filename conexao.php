<?php
define("DSN", "mysql");
define("SERVIDOR", "localhost");
define("USUARIO", "root");
define("SENHA", null);
define("BANCODEDADOS", "mysql");

try {
  $conn = new PDO(DSN . ':host=' . SERVIDOR . ';dbname=' . BANCODEDADOS, USUARIO, SENHA);
} catch (PDOException $e) {
  echo "Erro ao conectar: " . $e->getMessage();
}
?>