-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/03/2026 às 23:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto_login`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `amizades`
--

CREATE TABLE `amizades` (
  `id_amizade` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_amigo` int(11) NOT NULL,
  `status` enum('pendente','aceito') DEFAULT 'aceito',
  `data_conexao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `amizades`
--

INSERT INTO `amizades` (`id_amizade`, `id_usuario`, `id_amigo`, `status`, `data_conexao`) VALUES
(48, 14, 13, 'aceito', '2026-02-22 14:35:52'),
(49, 14, 12, 'aceito', '2026-02-22 14:36:23'),
(50, 13, 12, 'aceito', '2026-02-25 11:32:08'),
(51, 15, 12, 'aceito', '2026-02-26 19:02:40'),
(52, 15, 13, 'aceito', '2026-02-26 19:02:51'),
(53, 15, 14, 'aceito', '2026-02-26 19:03:13'),
(54, 16, 12, 'pendente', '2026-02-27 06:00:16'),
(55, 16, 13, 'aceito', '2026-02-27 06:00:20'),
(56, 16, 14, 'aceito', '2026-02-27 06:00:40'),
(57, 16, 15, 'aceito', '2026-02-27 06:00:45'),
(58, 13, 17, 'aceito', '2026-02-27 09:19:53'),
(59, 13, 18, 'aceito', '2026-02-27 09:20:09'),
(60, 13, 19, 'aceito', '2026-02-27 09:20:27'),
(61, 13, 20, 'aceito', '2026-02-27 09:20:36'),
(62, 13, 21, 'aceito', '2026-02-27 09:20:45'),
(63, 13, 22, 'aceito', '2026-02-27 09:20:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `gif_url` text DEFAULT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `post_id`, `parent_id`, `usuario_id`, `comentario`, `gif_url`, `data_comentario`) VALUES
(121, 83, NULL, 12, 'jfri fibr&#13;&#10;', NULL, '2026-02-09 06:39:45'),
(131, 84, NULL, 12, 'ffvrevcv', NULL, '2026-02-09 06:52:03'),
(139, 84, NULL, 12, 'vucgwcu ', NULL, '2026-02-09 07:01:34'),
(140, 84, NULL, 12, 'ewef', NULL, '2026-02-09 07:01:37'),
(143, 85, NULL, 12, 'r4rb54 w fr ', NULL, '2026-02-09 07:02:11'),
(144, 85, NULL, 12, 'frg', NULL, '2026-02-09 07:04:25'),
(146, 91, NULL, 13, 'tbt4b', NULL, '2026-02-09 07:06:21'),
(151, 85, NULL, 12, 'hhtreh', NULL, '2026-02-09 07:53:00'),
(153, 84, NULL, 12, 'f3rg', NULL, '2026-02-15 11:33:51'),
(154, 96, NULL, 13, 'rervebv', NULL, '2026-02-15 12:04:17'),
(155, 85, NULL, 12, 'rf', NULL, '2026-02-15 12:04:48'),
(156, 96, NULL, 14, 'foda', NULL, '2026-02-22 14:36:43'),
(157, 102, NULL, 12, 'wewfqg3', NULL, '2026-02-22 14:39:07'),
(158, 96, NULL, 14, 'rbvr ', NULL, '2026-02-22 14:46:31'),
(159, 101, NULL, 12, 'santicum&#13;&#10;', NULL, '2026-02-22 14:48:56'),
(160, 102, NULL, 14, 'fweevr', NULL, '2026-02-24 01:23:37'),
(161, 83, NULL, 14, 'rgrtnb', NULL, '2026-02-24 01:55:02'),
(162, 96, NULL, 12, 'r3ggre', NULL, '2026-02-24 02:08:54'),
(163, 96, NULL, 14, 'dd2f', NULL, '2026-02-24 02:24:12'),
(164, 96, NULL, 14, 'rvrebr', NULL, '2026-02-24 02:24:16'),
(166, 96, NULL, 14, 'gtt4h', NULL, '2026-02-24 02:44:13'),
(172, 103, NULL, 14, '3f45g4', NULL, '2026-02-25 08:55:32'),
(177, 112, NULL, 12, 'frgr', NULL, '2026-02-25 10:47:04'),
(178, 112, NULL, 14, 'ft4byte ', NULL, '2026-02-25 11:05:33'),
(179, 112, NULL, 14, 'f rwgr', NULL, '2026-02-25 11:05:37'),
(180, 112, NULL, 12, 'tbtb', NULL, '2026-02-25 11:05:53'),
(187, 93, NULL, 12, 'rvteb4b', NULL, '2026-02-25 17:36:09'),
(189, 110, NULL, 13, 'wfrbg', NULL, '2026-02-25 18:09:34'),
(190, 103, NULL, 13, 'vrev', NULL, '2026-02-25 18:09:46'),
(192, 113, NULL, 13, 'rvebt', NULL, '2026-02-25 18:17:22'),
(193, 113, NULL, 13, 'btrbtn', NULL, '2026-02-25 18:17:24'),
(194, 113, NULL, 13, 'ervrb', NULL, '2026-02-25 18:17:26'),
(197, 93, NULL, 13, 'rwrevre', NULL, '2026-02-25 18:25:21'),
(199, 93, NULL, 13, 'vrevbreb', NULL, '2026-02-25 18:25:29'),
(205, 113, NULL, 12, 'f3rg4gt4b', NULL, '2026-02-25 18:34:42'),
(212, 92, NULL, 13, '2rd43fvr3v', NULL, '2026-02-25 18:38:37'),
(213, 92, NULL, 13, '2rd43fvr3v', NULL, '2026-02-25 18:38:40'),
(214, 113, 193, 12, 'dvrebe', NULL, '2026-02-25 18:41:54'),
(215, 113, 193, 12, 'dvrebe', NULL, '2026-02-25 18:42:01'),
(216, 113, 193, 12, 'dvrebe', NULL, '2026-02-25 18:42:03'),
(217, 113, 193, 12, 'dvrebe', NULL, '2026-02-25 18:42:03'),
(218, 113, 193, 12, 'dvrebe', NULL, '2026-02-25 18:42:03'),
(219, 113, 193, 12, 'dvrebe', NULL, '2026-02-25 18:42:03'),
(221, 113, 192, 12, 'fwefwe', NULL, '2026-02-25 18:46:38'),
(222, 113, 193, 12, 'afewv', NULL, '2026-02-25 18:50:08'),
(223, 113, NULL, 12, ' ref gg', NULL, '2026-02-25 18:50:29'),
(224, 113, 214, 12, 'dwevwv', NULL, '2026-02-25 18:53:15'),
(225, 93, 187, 12, 'ff f', NULL, '2026-02-25 18:53:52'),
(226, 93, 197, 12, 'fr ff ', NULL, '2026-02-25 18:54:00'),
(227, 93, 226, 12, 'efw ', NULL, '2026-02-25 18:54:06'),
(228, 93, 225, 12, 'cwvev', NULL, '2026-02-25 18:59:08'),
(232, 93, 187, 13, 'ferwervbre', NULL, '2026-02-25 19:25:56'),
(233, 93, 225, 13, 'vvregg', NULL, '2026-02-25 19:26:07'),
(236, 93, 199, 13, 'fewv', NULL, '2026-02-25 19:35:53'),
(237, 93, 236, 13, 'fweg', NULL, '2026-02-25 19:35:58'),
(238, 93, NULL, 13, '😂😂😂😂😂', NULL, '2026-02-25 19:36:11'),
(240, 93, 238, 12, '❤️😂😂😂', NULL, '2026-02-25 20:30:54'),
(241, 115, NULL, 15, '❤️❤️❤️', NULL, '2026-02-26 19:02:26'),
(242, 116, NULL, 15, '👑✨', NULL, '2026-02-26 19:12:00'),
(243, 119, NULL, 16, 'wefewg', NULL, '2026-02-27 05:59:20'),
(244, 119, NULL, 16, '🔥 wow!', NULL, '2026-02-27 05:59:31'),
(245, 118, NULL, 16, '❤️❤️❤️❤️❤️❤️❤️✨✨ relax', NULL, '2026-02-27 09:38:29'),
(246, 118, 245, 16, '👀', NULL, '2026-02-27 09:38:35'),
(247, 93, NULL, 13, 'no celular estou trabalhando ', NULL, '2026-03-01 15:35:11'),
(248, 85, 155, 13, 'sensacional', NULL, '2026-03-01 15:54:44'),
(249, 85, NULL, 13, '❤️💎', NULL, '2026-03-01 15:54:50'),
(251, 89, NULL, 13, 'wwefef', NULL, '2026-03-02 17:05:49'),
(252, 92, 212, 13, 'hgcgg', NULL, '2026-03-02 17:29:15'),
(253, 92, 212, 13, 'bvccgg', NULL, '2026-03-02 17:29:25'),
(254, 92, 212, 13, 'ggghgg', NULL, '2026-03-02 17:29:29'),
(255, 92, 212, 13, 'vggfff', NULL, '2026-03-02 17:29:41'),
(256, 115, NULL, 15, 'g 👑', NULL, '2026-03-03 06:55:49'),
(257, 115, NULL, 15, 'g 👑', NULL, '2026-03-03 06:55:49'),
(258, 93, NULL, 14, 'wef f3 q', NULL, '2026-03-03 08:31:40'),
(259, 93, NULL, 14, '😂😂😂😂', NULL, '2026-03-03 08:31:43'),
(260, 93, NULL, 14, '🖤', NULL, '2026-03-03 08:31:49'),
(261, 111, NULL, 13, 'g egw eg', NULL, '2026-03-03 08:48:03'),
(262, 111, NULL, 13, 'g egw eg', NULL, '2026-03-03 08:48:03'),
(263, 111, NULL, 13, 'g gre g', NULL, '2026-03-03 08:48:06'),
(264, 111, NULL, 13, 'muito foda ❤️❤️❤️❤️❤️', NULL, '2026-03-03 08:48:17'),
(265, 111, 264, 14, 'obrigada, foi eu quem produziu o trailer', NULL, '2026-03-03 08:49:01'),
(266, 116, NULL, 14, ' rwe weq 💀💀💀', NULL, '2026-03-03 08:55:21'),
(267, 115, NULL, 14, ' wqe e', NULL, '2026-03-03 08:55:29'),
(268, 93, NULL, 14, 'dwef ', NULL, '2026-03-03 09:40:42'),
(269, 93, 199, 14, '✅', NULL, '2026-03-03 09:41:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes_sistema`
--

