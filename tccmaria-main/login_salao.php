<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $result = mysqli_query($conn, "SELECT s.*, sa.id as salaoId FROM usuarios s inner join saloes sa on(sa.usuario_id = s.id) WHERE s.email='$email'");
    //print_r($result);
    $user = mysqli_fetch_assoc($result);
//print_r($user);
//exit;
    if ($user && password_verify($senha, $user['senha']) && $user['tipo'] == 'cabeleireiro') {
        $_SESSION['id'] = $user['id'];
        $_SESSION['tipo'] = $user['tipo'];
        $_SESSION['id_salao'] = $user['salaoId'];
        header("Location: dashboard_cabeleireiro.php");
        exit;
    } else {
        $erro = "Email ou senha incorretos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Salão</title>

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

/* ====== CARD RESTAURADO ====== */
.card-login, .card-cadastro {
  width: 100%;
  max-width: 420px;
  background: #fff;
  border-radius: 1rem;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: transform 0.2s ease;
  z-index: 2;
}

.card-login:hover, .card-cadastro:hover {
  transform: translateY(-3px);
}



  
);


  display: flex;
  align-items: center;
  justify-content: center;
}




/* ====== CARD ====== */
.card-login {
  width: 100%;
  max-width: 420px;
  background: #fff;
  border-radius: 1rem;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: transform 0.2s ease;
}

.card-login:hover {
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
input {
  width: 100%;
  padding: 0.9rem 1rem;
  margin-bottom: 0.8rem;
  border-radius: 0.6rem;
  border: 1px solid #ddd;
  background: #fff;
  transition: all 0.2s ease;
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
  padding: 0.5rem;
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

    <div class="card-login">
       <div class="header-logo">
    <img src="logo.png" alt="Logo do Salão">
    <h2>Login Salão</h2>
</div>

       <form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>

    <?php if(isset($erro)): ?>
        <div class="erro"><?= $erro; ?></div>
    <?php endif; ?>

    <p class="cadastro-link">
        Ainda não tem uma conta? <a href="cadastro_salao_login.php">Cadastre-se</a>
    </p>
</form>


            <?php if(isset($erro)): ?>
                <div class="erro"><?= $erro; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
