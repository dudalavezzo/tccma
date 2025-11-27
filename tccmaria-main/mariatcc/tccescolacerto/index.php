<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonte moderna -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">

<!-- Tema visual (não altera sua estrutura) -->
 <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
  
  :root{
    --brand:#111827;
    --pill:#0f172a;
    --pill-text:#fff;
    --muted:#6b7280;
    --outline:rgba(0,0,0,.08);
  }

  html, body {
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

  /* Centraliza tudo verticalmente */
  body{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    padding:4rem 1rem;
  }

  /* Título principal com fonte Playfair Display */
  h1:first-of-type{
    font-family:"Playfair Display", serif;
    font-weight:900;
    line-height:1.05;
    letter-spacing:-.02em;
    font-size:clamp(2.5rem, 5vw + 1rem, 4.8rem);
    margin-bottom:1rem;
    color:#0b0b0f;
  }

  /* Subtítulo "Faça seu login!" */
  h1:nth-of-type(2){
  font-weight:400; /* leve, mas ainda legível */
  color:var(--muted);
  font-size:clamp(1.1rem, 0.8vw + 0.5rem, 1.1rem); /* menor e responsivo */
  margin-top:0.2rem;
  margin-bottom:1.8rem;
}

  

  /* Container dos botões */
  .botoes-login{
    display:flex;
    justify-content:center;
    gap:1rem;
    flex-wrap:wrap;
  }

  /* Botões (links) */
  a{
    display:inline-block;
    text-decoration:none;
    padding:.9rem 1.6rem;
    border-radius:999px;
    font-weight:700;
    border:1px solid var(--outline);
    background:#fff;
    color:var(--brand);
    box-shadow:0 6px 20px rgba(0,0,0,.05);
    transition:all .2s ease;
  }
  a:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 28px rgba(0,0,0,.1);
  }
  a:first-of-type{
    background:var(--pill);
    color:var(--pill-text);
    border:none;
  }
  a:first-of-type:hover{
    background:#0b1222;
  }


</style>

    <title>Agendamento Online</title>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<body>
    <h1>Bem-vindo ao Hair Cut</h1>
    <h1>Faça seu login!</h1>

    <a href="login_cliente.php">Sou Cliente</a><br><br>
    <a href="login_salao.php">Sou Salão</a>
</body>
</html>
