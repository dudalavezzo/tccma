<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

include "../conexao.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(["erro" => "ID do salão não informado"]);
    exit;
}

// mês atual
$mes_atual = date("m");
$ano_atual = date("Y");

$sql = "
    SELECT 
        DAY(h.data) AS dia,
        COUNT(a.id) AS total
    FROM agendamentos a
    INNER JOIN horarios h ON h.id = a.id_horario
    WHERE h.id_salao = $id
      AND MONTH(h.data) = $mes_atual
      AND YEAR(h.data) = $ano_atual
    GROUP BY DAY(h.data)
    ORDER BY DAY(h.data)
";

$resultado = $conn->query($sql);

$retorno = [
    "dias" => [],
    "qtd" => []
];

while ($linha = $resultado->fetch_assoc()) {
    $retorno["dias"][] = str_pad($linha["dia"], 2, "0", STR_PAD_LEFT);
    $retorno["qtd"][] = intval($linha["total"]);
}

echo json_encode($retorno);
