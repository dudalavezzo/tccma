<?php 
// dashboard_cliente.php
session_start();
$id_usuario = $_SESSION['id'] ?? 1;

$conn = new mysqli("localhost", "root", "", "agendamento");
if ($conn->connect_error) { die("Erro DB"); }

// buscar agendamentos do usuário
$sql = "SELECT a.id, s.nome AS salao, h.data, h.hora, a.status
        FROM agendamentos a
        JOIN horarios h ON a.id_horario = h.id
        JOIN saloes s ON h.id_salao = s.id
        WHERE a.id_usuario = ?
        ORDER BY h.data DESC, h.hora DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$agendamentos = $res->fetch_all(MYSQLI_ASSOC);

// buscar saloes diretamente do banco
$saloes = [];
$resS = $conn->query("SELECT id, nome, endereco, lat, lng FROM saloes ORDER BY nome ASC");
if ($resS) $saloes = $resS->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Dashboard Cliente - Encontrar Salões</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background: linear-gradient(135deg,#ffd6f6,#ffeef8); }
    #map { height: 420px; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.1); }
    .card-salao { border-radius: 12px; }
    .btn-gradient { background: linear-gradient(90deg,#c44bff,#ff60c0); color: #fff; border: none; }
    .chart-container { height: 280px; }
  </style>
</head>
<body class="p-3">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">🎀 Dashboard do Cliente</h3>
      <a href="logout.php" class="btn btn-sm btn-light">Sair</a>
    </div>

    <div class="row g-3">
      <!-- MAPA -->
      <div class="col-lg-8">
        <div class="card card-salao p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <strong>Mapa de Salões Próximos</strong><br>
              <small>Permita o uso da localização quando o navegador pedir.</small>
            </div>
            <div>
              <button id="btn-localizar" class="btn btn-sm btn-outline-dark">Localizar-me</button>
            </div>
          </div>
          <div id="map"></div>
        </div>

        <!-- Gráficos -->
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card p-3">
              <h6 class="text-center">Serviços realizados por tipo</h6>
              <div class="chart-container"><canvas id="graficoServicos"></canvas></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card p-3">
              <h6 class="text-center">Meus agendamentos no mês</h6>
              <div class="chart-container"><canvas id="graficoAgendamentos"></canvas></div>
            </div>
          </div>
        </div>

        <div class="card p-3 mt-3">
          <h5>Salões cadastrados</h5>
          <div id="lista-saloes" class="list-group">
            <?php if(empty($saloes)): ?>
              <p class="small">Nenhum salão cadastrado.</p>
            <?php else: ?>
              <?php foreach($saloes as $s): ?>
                <div class="list-group-item d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-bold"><?=htmlspecialchars($s['nome'])?></div>
                    <div class="small text-muted"><?=htmlspecialchars($s['endereco'] ?? '')?></div>
                  </div>
                  <div class="text-end">
                    <a href="agendar.php?salao_id=<?=urlencode($s['id'])?>" class="btn btn-sm btn-gradient">Agendar</a>
                    <?php if(!empty($s['lat']) && !empty($s['lng'])): ?>
                      <button class="btn btn-outline-secondary btn-sm ms-2" onclick="zoomTo(<?=htmlspecialchars($s['lat'])?>, <?=htmlspecialchars($s['lng'])?>)">Ver</button>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- LADO DIREITO -->
      <div class="col-lg-4">
        <div class="card p-3 mb-3">
          <h5>💅 Meus Agendamentos</h5>
          <?php if(empty($agendamentos)): ?>
            <p class="small">Você não tem agendamentos.</p>
          <?php else: ?>
            <ul class="list-group">
              <?php foreach($agendamentos as $ag): ?>
                <li class="list-group-item">
                  <strong><?=htmlspecialchars($ag['salao'])?></strong><br>
                  <?=htmlspecialchars($ag['data'])?> às <?=htmlspecialchars($ag['hora'])?><br>
                  <small>Status: <?=htmlspecialchars($ag['status'])?></small>
                </li>
              <?php endforeach;?>
            </ul>
          <?php endif; ?>
        </div>

        <div class="card p-3">
          <h6>Dicas</h6>
          <ul>
            <li>Permita a localização para visualizar distâncias no mapa.</li>
            <li>Salões com localização definida aparecem com marcadores.</li>
            <li>Agende seus horários com antecedência.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // === MAPA ===
    const map = L.map('map').setView([-22.3145, -49.0602], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);

    function zoomTo(lat, lng) { map.setView([lat, lng], 16); }

    function localizarUsuario() {
      if (!navigator.geolocation) { alert('Geolocalização não suportada'); return; }
      navigator.geolocation.getCurrentPosition(pos => {
        const lat = pos.coords.latitude, lng = pos.coords.longitude;
        const marker = L.circleMarker([lat, lng], { radius: 8, color: '#ff60c0', fillColor: '#ff9ad3', fillOpacity: 0.9 })
          .addTo(map).bindPopup('Você está aqui').openPopup();
        map.setView([lat, lng], 14);
      }, err => alert('Não foi possível obter sua localização: ' + err.message), { enableHighAccuracy:true });
    }
    document.getElementById('btn-localizar').addEventListener('click', localizarUsuario);

    <?php foreach($saloes as $s): 
      if(!empty($s['lat']) && !empty($s['lng'])): ?>
        L.marker([<?= $s['lat'] ?>, <?= $s['lng'] ?>])
          .addTo(map)
          .bindPopup("<strong><?= addslashes($s['nome']) ?></strong><br><?= addslashes($s['endereco']) ?>");
    <?php endif; endforeach; ?>

    // === GRÁFICOS FICTÍCIOS ===
    new Chart(document.getElementById('graficoServicos'), {
      type: 'bar',
      data: {
        labels: ['Corte', 'Coloração', 'Escova', 'Penteado'],
        datasets: [{
          label: 'Serviços realizados',
          data: [4, 2, 3, 1],
          backgroundColor: ['#c084fc', '#f472b6', '#60a5fa', '#34d399'],
          borderRadius: 6
        }]
      },
      options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('graficoAgendamentos'), {
      type: 'line',
      data: {
        labels: ['01', '05', '10', '15', '20', '25', '30'],
        datasets: [{
          label: 'Agendamentos',
          data: [1, 2, 1, 3, 2, 4, 5],
          borderColor: '#ec4899',
          backgroundColor: '#f9a8d4',
          fill: true,
          tension: 0.3
        }]
      },
      options: { scales: { y: { beginAtZero: true } } }
    });
  </script>
</body>
</html>
