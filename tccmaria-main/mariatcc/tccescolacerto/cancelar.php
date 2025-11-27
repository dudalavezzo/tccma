<?php
session_start();
include 'conexao.php';
$id = $_GET['id'];

// Busca horário do agendamento
$res = mysqli_query($conn, "SELECT id_horario FROM agendamentos WHERE id=$id");
$row = mysqli_fetch_assoc($res);
$id_horario = $row['id_horario'];

// Libera horário
mysqli_query($conn, "UPDATE horarios SET disponivel=1 WHERE id=$id_horario");

// Atualiza status
mysqli_query($conn, "UPDATE agendamentos SET status='cancelado' WHERE id=$id");

header("Location: dashboard_cliente.php");
?>