CREATE TABLE `configuracoes_sistema` (
  `id` int(11) NOT NULL,
  `chave` varchar(50) NOT NULL,
  `valor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `configuracoes_sistema`
--

INSERT INTO `configuracoes_sistema` (`id`, `chave`, `valor`) VALUES
(1, 'ultima_limpeza', '2026-03-04 16:29:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id_curtida` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_curtida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curtidas`
--

INSERT INTO `curtidas` (`id_curtida`, `post_id`, `usuario_id`, `data_curtida`) VALUES
(100, 84, 12, '2026-02-09 07:00:59'),
(102, 85, 12, '2026-02-09 07:04:40'),
(104, 83, 12, '2026-02-09 07:24:12'),
(106, 96, 12, '2026-02-15 12:04:29'),
(107, 100, 14, '2026-02-15 12:35:02'),
(108, 102, 14, '2026-02-22 14:00:06'),
(109, 98, 14, '2026-02-22 14:00:11'),
(110, 96, 14, '2026-02-22 14:36:39'),
(111, 103, 12, '2026-02-22 14:39:04'),
(112, 102, 12, '2026-02-22 14:39:08'),
(113, 101, 12, '2026-02-22 14:48:49'),
(114, 101, 14, '2026-02-22 14:49:19'),
(115, 85, 14, '2026-02-24 01:19:19'),
(116, 83, 14, '2026-02-24 01:55:00'),
(121, 103, 14, '2026-02-25 08:55:35'),
(124, 112, 12, '2026-02-25 10:47:02'),
(125, 112, 14, '2026-02-25 11:05:34'),
(127, 93, 12, '2026-02-25 17:26:51'),
(131, 113, 13, '2026-02-25 18:17:35'),
(142, 113, 12, '2026-02-25 18:50:34'),
(143, 92, 13, '2026-02-25 18:58:34'),
(144, 92, 12, '2026-02-25 19:25:32'),
(145, 115, 15, '2026-02-26 19:02:30'),
(146, 116, 15, '2026-02-26 19:11:53'),
(147, 119, 16, '2026-02-27 05:59:17'),
(148, 118, 16, '2026-02-27 09:38:31'),
(149, 93, 13, '2026-03-01 15:35:20'),
(150, 85, 13, '2026-03-01 15:54:32'),
(151, 89, 13, '2026-03-02 16:48:00'),
(152, 90, 13, '2026-03-02 16:53:47'),
(153, 88, 13, '2026-03-02 17:45:20'),
(154, 93, 14, '2026-03-03 08:31:55'),
(155, 111, 13, '2026-03-03 08:48:21'),
(156, 111, 14, '2026-03-03 08:48:40'),
(157, 116, 14, '2026-03-03 08:55:22'),
(158, 115, 14, '2026-03-03 08:55:25'),
(159, 91, 13, '2026-03-04 14:29:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emojis_custom`
--

CREATE TABLE `emojis_custom` (
  `id_emoji` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `url_imagem` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mapa_site`
--

CREATE TABLE `mapa_site` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mapa_site`
--

INSERT INTO `mapa_site` (`id`, `titulo`, `url`, `descricao`) VALUES
(1, 'Galeria de Fotos', 'site/rv_project_inc/posts/galeria/galeria.php', 'Veja as imagens da ordem'),
(2, 'O Princípio', 'site/rv_project_inc/posts/o_principio/o_principio.php', 'A história e os fundamentos'),
(3, 'Membros', 'membros.php', 'Busque por outros corvos'),
(4, 'Login', 'index.php', 'Area de login e acesso'),
(8, 'Meu Perfil', 'perfil_do_usuario.php', 'Editar meus dados e fotos'),
(10, 'Comunidade', 'site/public/home.php', 'Feed de atualizações e postagens recentes'),
(11, 'Repositorios', 'site/public/repo.php', 'Projetos e Postagens do Admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id_msg` int(11) NOT NULL,
  `id_de` int(11) NOT NULL,
  `id_para` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `lida` enum('n','s') DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_chat`
--

CREATE TABLE `mensagens_chat` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT 'default-user.png',
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens_privadas`
--

CREATE TABLE `mensagens_privadas` (
  `id` int(11) NOT NULL,
  `remetente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `citacao_id` int(11) DEFAULT NULL,
  `mensagem` text NOT NULL,
  `gif_url` text DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagens_privadas`
--

INSERT INTO `mensagens_privadas` (`id`, `remetente_id`, `destinatario_id`, `citacao_id`, `mensagem`, `gif_url`, `data_envio`, `lida`) VALUES
(35, 1, 2, NULL, 'vvbvfefbrebb', NULL, '2026-02-03 03:54:56', 0),
(40, 1, 2, NULL, 'fasfasfasf', NULL, '2026-02-03 18:18:00', 0),
(42, 1, 2, NULL, 'd2ecev', NULL, '2026-02-04 01:36:18', 0),
(44, 3, 2, NULL, 'd d d', NULL, '2026-02-04 01:36:37', 0),
(45, 3, 4, NULL, 'dsd sd', NULL, '2026-02-04 01:36:39', 0),
(48, 1, 2, NULL, 'wefwef', NULL, '2026-02-06 02:29:56', 0),
(52, 3, 1, NULL, 'reveb trb tr', NULL, '2026-02-06 04:49:06', 0),
(53, 3, 1, NULL, 'wv', NULL, '2026-02-06 04:50:33', 0),
(54, 3, 1, NULL, 'jjcbvb', NULL, '2026-02-06 11:10:58', 0),
(55, 3, 5, NULL, '553g45g5  g 4g4 g55g', NULL, '2026-02-07 21:17:12', 0),
(56, 3, 5, NULL, 'g5g 5g45g', NULL, '2026-02-07 21:17:13', 0),
(57, 3, 5, NULL, '55g 4g', NULL, '2026-02-07 21:17:14', 0),
(58, 3, 5, NULL, 'gr g54 g4g', NULL, '2026-02-07 21:17:16', 0),
(59, 3, 5, NULL, 'gr4 r', NULL, '2026-02-07 21:17:17', 0),
(61, 4, 1, NULL, 'dve e', NULL, '2026-02-08 03:51:02', 0),
(62, 4, 3, NULL, 'crvrb', NULL, '2026-02-08 05:11:54', 0),
(64, 10, 11, NULL, 'fwqvrv', NULL, '2026-02-08 20:45:24', 0),
(94, 13, 14, NULL, 'eefvre', NULL, '2026-02-25 16:20:43', 0),
(100, 12, 14, NULL, 'excelente', NULL, '2026-02-25 16:33:15', 0),
(101, 12, 14, NULL, 'está funcionando os emojis', NULL, '2026-02-25 16:33:41', 0),
(102, 12, 14, NULL, 'e também as mensagens são apagadas, legal', NULL, '2026-02-25 16:34:05', 0),
(103, 12, 14, NULL, '🙀', NULL, '2026-02-25 16:34:11', 0),
(106, 13, 12, NULL, 'efwerwvre', NULL, '2026-02-25 16:40:09', 0),
(107, 12, 13, NULL, 'vetwbtrn', NULL, '2026-02-25 16:40:17', 0),
(108, 12, 13, NULL, 'eget4', NULL, '2026-02-25 16:40:20', 0),
(109, 12, 13, NULL, '😍😍😍😍😍😍😍😍😍😍', NULL, '2026-02-25 16:40:39', 0),
(110, 12, 13, 106, 'verbtrnren', NULL, '2026-02-25 16:42:38', 0),
(111, 12, 13, NULL, 'bgr', NULL, '2026-02-25 16:58:38', 0),
(112, 13, 12, NULL, 'verb rtn', NULL, '2026-02-25 17:00:26', 0),
(113, 12, 13, 112, 'vfveb', NULL, '2026-02-25 17:08:27', 0),
(114, 12, 13, NULL, '😉', NULL, '2026-02-25 17:08:31', 0),
(115, 12, 13, NULL, 'f rsg e vvtv', NULL, '2026-02-25 17:22:43', 0),
(116, 12, 13, NULL, 'rebreb', NULL, '2026-02-25 17:52:30', 0),
(117, 13, 12, NULL, 'retrntyn', NULL, '2026-02-25 17:54:20', 0),
(118, 13, 12, NULL, '🤨', NULL, '2026-02-25 17:54:23', 0),
(120, 13, 20, NULL, '🙄', NULL, '2026-03-02 15:34:48', 0),
(121, 13, 15, NULL, '😍', NULL, '2026-03-02 15:49:15', 0),
(125, 13, 19, NULL, 'frvg4b', NULL, '2026-03-02 18:27:23', 0),
(126, 13, 20, NULL, 'ewe', NULL, '2026-03-02 18:27:27', 0),
(127, 13, 20, NULL, '😏', NULL, '2026-03-02 18:27:29', 0),
(128, 13, 20, NULL, 'dfgfhgfhgfjf jttytj tyjty', NULL, '2026-03-02 18:27:38', 0),
(129, 13, 20, 128, 'g trhrh rhr', NULL, '2026-03-02 18:27:41', 0),
(130, 13, 20, NULL, 'http://localhost:8080/Sistema-login/mensagens.php?com=20', NULL, '2026-03-02 18:27:52', 0),
(135, 13, 21, NULL, 'rfreg  gtgg e  geg 😐', NULL, '2026-03-02 19:25:25', 0),
(136, 15, 13, NULL, '🙄', NULL, '2026-03-03 06:57:10', 0),
(137, 13, 15, 136, 'wre wge  eg gr', NULL, '2026-03-03 08:25:03', 0),
(138, 14, 13, 94, 'ew evre regr', NULL, '2026-03-03 08:29:01', 0),
(139, 14, 13, NULL, 'g egreg er', NULL, '2026-03-03 08:29:03', 0),
(140, 14, 13, NULL, '😐😐😐😐😐😐😐😐', NULL, '2026-03-03 08:29:11', 0),
(141, 14, 12, NULL, 'frf rfrg rr', NULL, '2026-03-03 08:29:16', 0),
(142, 14, 16, NULL, 'frgqwg🔥', NULL, '2026-03-03 08:29:25', 0),
(143, 14, 15, NULL, 'fw f wrgg  wg', NULL, '2026-03-03 08:29:29', 0),
(144, 14, 15, NULL, '🤨', NULL, '2026-03-03 08:29:33', 0),
(145, 13, 14, NULL, 'ere gwrg', NULL, '2026-03-03 08:47:53', 0),
(146, 13, 14, NULL, 'ggw grege g', NULL, '2026-03-03 08:47:55', 0),
(147, 13, 14, NULL, 'fwf ewef', NULL, '2026-03-03 08:49:36', 0),
(148, 14, 15, NULL, 'w  we😏', NULL, '2026-03-03 08:55:35', 0),
(149, 14, 15, NULL, 'sda f', NULL, '2026-03-03 09:21:40', 0),
(150, 14, 13, NULL, 'fr w fw', NULL, '2026-03-03 09:40:03', 0),
(151, 14, 13, NULL, 'gg🙄', NULL, '2026-03-03 09:40:06', 0),
(152, 14, 13, NULL, '😃', NULL, '2026-03-03 09:40:11', 0),
(153, 14, 13, NULL, '😊 oi amigo', NULL, '2026-03-03 11:11:51', 0),
(154, 13, 21, NULL, 'cvdba', NULL, '2026-03-03 11:42:10', 0),
(155, 13, 21, NULL, 'vebvwebw', NULL, '2026-03-03 11:42:13', 0),
(156, 13, 21, NULL, 'fwewg', NULL, '2026-03-03 11:42:14', 0),
(157, 13, 21, NULL, 'FEFE  F', NULL, '2026-03-03 11:42:16', 0),
(158, 13, 14, NULL, '🥰', NULL, '2026-03-03 12:07:47', 0),
(159, 14, 13, 158, '😑', NULL, '2026-03-03 12:08:07', 0),
(160, 14, 15, NULL, '😃', NULL, '2026-03-04 19:02:56', 0),
(161, 13, 21, NULL, 'uffa, finalmente consegui usar algo que funcionasse!!!!', NULL, '2026-03-04 19:26:12', 0),
(162, 13, 21, NULL, 'um <meta> de viewport em todas as páginas', NULL, '2026-03-04 19:26:45', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id_notificacao` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `mensagem` text DEFAULT NULL,
  `id_post_referencia` int(11) DEFAULT NULL,
  `id_remetente` int(11) NOT NULL,
  `tipo` varchar(20) DEFAULT 'amizade',
  `lida` tinyint(1) DEFAULT 0,
  `data_notificacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id_notificacao`, `id_destinatario`, `mensagem`, `id_post_referencia`, `id_remetente`, `tipo`, `lida`, `data_notificacao`) VALUES
(336, 12, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 08:29:16'),
(337, 16, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 08:29:25'),
(338, 15, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 08:29:29'),
(339, 15, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 08:29:33'),
(340, 13, '<b>Raissa</b> comentou na sua publicação.', 93, 14, 'comentario', 1, '2026-03-03 08:31:40'),
(343, 13, '<b>Raissa</b> curtiu sua publicação.', 93, 14, 'like', 1, '2026-03-03 08:31:55'),
(344, 14, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 08:47:53'),
(345, 14, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 08:47:55'),
(346, 14, '<b>Raveno Mesaenc</b> comentou na sua publicação.', 111, 13, 'comentario', 1, '2026-03-03 08:48:03'),
(347, 14, '<b>Raveno Mesaenc</b> comentou na sua publicação.', 111, 13, 'comentario', 1, '2026-03-03 08:48:03'),
(348, 14, '<b>Raveno Mesaenc</b> comentou na sua publicação.', 111, 13, 'comentario', 1, '2026-03-03 08:48:06'),
(349, 14, '<b>Raveno Mesaenc</b> comentou na sua publicação.', 111, 13, 'comentario', 1, '2026-03-03 08:48:17'),
(350, 14, '<b>Raveno Mesaenc</b> curtiu sua publicação.', 111, 13, 'like', 1, '2026-03-03 08:48:21'),
(352, 14, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 08:49:36'),
(353, 15, '<b>Raissa</b> comentou na sua publicação.', 116, 14, 'comentario', 1, '2026-03-03 08:55:21'),
(354, 15, '<b>Raissa</b> curtiu sua publicação.', 116, 14, 'like', 1, '2026-03-03 08:55:22'),
(355, 15, '<b>Raissa</b> curtiu sua publicação.', 115, 14, 'like', 1, '2026-03-03 08:55:25'),
(356, 15, '<b>Raissa</b> comentou na sua publicação.', 115, 14, 'comentario', 1, '2026-03-03 08:55:29'),
(357, 15, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 08:55:35'),
(358, 15, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 09:21:40'),
(359, 13, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 09:40:03'),
(360, 13, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 09:40:06'),
(361, 13, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 09:40:11'),
(362, 13, '<b>Raissa</b> comentou na sua publicação.', 93, 14, 'comentario', 1, '2026-03-03 09:40:42'),
(363, 13, '<b>Raissa</b> respondeu ao seu comentário.', 93, 14, 'comentario', 1, '2026-03-03 09:41:12'),
(364, 13, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 11:11:51'),
(365, 21, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 11:42:10'),
(366, 21, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 11:42:13'),
(367, 21, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 11:42:14'),
(368, 21, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 11:42:16'),
(369, 14, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-03 12:07:47'),
(370, 13, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-03 12:08:07'),
(371, 15, 'Enviou uma nova mensagem.', NULL, 14, 'mensagem', 0, '2026-03-04 19:02:56'),
(372, 21, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-04 19:26:12'),
(373, 21, 'Enviou uma nova mensagem.', NULL, 13, 'mensagem', 0, '2026-03-04 19:26:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `timeline`
--

CREATE TABLE `timeline` (
  `id_post` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `imagem_post` varchar(255) DEFAULT NULL,
  `data_post` timestamp NOT NULL DEFAULT current_timestamp(),
  `gradiente_id` varchar(50) DEFAULT NULL,
  `alinhamento` varchar(20) DEFAULT 'center'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `timeline`
--

INSERT INTO `timeline` (`id_post`, `usuario_id`, `conteudo`, `imagem_post`, `data_post`, `gradiente_id`, `alinhamento`) VALUES
(83, 12, '<b>fwf</b> \r\n<u>we fwe </u>f <u>wq fwqf </u>\r\n fawf f fyw yeyfwifw  ew fwef uwe fy fuf w\r\nufwfuwgfiuwfgwf\r\nwfwfuogwgfuwfgwf\r\nwewfwfwfiuwgfiuwqfgwqiugfiugf gffwifiwf\r\nwfwgfiwgf iwqf fwfgwf\r\nwffwfhuwfgwi fwi iuw gfiwfiwfw\r\nfwfyewiufgwiufefwkfu ewf wew\r\nfwfwfgweh gfofewfpewhfew\r\nehfwioowfwgfwefw\r\nfwfgwiufgfiuw fwf\r\newf[gewfgwuf gwqf\r\nwfhwofgwqiufwqf\r\nwfwfowfiuwgfiwg', NULL, '2026-02-09 06:39:38', 'grad-blood', 'center'),
(84, 12, '', 'c4987c21ccc129c09512e607750072b2.gif', '2026-02-09 06:50:14', NULL, 'center'),
(85, 12, '', 'be510b694695c01819097a122dda6a4a.mp4', '2026-02-09 06:50:42', NULL, 'center'),
(87, 13, 'fqrbqb', NULL, '2026-02-09 06:53:45', 'grad-void', ''),
(88, 13, '', '9816521984201bd3aa70fe7c5fdd5492.jpg', '2026-02-09 06:54:21', NULL, 'center'),
(89, 13, '', 'f0fff21ec048b7c1bc07846761e46c3d.jpg', '2026-02-09 06:54:26', NULL, 'center'),
(90, 13, '', '68394ef21686695993b3550383cf239d.jpg', '2026-02-09 06:54:35', NULL, 'center'),
(91, 13, '', 'f1d9b4e9dc967f19a24fb71f88964698.jpg', '2026-02-09 06:54:46', NULL, 'center'),
(92, 13, '', 'a4b1eb3ed6ea121cd03e09bc5fdba862.mp4', '2026-02-09 06:55:03', NULL, 'center'),
(93, 13, '', 'c107a620a048acb1a7cde23445cf9d4e.gif', '2026-02-09 06:55:31', NULL, 'center'),
(96, 12, '', '7e1253b52714fc76fc4adf19536c4de9.jpg', '2026-02-09 08:07:02', NULL, 'center'),
(97, 14, 'hj jfwffqwf ii  fw fwif  y f ywyfwwf  y ewufwuy euif iw eiu fiu gf iuw i iywg fie fiwqlwfpefo fwe iof  eof9fw9fw99', NULL, '2026-02-15 12:30:42', 'grad-void', 'center'),
(98, 14, '', '649cefdfaadc5d25a02085a03f61e2b1.gif', '2026-02-15 12:30:52', NULL, 'center'),
(99, 14, '', '11375c01eddf287a67947ee57ef4adbc.png', '2026-02-15 12:31:03', NULL, 'center'),
(100, 14, '', '8bc7025ce4f07d3a74ec96186432876d.jpg', '2026-02-15 12:31:10', NULL, 'center'),
(101, 14, '', '4eaf946d07741825b7f00f5c726d1d4d.jpg', '2026-02-15 12:31:22', NULL, 'center'),
(102, 14, '', 'b3634c92fdfe482a6fe5bbc69e402b90.jpg', '2026-02-15 12:31:27', NULL, 'center'),
(103, 14, 'gverbt4wn ', '49d29339d3e78abd0014755e7c73d7cd.gif', '2026-02-15 12:34:40', NULL, 'center'),
(110, 14, '', '0b8a4b552b9b3878a206dceb44d85b4c.mp4', '2026-02-25 10:46:05', NULL, 'center'),
(111, 14, '', 'dcbd68560c1c3cc6bbff9a3ca20bc87d.mp4', '2026-02-25 10:46:15', NULL, 'center'),
(112, 12, '', 'f9314e8f58f252f24c2d8016d3871d0c.mp4', '2026-02-25 10:46:51', NULL, 'center'),
(113, 12, '', '6529236d3c3c17d0e2cfa3e7f141d731.mp4', '2026-02-25 13:26:37', NULL, 'center'),
(114, 15, 'hbt4wn y5 h h thh th 4hthth h 6h', NULL, '2026-02-26 19:01:09', NULL, 'center'),
(115, 15, '', '717f3d76c416b644bc1adf3c112c2519.jpeg', '2026-02-26 19:01:24', NULL, 'center'),
(116, 15, '', '3a30b4550bae2aa7b4e6745e2f87eb64.png', '2026-02-26 19:02:09', NULL, 'center'),
(117, 16, '', '154aa84b5964420a7a92d0e625ef7d80.png', '2026-02-27 05:57:46', NULL, 'center'),
(118, 16, '', 'd48ce14c74903cba2087ff790556bfd4.jpg', '2026-02-27 05:58:03', NULL, 'center'),
(119, 16, '', '8f9d6b185638e3530891131270dfbf44.jpg', '2026-02-27 05:58:21', NULL, 'center'),
(120, 16, '', '0df0bba37902c36355db074808c14946.jpg', '2026-02-27 05:58:37', NULL, 'center');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'img/perfil/default-user.jpg',
  `bio` text DEFAULT NULL,
  `ultimo_acesso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `telefone`, `email`, `senha`, `foto`, `bio`, `ultimo_acesso`) VALUES
(12, 'Kaleu', '98991331058', 'kaleu.conde@gmail.com', '$2y$10$X.138XRuAU4Uf/N3AByp5eskrYrxlRRUHBDEfeu4L.qtghDFxkIc2', 'img/perfis/Kaleu_id_12.jpg', 'Senhor das ideias.....', '2026-02-22 14:32:14'),
(13, 'Raveno Mesaenc', '98991331058', 'lorde.raveno@gmail.com', '$2y$10$T9Le1f92yZPfS8CimBpQL.DjRJPyCOuarbjvX9b18uFzIT4TjJVzi', 'img/perfis/Raveno_Mesaenc_id_13.jpeg', 'Alguém como todos, porém diverso.', '2026-02-25 11:29:48'),
(14, 'Raissa', '98991331058', 'raissa.brend@gmail.com', '$2y$10$SCRQkZJOKy8OPpCR5MsoPOy8O8sZv753JbWT2HMIcNVexiWROHD0G', 'img/perfis/Raissa_id_14.png', 'Este membro ainda não revelou sua história.', '2026-02-25 10:05:27'),
(15, 'Duarte', '98991331058', 'duarte_29@gmail.com', '$2y$10$BttZYU3vsbcG6M7KpIzXG.xcw38y3xjyo7VRjEc5sZB2e3BVSw/QS', 'img/perfis/Duarte_id_15.png', 'Este membro ainda não revelou sua história.', '2026-02-26 19:00:47'),
(16, 'Sadora', '98991331058', 'sadora.brumer@gamil.com', '$2y$10$v9KMGV3LVD97/Nc.h9IjteNBWNKwTgJ2g3EAFRuAWT9fJ0Sen/Paq', 'img/perfis/Sadora_id_16.jpg', 'Este membro ainda não revelou sua história.', '2026-02-27 05:54:12'),
(17, 'Eduardo', '98991331058', 'eduardo09@gmail.com', '$2y$10$uCXf/j3l6w4FpId4eztMIOw49uZUg9a7Tge.kCkqwGxmi7EWbcNuS', 'img/perfis/Eduardo_id_17.png', 'Este membro ainda não revelou sua história.', '2026-02-27 09:34:54'),
(18, 'Francisco', '98991331058', 'francisco_xms@gmail.com', '$2y$10$PT3lU/o8Eivq77xdxTQvqe1BjTq.z04Ocfg633BrDia5Qsz2ISpx2', 'img/perfis/Francisco_id_18.png', 'Este membro ainda não revelou sua história.', '2026-02-27 09:35:16'),
(19, 'Benard', '98991331058', 'benard@gmail.com', '$2y$10$ao4vM2c6QJw8c.pvLKSMoO03NmgB0CTOPauFfXAwVUxirWiqlNxRa', 'img/perfis/Benard_id_19.png', 'Este membro ainda não revelou sua história.', '2026-02-27 09:34:27'),
(20, 'Brenda', '98991331058', 'brenda.cv6@gmail.com', '$2y$10$AalmH4yYP1EXf7JgL8ffMeIm7pJiB5U/ojSNzTUR43Ieoc5krXhnW', 'img/perfis/Brenda_id_20.png', 'Este membro ainda não revelou sua história.', '2026-02-27 09:34:04'),
(21, 'Amanda', '98991331058', 'amanda_schulet@gmail.com', '$2y$10$4OOwUWp6wjEf/KJLEUcv2.3JzfthZwariMCty6cjB4a9cvRbYuAMq', 'img/perfis/Amanda_id_21.png', 'Este membro ainda não revelou sua história.', '2026-02-27 09:33:30'),
(22, 'Strogonof', '98991331058', 'tumf_tumf_08@gmail.com', '$2y$10$ADIjpPHljLocf8UTfo4SwOSLkJ7TxXwB94QCuiHrMMLp2LVKdyRxq', 'img/perfis/Strogonof_id_22.jpg', 'Este membro ainda não revelou sua história.', '2026-02-27 09:35:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_configuracoes`
--

CREATE TABLE `usuario_configuracoes` (
  `id_config` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tema` enum('escuro','claro') DEFAULT 'escuro',
  `privacidade_feed` enum('publico','amigos','privado') DEFAULT 'publico',
  `qualidade_midia` enum('SD','HD') DEFAULT 'SD'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `amizades`
--
ALTER TABLE `amizades`
  ADD PRIMARY KEY (`id_amizade`),
  ADD KEY `usuario_id` (`id_usuario`),
  ADD KEY `amigo_id` (`id_amigo`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_comentario_parent` (`parent_id`);

--
-- Índices de tabela `configuracoes_sistema`
--
ALTER TABLE `configuracoes_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chave` (`chave`);

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id_curtida`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `emojis_custom`
--
ALTER TABLE `emojis_custom`
  ADD PRIMARY KEY (`id_emoji`);

--
-- Índices de tabela `mapa_site`
--
ALTER TABLE `mapa_site`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id_msg`),
  ADD KEY `mensagens_ibfk_1` (`id_de`),
  ADD KEY `mensagens_ibfk_2` (`id_para`);

--
-- Índices de tabela `mensagens_chat`
--
ALTER TABLE `mensagens_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mensagens_chat_ibfk_1` (`usuario_id`);

--
-- Índices de tabela `mensagens_privadas`
--
ALTER TABLE `mensagens_privadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mensagem_citacao` (`citacao_id`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id_notificacao`),
  ADD KEY `notificacoes_ibfk_1` (`id_destinatario`),
  ADD KEY `notificacoes_ibfk_2` (`id_remetente`);

--
-- Índices de tabela `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `timeline_ibfk_1` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Índices de tabela `usuario_configuracoes`
--
ALTER TABLE `usuario_configuracoes`
  ADD PRIMARY KEY (`id_config`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD UNIQUE KEY `id_usuario_2` (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `amizades`
--
ALTER TABLE `amizades`
  MODIFY `id_amizade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT de tabela `configuracoes_sistema`
--
ALTER TABLE `configuracoes_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id_curtida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT de tabela `emojis_custom`
--
ALTER TABLE `emojis_custom`
  MODIFY `id_emoji` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mapa_site`
--
ALTER TABLE `mapa_site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id_msg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_chat`
--
ALTER TABLE `mensagens_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_privadas`
--
ALTER TABLE `mensagens_privadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id_notificacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=374;

--
-- AUTO_INCREMENT de tabela `timeline`
--
ALTER TABLE `timeline`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuario_configuracoes`
--
ALTER TABLE `usuario_configuracoes`
  MODIFY `id_config` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `amizades`
--
ALTER TABLE `amizades`
  ADD CONSTRAINT `amizades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `amizades_ibfk_2` FOREIGN KEY (`id_amigo`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_amizades_amigo` FOREIGN KEY (`id_amigo`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_amizades_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `timeline` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comentario_parent` FOREIGN KEY (`parent_id`) REFERENCES `comentarios` (`id_comentario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `timeline` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_de`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_para`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens_chat`
--
ALTER TABLE `mensagens_chat`
  ADD CONSTRAINT `mensagens_chat_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `mensagens_privadas`
--
ALTER TABLE `mensagens_privadas`
  ADD CONSTRAINT `fk_mensagem_citacao` FOREIGN KEY (`citacao_id`) REFERENCES `mensagens_privadas` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_2` FOREIGN KEY (`id_remetente`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `timeline`
--
ALTER TABLE `timeline`
  ADD CONSTRAINT `timeline_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuario_configuracoes`
--
ALTER TABLE `usuario_configuracoes`
  ADD CONSTRAINT `fk_config_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
