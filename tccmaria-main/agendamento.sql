-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/11/2025 às 21:59
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agendamento`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_horario` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `status` enum('agendado','cancelado') DEFAULT 'agendado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`id`, `id_usuario`, `id_horario`, `id_servico`, `status`) VALUES
(1, 27, 3, 4, 'agendado'),
(2, 27, 18, 5, 'agendado'),
(3, 27, 2, 6, 'agendado'),
(4, 4, 11, 3, 'agendado'),
(5, 3, 2, 2, 'agendado'),
(6, 13, 30, 5, 'agendado'),
(7, 2, 8, 6, 'agendado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `id_salao` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `disponivel` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios`
--

INSERT INTO `horarios` (`id`, `id_salao`, `data`, `hora`, `disponivel`) VALUES
(1, 15, '2025-11-20', '00:00:00', 1),
(2, 15, '2025-11-20', '12:00:00', 1),
(3, 15, '2025-11-19', '13:00:00', 1),
(4, 15, '2025-11-20', '12:00:00', 1),
(5, 15, '2025-11-19', '13:00:00', 1),
(6, 15, '2025-11-20', '14:00:00', 1),
(7, 15, '2025-11-19', '15:00:00', 1),
(8, 15, '2025-11-20', '16:00:00', 1),
(9, 15, '2025-11-19', '17:00:00', 1),
(10, 15, '2025-11-20', '18:00:00', 1),
(11, 15, '2025-11-19', '19:00:00', 1),
(12, 15, '2025-11-20', '20:00:00', 1),
(13, 15, '2025-11-19', '21:00:00', 1),
(14, 15, '2025-11-22', '12:00:00', 1),
(15, 15, '2025-11-22', '13:00:00', 1),
(16, 15, '2025-11-22', '14:00:00', 1),
(17, 15, '2025-11-22', '15:00:00', 1),
(18, 15, '2025-11-22', '16:00:00', 1),
(19, 15, '2025-11-22', '17:00:00', 1),
(20, 15, '2025-11-22', '18:00:00', 1),
(21, 15, '2025-11-22', '19:00:00', 1),
(22, 15, '2025-11-22', '20:00:00', 1),
(23, 15, '2025-11-22', '21:00:00', 1),
(24, 15, '2025-11-18', '12:00:00', 1),
(25, 15, '2025-11-18', '13:00:00', 1),
(26, 15, '2025-11-18', '14:00:00', 1),
(27, 15, '2025-11-18', '15:00:00', 1),
(28, 15, '2025-11-18', '16:00:00', 1),
(29, 15, '2025-11-18', '17:00:00', 1),
(30, 15, '2025-11-18', '18:00:00', 1),
(31, 15, '2025-11-18', '19:00:00', 1),
(32, 15, '2025-11-18', '20:00:00', 1),
(33, 15, '2025-11-18', '21:00:00', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `saloes`
--

CREATE TABLE `saloes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `servicos` text DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_final` time DEFAULT NULL,
  `pausa` time DEFAULT NULL,
  `nome_usuario` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `saloes`
--

INSERT INTO `saloes` (`id`, `nome`, `endereco`, `telefone`, `servicos`, `horario_inicio`, `horario_final`, `pausa`, `nome_usuario`, `email`, `senha`, `usuario_id`, `lat`, `lng`) VALUES
(1, 'Cabelo no Ar', 'quadra 11', '1111111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'oi', 'oi', '1111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'oi', 'oi', '1111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'maria cabelos', 'av pinheiro', '121212', 'Corte, Coloração', '10:00:00', '18:02:00', '13:35:00', 'maria', 'ma@oi', '$2y$10$FrqKoMrzF70wapYiCXMshOd5sCCicCwEa9Owzv.go6JyIlN0OJCyW', NULL, NULL, NULL),
(5, 'maria cabelos', 'av pinheiro', '121212', 'Corte, Coloração', '10:00:00', '18:02:00', '13:35:00', 'maria', 'ma@oi', '$2y$10$YO1LoDPqfjmSVay8Z3xaSu41y.3RSuDIu5fCjjBp0Y9etjIISDjlG', NULL, NULL, NULL),
(6, 'maria cabelos', 'av pinheiro', '121212', 'Corte, Coloração', '10:00:00', '18:02:00', '13:35:00', 'maria', 'ma@oi', '$2y$10$SCzERuWUKRgcgJdFnuNhuuVCSz5IIAIJpXaj8ZyoBc635umGarqDK', NULL, NULL, NULL),
(7, 'livia', '111', '111', 'Corte', '00:50:00', '21:17:00', '00:50:00', NULL, NULL, NULL, 9, NULL, NULL),
(8, 'paulo', '12', '1314', 'Corte, Coloração', '00:05:00', '23:04:00', '02:07:00', NULL, NULL, NULL, 11, NULL, NULL),
(9, 'Salão Bela', 'R. Exemplo, 123, Bauru, SP', '(14) 0000-0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, -22.31450000, -49.06020000),
(10, 'mat', '0909', '0009', 'Corte, Coloração', '22:50:00', '16:55:00', '23:52:00', NULL, NULL, NULL, 13, NULL, NULL),
(11, 'mat', '0909', '0009', 'Corte, Coloração', '22:50:00', '16:55:00', '23:52:00', NULL, NULL, NULL, 16, NULL, NULL),
(12, 'sdas', 'afaffasda', 'adasdasd', 'Corte', '23:59:00', '23:00:00', '21:01:00', NULL, NULL, NULL, 20, NULL, NULL),
(13, 'lllsdsad', 'dfkafrefe', 'fdsf', 'Coloração', '03:03:00', '03:04:00', '23:32:00', NULL, NULL, NULL, 23, NULL, NULL),
(14, 'Ferneko Cortes', 'Rua Tomé de souza, 6-32', '14991340447', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'Ferneko Cortes', 'Rua Tomé de souza, 6-32', '14991340447', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 27, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `nome`, `preco`) VALUES
(1, 'Corte', 35.00),
(2, 'Unha', 15.00),
(3, 'Pé', 20.00),
(4, 'Barba', 15.00),
(5, 'Cabelo', 70.00),
(6, 'Progressiva', 150.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cliente','cabeleireiro') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`) VALUES
(1, 'maria eduarda', 'mariaeduardachicalelavezzo@gmail.com', '$2y$10$bDKSyhDtMbtSJ1rLz6Fv/eWel0FGiXxlKALtLlCW5HHbm6svlqgOO', 'cliente'),
(2, 'Renata', 'renata@gmail', '$2y$10$M2SBqP7TF1ptYxGJ79e2iuu6iVe/ST/uOiSIObkDZnAC2R7dj1opK', 'cabeleireiro'),
(3, 'kaike', 'kaike@gmail', '$2y$10$5Fdj1Fv89tDmOZYa6Vj7ZewMfx144TeGRlHYsbci2.qbr0xJPfCZu', 'cliente'),
(4, 'duda', 'duda@gmail', '$2y$10$Rbf9R2OQJ3/7.3ULqi8oqOU3W5LfP77Jt3X76Fqh6bznjHVPw2hYy', 'cliente'),
(5, 'maria eduarda ', 'ma@gmail.com', '$2y$10$kdax0Gf2ZEKHf.swihNmaud8M/nVXukdDkqH4W4wyztZdEnZ3wy2a', 'cliente'),
(6, 'duda', 'oi@oi', '$2y$10$IIhgkSko/ZFJtu7ZpoDlu.AsX4OEuQmq0qC8U70f5/LLam9Sc2auK', 'cliente'),
(8, 'oi', 'mari@99', '$2y$10$PZ7ic8Fq5HEMyg.Ri8xXNegeYVpmURXviLL02PsYQRpEop4EXRlPS', 'cabeleireiro'),
(9, 'livia', 'li@oi', '$2y$10$gajVJFS/YJx1YI/4i7iY2.asWAqCIlVpCa/rifzkr1vVQRDS8s0HW', 'cabeleireiro'),
(11, 'paulo ', 'paulo@gmail', '$2y$10$o/Jpq3NI8G8GAC1OzJhCtekJj9q7KXNUlPqCTgH9/phHnnoPO.Buy', 'cabeleireiro'),
(12, 'jade', 'jade@gmail', '$2y$10$pEYGf8vReuPMVER03ra5f.3PU2HexOvguQQyoKCJTOrztf2jo/Tt6', 'cliente'),
(13, 'mat', 'mat@oi', '$2y$10$7eJ5GgSg6nsdamE5c0qmZOc7PulkUR5qqEWtEuqa9Xm.8ONgHBlbe', 'cabeleireiro'),
(16, 'mat', 'luci@oi', '$2y$10$pGPpFBrEH4.SYeIW10VdueACB0XM8lV.MTJ9cnYT8X.XOk7xgxuyS', 'cabeleireiro'),
(20, 'dasda', 'mama@ui', '$2y$10$vILXUGRPZCNqzM40u94j1OLramaVND1n8zFIIj2HnGWm.NUDejz3G', 'cabeleireiro'),
(23, 'ewfre', 'oooo@d', '$2y$10$Q9yKDWFmVrCMGcdepBe1Wekpd1W5AmVkhLGo36ApO8IWDoavvJixK', 'cabeleireiro'),
(25, 'Fernando', 'fernando@fernando.com', '$2y$10$D5aMmu3jqSjCCp7FSz6TX.cOEfCi49H4VQBjamuQ2jKISK3AOyOeS', 'cliente'),
(27, 'Fernando Graciano', 'fernando@gmail.com', '$2y$10$jxyeByH0DhXmziT2SYn0HuoTfZlQ7Ybk1oSxpc0dL18Wm3YjL9bAq', 'cabeleireiro');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_horario` (`id_horario`),
  ADD KEY `id_servico` (`id_servico`);

--
-- Índices de tabela `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_salao` (`id_salao`);

--
-- Índices de tabela `saloes`
--
ALTER TABLE `saloes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `saloes`
--
ALTER TABLE `saloes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id`),
  ADD CONSTRAINT `agendamentos_ibfk_3` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id`);

--
-- Restrições para tabelas `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_salao`) REFERENCES `saloes` (`id`);

--
-- Restrições para tabelas `saloes`
--
ALTER TABLE `saloes`
  ADD CONSTRAINT `saloes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
