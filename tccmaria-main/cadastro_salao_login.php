<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // --- Dados do salão ---
  $nome_salao   = mysqli_real_escape_string($conn, $_POST['nome_salao']);
  $endereco     = mysqli_real_escape_string($conn, $_POST['endereco']);
  $telefone     = mysqli_real_escape_string($conn, $_POST['telefone']);
  $servicos     = isset($_POST['servicos']) ? implode(', ', $_POST['servicos']) : '';

  // --- Horário de atendimento ---
  $horario_inicio = $_POST['horario_inicio'] . ":00";
  $horario_final  = $_POST['horario_final'] . ":00";
  $pausa          = $_POST['pausa'] ? $_POST['pausa'] . ":00" : NULL;

  // --- Conta do responsável ---
  $nome_usuario = mysqli_real_escape_string($conn, $_POST['nome_usuario']);
  $email        = mysqli_real_escape_string($conn, $_POST['email']);
  $senha        = password_hash($_POST['senha'], PASSWORD_DEFAULT);

  // Verifica se o email já existe
  $check_email = mysqli_query($conn, "SELECT id FROM usuarios WHERE email='$email'");
  if (mysqli_num_rows($check_email) > 0) {
    $erro = "❌ Este e-mail já está cadastrado. Faça login ou use outro.";
  } else {

    // Cria usuário cabeleireiro
    $query_usuario = "INSERT INTO usuarios (nome, email, senha, tipo)
                      VALUES ('$nome_usuario', '$email', '$senha', 'cabeleireiro')";
    mysqli_query($conn, $query_usuario);
    $id_cabeleireiro = mysqli_insert_id($conn);

    // Cria o salão (AGORA SALVANDO OS HORÁRIOS)
    $query_salao = "INSERT INTO saloes 
                    (nome, endereco, telefone, servicos, horario_inicio, horario_final, pausa, id_cabeleireiro)
                    VALUES 
                    ('$nome_salao', '$endereco', '$telefone', '$servicos', '$horario_inicio', '$horario_final', " . 
                    ($pausa ? "'$pausa'" : "NULL") . ", '$id_cabeleireiro')";
                    
    mysqli_query($conn, $query_salao);

    header("Location: dashboard_salao.php");
    exit;
  }
}
?>
