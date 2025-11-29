<?php 
session_start();
include 'conexao.php';

if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cabeleireiro'){ 
    header("Location: login_salao.php"); 
    exit; 
}

$id_salao = $_SESSION['id']; // ID do salao (usuario cabeleireiro)
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Dashboard do Cabeleireiro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
  background: #f9f9fb;
  font-family: 'Inter', sans-serif;
  color: #333;
}
.container {
  max-width: 1100px;
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
    <!-- Gráfico de serviços -->
    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Serviços realizados no mês</h5>
        <canvas id="graficoServicos"></canvas>
      </div>
    </div>

    <!-- Gráfico de agendamentos -->
    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Agendamentos do mês</h5>
        <canvas id="graficoAgendamentos"></canvas>
      </div>
    </div>
  </div>

  <div class="card mt-4 p-3">
    <h4>📅 Todos os Agendamentos do Salão</h4>

    <div class="table-responsive mt-3">
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
        $query = "
          SELECT a.id, u.nome AS cliente, s.nome AS salao, h.data, h.hora, a.status 
          FROM agendamentos a 
          JOIN usuarios u ON a.id_usuario=u.id
          JOIN horarios h ON a.id_horario=h.id
          JOIN saloes s ON h.id_salao=s.id
          WHERE s.usuario_id = $id_salao
          ORDER BY h.data, h.hora
        ";

        $res = mysqli_query($conn, $query);

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

<script>
// ID do salão
const idSalao = <?= $id_salao ?>;

// ========================== GRÁFICO DE SERVIÇOS ==========================
fetch(`api/servicos_mes.php?id=${idSalao}`)
  .then(res => res.json())
  .then(dados => {
      const ctx = document.getElementById('graficoServicos');
      new Chart(ctx, {
          type: 'bar',
          data: {
              labels: dados.servicos,
              datasets: [{
                  label: 'Serviços Realizados',
                  data: dados.qtd,
                  backgroundColor: ['#a855f7', '#ec4899', '#38bdf8', '#facc15', '#10b981'],
                  borderRadius: 8
              }]
          },
          options: {
              scales: {
                  y: { beginAtZero: true }
              },
              plugins: {
                  legend: { display: false }
              }
          }
      });
  })
  .catch(err => console.error("Erro ao carregar gráfico de serviços:", err));


// ========================== GRÁFICO DE AGENDAMENTOS ==========================
fetch(`api/agendamentos_mes.php?id=${idSalao}`)
  .then(res => res.json())
  .then(dados => {
      const ctx = document.getElementById('graficoAgendamentos');
      new Chart(ctx, {
          type: 'line',
          data: {
              labels: dados.dias,
              datasets: [{
                  label: 'Agendamentos Confirmados',
                  data: dados.qtd,
                  borderColor: '#f43f5e',
                  backgroundColor: '#fda4af',
                  fill: true,
                  tension: 0.3
              }]
          },
          options: {
              scales: {
                  y: { beginAtZero: true },
                  x: { title: { display: true, text: 'Dia do mês' } }
              }
          }
      });
  })
  .catch(err => console.error("Erro ao carregar gráfico de agendamentos:", err));

</script>

</body>
</html>
