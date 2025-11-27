<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente') {
    die("Acesso negado. Faça login como cliente.");
}

$id_cliente = $_SESSION['id'];

// ===================== 1️⃣ CLIENTE CONFIRMOU O AGENDAMENTO =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data']) && isset($_POST['hora'])) {

    $id_salao = $_POST['salao'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $servico = mysqli_real_escape_string($conn, $_POST['servico']);
    $pagamento = mysqli_real_escape_string($conn, $_POST['pagamento']);

    // Verifica se já existe horário cadastrado e livre para o salão
    $resHorario = mysqli_query($conn, "SELECT id FROM horarios 
        WHERE id_salao = '$id_salao' AND data = '$data' AND hora = '$hora' AND disponivel = 1");
    $horario = mysqli_fetch_assoc($resHorario);

    // Se não existir o horário ainda, cria
    if (!$horario) {
        mysqli_query($conn, "INSERT INTO horarios (id_salao, data, hora, disponivel) VALUES ('$id_salao', '$data', '$hora', 1)");
        $id_horario = mysqli_insert_id($conn);
    } else {
        $id_horario = $horario['id'];
    }

    // Verifica se já tem agendamento neste horário
    $verifica = mysqli_query($conn, "SELECT id FROM agendamentos WHERE id_horario = '$id_horario' AND status = 'agendado'");
    if (mysqli_num_rows($verifica) > 0) {
        die("<p>❌ Esse horário acabou de ser agendado por outra pessoa. Escolha outro horário.</p>");
    }

    // Insere o agendamento
    mysqli_query($conn, "INSERT INTO agendamentos (id_usuario, id_horario, id_servico, status)
                         VALUES ('$id_cliente', '$id_horario', 
                                 (SELECT id FROM servicos WHERE nome='$servico' LIMIT 1),
                                 'agendado')");

    // Marca o horário como ocupado
    mysqli_query($conn, "UPDATE horarios SET disponivel=0 WHERE id='$id_horario'");
    header("Location: dashboard_cliente.php?sucesso=1");
    exit;

    echo "<h3>✅ Agendamento confirmado!</h3>";
    echo "<p><strong>Data:</strong> " . date('d/m/Y', strtotime($data)) . " às " . substr($hora, 0, 5) . "</p>";
    echo "<p><strong>Serviço:</strong> $servico</p>";
    echo "<p><strong>Pagamento:</strong> $pagamento</p>";
    echo '<a href="dashboard_cliente.php">Voltar à Dashboard</a>';
    exit;
}

// ===================== 2️⃣ SELECIONA O SALÃO =====================
if (!isset($_GET['salao'])) {
    die("Nenhum salão selecionado.");
}

$id_salao = (int)$_GET['salao'];
$salao = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM saloes WHERE id = $id_salao"));
if (!$salao) {
    die("Salão não encontrado.");
}

// ===================== 3️⃣ SELECIONA DATA =====================
$data_selecionada = isset($_GET['data']) ? $_GET['data'] : null;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Agendar - <?= htmlspecialchars($salao['nome']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: #f9fafb;
  font-family: 'Inter', sans-serif;
}
.container {
  max-width: 700px;
  background: #fff;
  border-radius: 12px;
  padding: 25px;
  margin-top: 40px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h2 { text-align: center; color: #333; margin-bottom: 30px; }
select, input[type=date], button { width: 100%; margin-bottom: 15px; }
</style>
</head>
<body>
<div class="container">
<h2>Agendar no salão <?= htmlspecialchars($salao['nome']) ?></h2>

<!-- ===================== FORM DE ESCOLHA DE DATA ===================== -->
<form method="GET" action="agendar.php">
    <input type="hidden" name="salao" value="<?= $id_salao ?>">
    <label for="data"><strong>Selecione o dia desejado:</strong></label>
    <input type="date" name="data" id="data" min="<?= date('Y-m-d') ?>" required value="<?= $data_selecionada ?>">
    <button type="submit" class="btn btn-primary">Ver horários disponíveis</button>
</form>

<?php
// ===================== 4️⃣ SE A DATA FOI ESCOLHIDA =====================
if ($data_selecionada) {
    $inicio = strtotime($salao['horario_inicio']);
    $fim = strtotime($salao['horario_final']);
    $pausa = $salao['pausa'] ? strtotime($salao['pausa']) : null;

    echo "<h4>Horários disponíveis para " . date('d/m/Y', strtotime($data_selecionada)) . "</h4>";

    // Gera todos os horários possíveis conforme o expediente
    $horarios_disponiveis = [];
    for ($hora = $inicio; $hora <= $fim; $hora += 1800) { // 30 em 30 min
        if ($pausa && ($hora >= $pausa && $hora <= $pausa + 3600)) continue; // pula pausa
        $hora_formatada = date('H:i:s', $hora);

        // Verifica se já tem agendamento nesse horário
        $check = mysqli_query($conn, "
            SELECT a.id FROM agendamentos a
            JOIN horarios h ON a.id_horario = h.id
            WHERE h.id_salao = $id_salao
              AND h.data = '$data_selecionada'
              AND h.hora = '$hora_formatada'
              AND a.status = 'agendado'
        ");
        if (mysqli_num_rows($check) == 0) {
            $horarios_disponiveis[] = $hora_formatada;
        }
    }

    if (count($horarios_disponiveis) == 0) {
        echo "<p>Nenhum horário disponível neste dia.</p>";
    } else {
        // Serviços do salão
        $servicos = !empty($salao['servicos']) ? array_map('trim', explode(',', $salao['servicos'])) : ['Corte', 'Coloração', 'Escova', 'Tratamentos Capilares'];

        echo '<form method="POST" action="agendar.php">';
        echo '<input type="hidden" name="salao" value="'.$id_salao.'">';
        echo '<input type="hidden" name="data" value="'.$data_selecionada.'">';

        echo '<label for="hora"><strong>Selecione o horário:</strong></label>';
        echo '<select name="hora" required>';
        echo '<option value="">-- Escolher horário --</option>';
        foreach ($horarios_disponiveis as $h) {
            echo '<option value="'.$h.'">'.substr($h, 0, 5).'</option>';
        }
        echo '</select>';

        echo '<label for="servico"><strong>Serviço desejado:</strong></label>';
        echo '<select name="servico" id="servico" required>';
        echo '<option value="">-- Escolher serviço --</option>';
        foreach ($servicos as $s) {
            echo '<option value="'.htmlspecialchars($s).'">'.htmlspecialchars($s).'</option>';
        }
        echo '</select>';

        echo '<label for="pagamento"><strong>Forma de pagamento:</strong></label>';
        echo '<select name="pagamento" required>';
        echo '<option value="">-- Escolher --</option>';
        echo '<option value="Dinheiro">Dinheiro</option>';
        echo '<option value="Pix">Pix</option>';
        echo '<option value="Cartão de Crédito">Cartão de Crédito</option>';
        echo '<option value="Cartão de Débito">Cartão de Débito</option>';
        echo '</select>';

        echo '<button type="submit" class="btn btn-success">Confirmar Agendamento</button>';
        echo '</form>';
    }
}
?>
</div>
</body>
</html>