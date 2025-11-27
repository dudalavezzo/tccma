<?php
include 'conexao.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Dados salão
    $nome_salao = mysqli_real_escape_string($conn, $_POST['nome_salao']);
    $endereco   = mysqli_real_escape_string($conn, $_POST['endereco']);
    $telefone   = mysqli_real_escape_string($conn, $_POST['telefone']);

    mysqli_query($conn, "INSERT INTO saloes (nome,endereco,telefone) VALUES ('$nome_salao','$endereco','$telefone')");
    $id_salao = mysqli_insert_id($conn);

    // Dados usuário responsável
    $nome_usuario = mysqli_real_escape_string($conn, $_POST['nome_usuario']);
    $email        = mysqli_real_escape_string($conn, $_POST['email']);
    $senha        = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO usuarios (nome,email,senha,tipo) VALUES ('$nome_usuario','$email','$senha','cabeleireiro')");

    $sucesso = true;
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
  max-width: 500px;
  background: #fff;
  border-radius: 1rem;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease;
  text-align: center;
}

.card-cadastro:hover {
  transform: translateY(-3px);
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
}

.header-logo h2 {
  font-family: "Playfair Display", serif;
  font-weight: 900;
  font-size: 1.9rem;
  color: var(--brand);
  margin: 0;
}

/* ====== SUBTÍTULOS ====== */
h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--accent);
  margin-top: 1.5rem;
  margin-bottom: 0.8rem;
}

/* ====== CAMPOS ====== */
input {
  width: 100%;
  padding: 0.9rem 1rem;
  margin-bottom: 0.8rem;
  border-radius: 0.6rem;
  border: 1px solid #ddd;
  background: #fff;
  transition: all 0.2s ease;
  font-size: 1rem;
}

input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(108,92,231,0.25);
  outline: none;
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

/* ====== ALERTAS ====== */
.alerta-sucesso {
  background: #ecfdf5;
  color: #065f46;
  border-radius: 0.6rem;
  padding: 0.8rem;
  margin-top: 1rem;
  border: 1px solid #a7f3d0;
}

a {
  color: var(--accent);
  text-decoration: none;
  font-weight: 600;
}
a:hover {
  text-decoration: underline;
}
    </style>
</head>
<body>

    <div class="card-cadastro">
        <div class="header-logo">
            <img src="logo.png" alt="Logo do Salão">
            <h2>Cadastro de Salão</h2>
        </div>

        <?php if (isset($sucesso)): ?>
            <div class="alerta-sucesso">
                ✅ Salão e conta cadastrados com sucesso!<br>
                <a href="login_salao.php">Acessar Login do Salão</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <h3>Informações do Salão</h3>
                <input type="text" name="nome_salao" placeholder="Nome do Salão" required>
                <input type="text" name="endereco" placeholder="Endereço" required>
                <input type="text" name="telefone" placeholder="Telefone" required>

                <h3>Responsável pelo Salão</h3>
                <input type="text" name="nome_usuario" placeholder=" Nome" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>

                <button type="submit">Cadastrar</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
