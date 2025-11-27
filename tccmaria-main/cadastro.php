<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES ('$nome','$email','$senha','$tipo')";
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php");
        exit;
    } else {
        echo "Erro: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>

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
  height: 100vh;
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
  max-width: 420px;
  background: #fff;
  border-radius: 1rem;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: transform 0.2s ease;
}

.card-cadastro:hover {
  transform: translateY(-3px);
}

/* ====== TÍTULO ====== */
h2 {
  font-family: "Playfair Display", serif;
  font-weight: 900;
  font-size: 2.2rem;
  color: var(--brand);
  margin-bottom: 1.8rem;
}

/* ====== CAMPOS ====== */
input, select {
  width: 100%;
  padding: 0.9rem 1rem;
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
}

button:hover {
  filter: brightness(1.05);
  transform: translateY(-2px);
  box-shadow: 0 10px 26px rgba(108,92,231,.45);
}

/* ====== LINK LOGIN ====== */
p {
  margin-top: 1.5rem;
  font-size: 0.95rem;
  color: #444;
}

p a {
  color: var(--accent);
  text-decoration: none;
  font-weight: 600;
}

p a:hover {
  text-decoration: underline;
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
    <h2>Cadastro</h2>
</div>

        <form method="POST">
            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>

            <select name="tipo" required>
                <option value="" disabled selected>Selecione o tipo de usuário</option>
                <option value="cliente">Cliente</option>
                <option value="cabeleireiro">Cabeleireiro</option>
            </select>

            <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php">Entrar</a></p>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
