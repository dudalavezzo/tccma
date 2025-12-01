<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    die("Acesso negado. Faça login como cliente.");
}

$id_cliente = $_SESSION['id'];


if (isset($_GET['salao_id'])) {
    $id_salao = (int)$_GET['salao_id'];
} elseif (isset($_POST['salao_id'])) {
    $id_salao = (int)$_POST['salao_id'];
} else {
    die("Nenhum salão selecionado.");
}

$salao = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM saloes WHERE id = $id_salao"));
if (!$salao) {
    die("Salão não encontrado.");
}


$data_selecionada = isset($_GET['data']) ? $_GET['data'] : null;


$sucesso = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'], $_POST['hora'], $_POST['servico'], $_POST['pagamento'])) {
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $servico = mysqli_real_escape_string($conn, $_POST['servico']);
    $pagamento = mysqli_real_escape_string($conn, $_POST['pagamento']);


    $resHorario = mysqli_query($conn, "SELECT id FROM horarios 
        WHERE id_salao='$id_salao' AND data='$data' AND hora='$hora'");
    $horario = mysqli_fetch_assoc($resHorario);

    if (!$horario) {
        mysqli_query($conn, "INSERT INTO horarios (id_salao,data,hora,disponivel) VALUES ('$id_salao','$data','$hora',1)");
        $id_horario = mysqli_insert_id($conn);
    } else {
        $id_horario = $horario['id'];
    }


    $check = mysqli_query($conn, "SELECT id FROM agendamentos WHERE id_horario='$id_horario' AND status='agendado'");
    if (mysqli_num_rows($check) > 0) {
        die("<p>❌ Horário já agendado. Escolha outro.</p><a href='agendar.php?salao_id=$id_salao'>Voltar</a>");
    }


    mysqli_query($conn, "INSERT INTO agendamentos (id_usuario,id_horario,id_servico,status)
        VALUES ('$id_cliente','$id_horario',(SELECT id FROM servicos WHERE nome='$servico' LIMIT 1),'agendado')");


    mysqli_query($conn, "UPDATE horarios SET disponivel=0 WHERE id='$id_horario'");

 
    $sucesso = "✅ Agendamento confirmado para " . date('d/m/Y', strtotime($data)) .
               " às " . substr($hora,0,5) . " no salão " . htmlspecialchars($salao['nome']) . ".";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendar - <?= htmlspecialchars($salao['nome']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>

:root{
  --brand:#111827;
  --accent:#6C5CE7;
  --bg1:#B079DC; --bg2:#7A94DB; --bg3:#7A56FF;
}

html,body{height:100%}
body{
  margin:0;
  font-family:"Inter",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
  color:#111827;
  background:
    radial-gradient(60% 60% at 0% 0%, rgba(250,146,196,0.65) 0%, rgba(250,146,196,0.1) 200%),
    radial-gradient(60% 60% at 100% 0%, rgba(189,169,223,0.6) 0%, rgba(189,169,223,0.1) 200%),
    radial-gradient(80% 80% at 50% 100%, rgba(161,202,224,0.7) 0%, rgba(161,202,224,0.15) 200%),
    #ffffff;
  background-attachment:fixed;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:flex-start;
  gap:0;
  padding:32px 16px 48px;
  position:relative;
}


.container{
  position:relative; z-index:1;
  width:min(92vw, 900px);
  max-width:900px;
  background:#fff;
  border-radius:18px;
  padding:28px 22px;
  margin-top:0;
  box-shadow:0 10px 26px rgba(0,0,0,.08);
  border:1px solid #eef0f3;
}


.alerta-sucesso{
  background:#ecfdf5;
  color:#065f46;
  border:1px solid #a7f3d0;
  border-radius:12px;
  padding:14px 16px;
  margin-bottom:18px;
}
.alerta-sucesso a{
  color:#0ea5e9;
  font-weight:700;
  text-decoration:none;
}
.alerta-sucesso a:hover{ text-decoration:underline; }


h2{
  font-family:"Playfair Display", serif;
  font-weight:900;
  font-size:1.9rem;
  letter-spacing:.2px;
  color:var(--brand);
  text-align:center;
  margin:0 0 22px 0;
}
h4{
  font-weight:800;
  color:#363a44;
  margin:14px 0 10px 0;
}


label{ display:block; font-weight:700; color:#374151; margin:8px 0 6px; }

input[type="date"],
select{
  width:100%;
  padding:0.9rem 1rem;
  border:1px solid #e5e7eb;
  border-radius:12px;
  background:#fff;
  font-size:1rem;
  transition:border-color .2s ease, box-shadow .2s ease;
  margin-bottom:12px;
}
input[type="date"]:focus,
select:focus{
  outline:none;
  border-color:#a29bfe;
  box-shadow:0 0 0 4px rgba(162,155,254,.22);
}


.btn{
  display:inline-block;
  width:100%;
  padding:.95rem 1rem;
  border:none;
  border-radius:999px;
  font-weight:800;
  letter-spacing:.2px;
  transition:transform .15s ease, box-shadow .2s ease, filter .15s ease;
}
.btn-primary{
  color:#fff;
  background:linear-gradient(135deg,#6C5CE7 0%,#A29BFE 100%);
  box-shadow:0 10px 26px rgba(108,92,231,.45);
}
.btn-success{
  color:#fff;
  background:linear-gradient(135deg,#22c55e 0%,#86efac 100%);
  box-shadow:0 10px 26px rgba(34,197,94,.30);
}
.btn:hover{
  transform:translateY(-1px);
  filter:brightness(1.04);
}
.btn-primary:hover{
  box-shadow:0 14px 34px rgba(108,92,231,.55), 0 0 18px rgba(162,155,254,.35);
}
.btn-success:hover{
  box-shadow:0 14px 34px rgba(34,197,94,.38), 0 0 18px rgba(134,239,172,.28);
}


p{ color:#4b5563; margin:8px 0; }


@media (max-width:560px){
  .container{ padding:22px 16px; border-radius:16px; }
  h2{ font-size:1.7rem; }
}
</style>
</head>
<body>
<div class="container">

  <?php if (!empty($sucesso)): ?>
    <div class="alerta-sucesso">
      <?= $sucesso ?><br>
      <a href="dashboard_cliente.php">Ir para o Dashboard</a>
    </div>
  <?php endif; ?>

  

  <?php if (empty($sucesso)): ?>
 
    <form method="GET" action="agendar.php">
        <input type="hidden" name="salao_id" value="<?= $id_salao ?>">
        <label for="data"><strong>Selecione o dia desejado:</strong></label>
        <input type="date" name="data" id="data" min="<?= date('Y-m-d') ?>" required value="<?= $data_selecionada ?>">
        <button type="submit" class="btn btn-primary">Ver horários disponíveis</button>
    </form>

    <?php
    if ($data_selecionada) {
        $inicio = strtotime($salao['horario_inicio']);
        $fim = strtotime($salao['horario_final']);
        $pausa = $salao['pausa'] ? strtotime($salao['pausa']) : null;

        echo "<h4>Horários disponíveis para " . date('d/m/Y', strtotime($data_selecionada)) . "</h4>";

        $horarios_disponiveis = [];
        for ($hora = $inicio; $hora <= $fim; $hora += 1800) {
            if ($pausa && ($hora >= $pausa && $hora <= $pausa + 3600)) continue;
            $hora_formatada = date('H:i:s', $hora);

            $check = mysqli_query($conn, "
                SELECT a.id FROM agendamentos a
                JOIN horarios h ON a.id_horario = h.id
                WHERE h.id_salao = $id_salao
                  AND h.data = '$data_selecionada'
                  AND h.hora = '$hora_formatada'
                  AND a.status = 'agendado'
            ");
            if (mysqli_num_rows($check) == 0) $horarios_disponiveis[] = $hora_formatada;
        }

        if (count($horarios_disponiveis) == 0) {
            echo "<p>Nenhum horário disponível neste dia.</p>";
        } else {
            $servicos = !empty($salao['servicos']) ? array_map('trim', explode(',', $salao['servicos'])) : ['Corte','Coloração','Escova','Tratamentos Capilares'];

            echo '<form method="POST" action="agendar.php">';
            echo '<input type="hidden" name="salao_id" value="'.$id_salao.'">';
            echo '<input type="hidden" name="data" value="'.$data_selecionada.'">';

            echo '<label for="hora"><strong>Selecione o horário:</strong></label>';
            echo '<select name="hora" required><option value="">-- Escolher horário --</option>';
            foreach ($horarios_disponiveis as $h) {
                echo '<option value="'.$h.'">'.substr($h,0,5).'</option>';
            }
            echo '</select>';

            echo '<label for="servico"><strong>Serviço desejado:</strong></label>';
            echo '<select name="servico" required><option value="">-- Escolher serviço --</option>';
            foreach ($servicos as $s) {
                echo '<option value="'.htmlspecialchars($s).'">'.htmlspecialchars($s).'</option>';
            }
            echo '</select>';

            echo '<label for="pagamento"><strong>Forma de pagamento:</strong></label>';
            echo '<select name="pagamento" required>
                    <option value="">-- Escolher --</option>
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Pix">Pix</option>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="Cartão de Débito">Cartão de Débito</option>
                  </select>';

            echo '<button type="submit" class="btn btn-success">Confirmar Agendamento</button>';
            echo '</form>';
        }
    }
    ?>
  <?php endif; ?>

</div>
</body>
</html>