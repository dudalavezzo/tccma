<?php 
session_start();
include 'conexao.php';

if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cabeleireiro'){ 
    header("Location: login_salao.php"); 
    exit; 
}

$id_usuario = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dashboard do Cabeleireiro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  background: #f9f9fb;
  font-family: 'Inter', sans-serif;
  color: #333;
}
.container {
  max-width: 1000px;
  margin-top: 40px;
}
.card {
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
</style>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">💇‍♀️ Dashboard do Cabeleireiro</h2>
    <div>
      <a href="logout.php" class="btn btn-outline-secondary btn-sm">Sair</a>
      <a href="cadastro_salao_login.php" class="btn btn-outline-primary btn-sm">Cadastrar outro salão</a>
    </div>
  </div>

  <div class="row g-3">

    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Serviços realizados no mês</h5>
        <canvas id="graficoServicos"></canvas>
      </div>
    </div>


    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Agendamentos do mês</h5>
        <canvas id="graficoAgendamentos"></canvas>
      </div>
    </div>
  </div>

  <div class="card mt-4 p-3">
    <h4>📅 Todos os Agendamentos do Salão</h4>
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Salão</th>
            <th>Cliente</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $res = mysqli_query($conn, "SELECT a.id, u.nome AS cliente, s.nome AS salao, h.data, h.hora, a.status 
          FROM agendamentos a 
          JOIN usuarios u ON a.id_usuario=u.id
          JOIN horarios h ON a.id_horario=h.id
          JOIN saloes s ON h.id_salao=s.id
          where s.id = $_SESSION[id_salao]
          ORDER BY h.data,h.hora");
        
        if (mysqli_num_rows($res) == 0) {
            echo "<tr><td colspan='5' class='text-center text-muted'>Nenhum agendamento encontrado.</td></tr>";
        } else {
            while($row = mysqli_fetch_assoc($res)){
                echo "<tr>
                        <td>".htmlspecialchars($row['salao'])."</td>
                        <td>".htmlspecialchars($row['cliente'])."</td>
                        <td>".htmlspecialchars($row['data'])."</td>
                        <td>".htmlspecialchars(substr($row['hora'],0,5))."</td>
                        <td><span class='badge ".($row['status']=='agendado'?'bg-success':'bg-danger')."'>".$row['status']."</span></td>
                      </tr>";
            }
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>


<?php 
$url_base =  $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];

    $json = file_get_contents($url_base."/tccmaria/graficos/servicos_mes.php?id=".$_SESSION['id_salao']);
    $dados =json_decode($json, true); 
   
   
    echo "const valores_grafico =". json_encode($dados["servicos"],JSON_UNESCAPED_UNICODE);
    echo "\n";
    echo "const qtd_grafico =".json_encode($dados["qtd"]);
    
    
      
?>

const ctxServicos = document.getElementById('graficoServicos');
new Chart(ctxServicos, {
  type: 'bar',
  data: {
    labels: valores_grafico,
    datasets: [{
      label: 'Serviços Realizados',
      data: qtd_grafico,
      borderRadius: 8
    }]
  }, options: {
    scales: {
      y: { beginAtZero: true, title: { display: true, text: 'Quantidade' } }
    },
    plugins: {
      legend: { display: false }
    }
  }
  
});


const ctxAg = document.getElementById('graficoAgendamentos');
new Chart(ctxAg, {
  type: 'line',
  data: {
    labels: ['01', '05', '10', '15', '20', '25', '30'],
    datasets: [{
      label: 'Agendamentos Confirmados',
      data: [3, 6, 5, 9, 8, 10, 12],
      borderColor: '#f43f5e',
      backgroundColor: '#fda4af',
      fill: true,
      tension: 0.3
    }]
  },
  options: {
    scales: {
      y: { beginAtZero: true, title: { display: true, text: 'Agendamentos' } },
      x: { title: { display: true, text: 'Dia do mês' } }
    }
  }
});
</script>
</body>
</html>