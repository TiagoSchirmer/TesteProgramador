-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16-Abr-2017 às 10:44
-- Versão do servidor: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testeprogramador`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE `reservas` (
  `codigo_reserva` int(11) NOT NULL,
  `hora_reserva` varchar(50) DEFAULT NULL,
  `dia_reserva` varchar(50) DEFAULT NULL,
  `codigo_usuario` int(11) DEFAULT NULL,
  `codigo_sala` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `reservas`
--

INSERT INTO `reservas` (`codigo_reserva`, `hora_reserva`, `dia_reserva`, `codigo_usuario`, `codigo_sala`) VALUES
(1, '07:00 - 08:00', '16/04/2017', 2, 1),
(2, '08:00 - 09:00', '16/04/2017', 2, 2),
(3, '07:00 - 08:00', '17/05/2017', 2, 1),
(4, '10:00 - 11:00', '16/07/2017', 2, 1),
(5, '08:00 - 09:00', '16/04/2017', 2, 1),
(6, '12:00 - 13:00', '16/04/2017', 2, 1),
(7, '09:00 - 10:00', '16/04/2017', 2, 2),
(8, '07:00 - 08:00', '16/04/2017', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas`
--

CREATE TABLE `salas` (
  `codigo_sala` int(11) NOT NULL,
  `nome_sala` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `salas`
--

INSERT INTO `salas` (`codigo_sala`, `nome_sala`) VALUES
(1, 'Sala 1'),
(2, 'Sala 2'),
(3, 'Sala 3'),
(4, 'Sala 4'),
(5, 'Sala 5'),
(6, 'Sala 6'),
(7, 'Sala 7'),
(8, 'Sala 8'),
(9, 'Sala 9'),
(10, 'Sala 10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `codigo_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(100) DEFAULT NULL,
  `login_usuario` varchar(50) DEFAULT NULL,
  `senha_usuario` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`codigo_usuario`, `nome_usuario`, `login_usuario`, `senha_usuario`) VALUES
(1, 'Teste da silva', 'teste', 'teste'),
(2, 'Usuário', 'user', 'user'),
(3, 'Gerente', 'ger', 'ger'),
(4, 'Funcionário 1', 'func', 'func');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`codigo_reserva`),
  ADD KEY `codigo_usuario` (`codigo_usuario`),
  ADD KEY `codigo_sala` (`codigo_sala`);

--
-- Indexes for table `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`codigo_sala`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`codigo_usuario`);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`codigo_usuario`) REFERENCES `usuarios` (`codigo_usuario`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`codigo_sala`) REFERENCES `salas` (`codigo_sala`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
