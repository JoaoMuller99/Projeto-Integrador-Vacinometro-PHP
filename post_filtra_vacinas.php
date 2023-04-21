<?php
include_once "conexao.php";

if (isset($_POST) && !empty($_POST)) {
  $query = $conn->query("SELECT vacina_nome, vacina_descricao_dose FROM projeto.vacinas WHERE estabelecimento_municipio_nome = '" . $_POST["cidade"] . "' AND (vacina_descricao_dose = '1ª Dose' OR vacina_descricao_dose = '2ª Dose')");
  $registro["doses"] = $query->fetchAll();
  echo json_encode($registro["doses"]);
}
?>