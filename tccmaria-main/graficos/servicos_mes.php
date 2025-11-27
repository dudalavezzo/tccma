
<?php 


header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
include "../conexao.php";
$metodoSolicitado = $_SERVER['REQUEST_METHOD'];


$id     = $_GET['id'] ?? null;

switch($metodoSolicitado){
    case "POST":
        break;
    case "GET": 
        $conexao = $conn;

        $sql = "Select  
            a.id_servico,
		    svc.nome, 
            count(a.id_servico)as qtd_serv 
        from agendamentos a 
        inner join horarios h   on (h.id = a.id_horario )
        inner join saloes s     on (h.id_salao = s.id)
        inner join servicos svc on (svc.id = a.id_servico)
        where s.id = $id group by a.id_servico;
        ";


        $resultado = $conexao->query($sql);
        $retorno = [];
        $retorno["servicos"] = [];
        $retorno["qtd"] = [];
        while ($linha = $resultado->fetch_assoc()) {
           array_push($retorno["servicos"],$linha["nome"]);
           array_push($retorno["qtd"], intval($linha["qtd_serv"]));
        }

        

        echo json_encode($retorno);



        break;    
}


?>