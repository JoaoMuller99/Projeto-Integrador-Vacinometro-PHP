<?php
$query = $conn->query("SELECT DISTINCT estabelecimento_municipio_nome FROM projeto.vacinas ORDER BY 1");
$registro["cidades"] = $query->fetchAll();
?>