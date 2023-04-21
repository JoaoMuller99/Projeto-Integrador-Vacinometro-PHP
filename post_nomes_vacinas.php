<?php
include_once "conexao.php";

if (isset($_POST) && !empty($_POST)) {
  $query = $conn->query("SELECT DISTINCT vacina_nome FROM projeto.vacinas WHERE estabelecimento_municipio_nome = '" . $_POST["cidade"] . "'");
  $registro["vacinas"] = $query->fetchAll();
  echo json_encode($registro["vacinas"]);
}
?>