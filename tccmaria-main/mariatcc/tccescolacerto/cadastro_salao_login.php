<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Dados do salão ---
    $nome_salao   = mysqli_real_escape_string($conn, $_POST['nome_salao']);
    $endereco     = mysqli_real_escape_string($conn, $_POST['endereco']);
    $telefone     = mysqli_real_escape_string($conn, $_POST['telefone']);
    $servicos     = isset($_POST['servicos']) ? implode(', ', $_POST['servicos']) : '';

    // --- Horário de atendimento ---
    $horario_inicio = $_POST['horario_inicio'];
    $horario_final  = $_POST['horario_final'];
    $pausa          = $_POST['pausa'];

    // --- Conta do responsável ---
    $nome_usuario = mysqli_real_escape_string($conn, $_POST['nome_usuario']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $senha        = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verifica se o e-mail já existe
    $check_email = mysqli_query($conn, "SELECT id FROM usuarios WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $erro = "❌ Este e-mail já está cadastrado. Faça login ou use outro e-mail.";
    } else {
        // Cria o usuário cabeleireiro
        $query_usuario = "INSERT INTO usuarios (nome, email, senha, tipo)
                          VALUES ('$nome_usuario', '$email', '$senha', 'cabeleireiro')";
        mysqli_query($conn, $query_usuario);
        $id_cabeleireiro = mysqli_insert_id($conn);

        // Cria o salão vinculado
        $query_salao = "INSERT INTO saloes (nome, endereco, telefone, id_cabeleireiro)
                        VALUES ('$nome_salao', '$endereco', '$telefone', '$id_cabeleireiro')";
        mysqli_query($conn, $query_salao);

        header("Location: dashboard_salao.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Salão</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Fontes -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
:root {
  --brand: #111827;
  --accent: #6C5CE7;
}

body {
  height: 100%;
  margin: 0;
  font-family: "Inter", sans-serif;
  background:
    radial-gradient(60% 60% at 0% 0%, rgba(250,146,196,0.65) 0%, rgba(250,146,196,0.1) 200%),   /* #FA92C4 */
    radial-gradient(60% 60% at 100% 0%, rgba(189,169,223,0.6) 0%, rgba(189,169,223,0.1) 200%), /* #BDA9DF */
    radial-gradient(80% 80% at 50% 100%, rgba(161,202,224,0.7) 0%, rgba(161,202,224,0.15) 200%), /* #A1CAE0 */
    #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ====== CARD ====== */
.card-cadastro {
  width: 100%;
  max-width: 600px;
  background: #fff;
  border-radius: 1rem;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease;
}

.card-cadastro:hover {
  transform: translateY(-3px);
}

/* ====== TÍTULOS ====== */
h2 {
  font-family: "Playfair Display", serif;
  font-weight: 900;
  font-size: 2.2rem;
  text-align: center;
  color: var(--brand);
  margin-bottom: 1.8rem;
}

h3 {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--accent);
  margin-top: 1.5rem;
  margin-bottom: 0.8rem;
}

/* ====== CAMPOS ====== */
input, select {
  width: 100%;
  padding: 0.8rem 1rem;
  margin-bottom: 0.8rem;
  border-radius: 0.6rem;
  border: 1px solid #ddd;
  background: #fff;
  transition: all 0.2s ease;
  font-size: 1rem;
}

input:focus, select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(108,92,231,0.25);
  outline: none;
}

/* ====== CHECKBOX ====== */
label {
  display: block;
  text-align: left;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

/* ====== BOTÃO ====== */
button {
  width: 100%;
  padding: 0.9rem 1rem;
  border: none;
  border-radius: 50px;
  font-weight: 700;
  color: white;
  background: linear-gradient(135deg, #6C5CE7, #A29BFE);
  box-shadow: 0 6px 18px rgba(108,92,231,.35);
  transition: all 0.2s ease;
  margin-top: 1rem;
}

button:hover {
  filter: brightness(1.05);
  transform: translateY(-2px);
  box-shadow: 0 10px 26px rgba(108,92,231,.45);
}

/* ====== ERRO ====== */
.erro {
  color: #b91c1c;
  font-weight: 500;
  margin-top: 1rem;
  background: #fee2e2;
  border-radius: 0.6rem;
  padding: 0.6rem;
  text-align: center;
}
/* ====== HEADER COM LOGO E TÍTULO ====== */
.header-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.8rem;
  margin-bottom: 1.5rem;
}

.header-logo img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.header-logo img:hover {
  transform: rotate(10deg) scale(1.05);
}

.header-logo h2 {
  font-family: "Playfair Display", serif;
  font-weight: 900;
  font-size: 1.9rem;
  color: var(--brand);
  margin: 0;
}

    </style>
</head>
<body>

    <div class="card-cadastro">
       <div class="header-logo">
    <img src="logo.png" alt="Logo do Salão">
    <h2>Cadastro de Salão</h2>
</div>


        <form method="POST" action="cadastro_salao.php">
            <h3>Informações do Salão</h3>
            <input type="text" name="nome_salao" placeholder="Nome do Salão" required>
            <input type="text" name="endereco" placeholder="Endereço" required>
            <input type="text" name="telefone" placeholder="Telefone" required>

            <h3>Serviços Oferecidos</h3>
            <label><input type="checkbox" name="servicos[]" value="Corte"> Corte</label>
            <label><input type="checkbox" name="servicos[]" value="Coloração"> Coloração</label>
            <label><input type="checkbox" name="servicos[]" value="Escova"> Escova</label>
            <label><input type="checkbox" name="servicos[]" value="Tratamentos Capilares"> Tratamentos Capilares</label>

            <h3>Horário de Atendimento</h3>
            <div class="row">
                <div class="col-md-4">
                    <label>Início:</label>
                    <input type="time" name="horario_inicio" required>
                </div>
                <div class="col-md-4">
                    <label>Fim:</label>
                    <input type="time" name="horario_final" required>
                </div>
                <div class="col-md-4">
                    <label>Pausa(1 hora):</label>
                    <input type="time" name="pausa">
                </div>
            </div>

            <h3>Conta do Responsável pelo Salão</h3>
            <input type="text" name="nome_usuario" placeholder=" Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>

            <button type="submit">Cadastrar Salão e Conta</button>

            <?php if (isset($erro)): ?>
                <div class="erro"><?= $erro; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
