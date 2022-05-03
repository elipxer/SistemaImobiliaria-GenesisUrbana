-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Nov-2021 às 02:15
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `imobiliaria`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `id_recipient` int(11) NOT NULL COMMENT 'Beneficiario id',
  `recipient` varchar(450) NOT NULL DEFAULT '0' COMMENT 'Beneficiario',
  `cnpj` char(18) NOT NULL DEFAULT '0',
  `name` varchar(450) NOT NULL DEFAULT '0' COMMENT 'Nome do banco',
  `id_bank` int(11) NOT NULL DEFAULT 0 COMMENT 'Id banco',
  `bank_agency` int(11) NOT NULL DEFAULT 0 COMMENT 'agencia do banco',
  `verifying_digit` int(11) NOT NULL DEFAULT 0 COMMENT 'digito verificador',
  `account` int(11) NOT NULL DEFAULT 0 COMMENT 'conta',
  `wallet` varchar(4) NOT NULL DEFAULT '0' COMMENT 'carteira',
  `byte` varchar(4) NOT NULL DEFAULT '0',
  `post` varchar(4) NOT NULL DEFAULT '0',
  `accept` varchar(1) NOT NULL DEFAULT '0' COMMENT 'Aceite',
  `kind_doc` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Especie doc',
  `street` varchar(450) NOT NULL DEFAULT '0',
  `number` varchar(10) DEFAULT '0',
  `neighborhood` varchar(150) NOT NULL DEFAULT '0',
  `city` varchar(150) NOT NULL DEFAULT '0',
  `uf` varchar(10) NOT NULL DEFAULT '0',
  `cep` char(10) NOT NULL DEFAULT '0',
  `description` text DEFAULT NULL,
  `bank_slip` tinyint(4) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `id_user_permission` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `banks`
--

INSERT INTO `banks` (`id`, `id_recipient`, `recipient`, `cnpj`, `name`, `id_bank`, `bank_agency`, `verifying_digit`, `account`, `wallet`, `byte`, `post`, `accept`, `kind_doc`, `street`, `number`, `neighborhood`, `city`, `uf`, `cep`, `description`, `bank_slip`, `status`, `id_user_permission`) VALUES
(1, 54725, 'Genesis Loteadora e Colonizadora S/S LTD', '00.664.563/0001-84', 'Sicredi', 748, 718, 0, 54725, '1', '2', '61', 'S', 'DM', 'Rua Pará', '0', 'Centro', 'Londrina', 'PR', '86020-400', 'Conta Padrão', 0, 1, '6'),
(2, 2147483647, 'Genesis Loteadora e Colonizadora S/S LTD', '00.664.563/0001-84', 'Caixa', 104, 3068, 4, 504695, 'RG', '2', '61', 'S', 'DM', 'Rua Pará', '0', 'Centro', 'Londrina', 'PR', '86020-400', 'Conta para gerar boletos com valores acima de R$ 1000, 00', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bank_slip`
--

CREATE TABLE `bank_slip` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `id_parcel` int(11) NOT NULL DEFAULT 0,
  `descont` float NOT NULL,
  `bank_interest_rate` float NOT NULL COMMENT 'taxa',
  `fine` float NOT NULL,
  `id_financial_accounts` int(11) NOT NULL DEFAULT 0,
  `delay_limit` int(11) NOT NULL DEFAULT 0 COMMENT 'limite de atraso',
  `observation` text DEFAULT NULL,
  `path` varchar(450) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1-Gerado Remessa; 2-Pendente',
  `our_number` char(5) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `path_send_file` varchar(450) NOT NULL DEFAULT '',
  `add_now` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bank_slip`
--

INSERT INTO `bank_slip` (`id`, `id_sale`, `id_parcel`, `descont`, `bank_interest_rate`, `fine`, `id_financial_accounts`, `delay_limit`, `observation`, `path`, `status`, `our_number`, `date`, `time`, `path_send_file`, `add_now`) VALUES
(4, 14, 4278, 10, 1, 2, 1, 90, NULL, '6c61aa5c650b5fb2d1c0e1cd32d81703.pdf', 1, '85366', '2021-10-27', '11:31:24', '', 0),
(5, 14, 4279, 10, 1, 2, 1, 90, NULL, '95043d2dc9863ca9b58810fa1600984f.pdf', 1, '45860', '2021-10-27', '11:31:24', '', 0),
(6, 14, 4280, 10, 1, 2, 1, 90, NULL, '78ec7655acab4e5299b26eab8191d09e.pdf', 1, '61493', '2021-10-27', '11:31:24', '', 0),
(7, 14, 4281, 10, 1, 2, 1, 90, NULL, 'e0e296a35d60d04b1507b3b30a99452d.pdf', 1, '31480', '2021-10-27', '11:31:25', '', 0),
(8, 14, 4282, 10, 1, 2, 1, 90, NULL, '7a71049cc0c6fab0b05b0b6509bfb34a.pdf', 1, '33634', '2021-10-28', '22:18:59', '', 0),
(9, 14, 4283, 10, 1, 2, 1, 90, NULL, 'cd44ad74ec2d1ae41970ac99d62aeac9.pdf', 1, '74127', '2021-10-28', '22:19:00', '', 0),
(10, 14, 4284, 10, 1, 2, 1, 90, NULL, 'e647c7c48915af2bc1d8273b33be861f.pdf', 2, '90850', '2021-10-28', '22:19:00', '', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bank_slip_return`
--

CREATE TABLE `bank_slip_return` (
  `id` int(11) NOT NULL,
  `id_financial_account` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `path_file_return` varchar(100) NOT NULL,
  `updated_parcels` tinyint(4) NOT NULL,
  `add_now` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bank_slip_return`
--

INSERT INTO `bank_slip_return` (`id`, `id_financial_account`, `date`, `path_file_return`, `updated_parcels`, `add_now`) VALUES
(1, 1, '2021-10-28', 'bankSlip/returnFile/3de27d5aca6669d477c2826cbc55c01a.CRT', 0, 1),
(2, 1, '2021-11-10', 'bankSlip/returnFile/3de27d5aca6669d477c2826cbc55c01a.CRT', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bank_slip_return_info`
--

CREATE TABLE `bank_slip_return_info` (
  `id` int(11) NOT NULL,
  `id_bankSlipReturn` int(11) NOT NULL DEFAULT 0,
  `our_number` int(11) NOT NULL DEFAULT 0,
  `ocorrency` int(11) NOT NULL DEFAULT 0,
  `ocorrencyDescription` varchar(50) NOT NULL DEFAULT '0',
  `dateOccorency` date NOT NULL,
  `deadLine` date NOT NULL,
  `creditDate` date DEFAULT NULL,
  `amount_received` varchar(50) NOT NULL,
  `value_descont` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL DEFAULT '0',
  `value_rate` varchar(50) NOT NULL DEFAULT '0',
  `value_mora` varchar(50) NOT NULL DEFAULT '0',
  `value_fine` varchar(50) NOT NULL DEFAULT '0',
  `view` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bank_slip_return_info`
--

INSERT INTO `bank_slip_return_info` (`id`, `id_bankSlipReturn`, `our_number`, `ocorrency`, `ocorrencyDescription`, `dateOccorency`, `deadLine`, `creditDate`, `amount_received`, `value_descont`, `value`, `value_rate`, `value_mora`, `value_fine`, `view`) VALUES
(1, 1, 212549802, 2, 'Entrada confirmada', '2021-10-05', '2021-10-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(2, 1, 212800440, 2, 'Entrada confirmada', '2021-10-05', '2021-11-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(3, 1, 212460950, 2, 'Entrada confirmada', '2021-10-05', '2021-12-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(4, 1, 212248365, 2, 'Entrada confirmada', '2021-10-05', '2022-01-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(5, 1, 212513778, 2, 'Entrada confirmada', '2021-10-05', '2022-02-28', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(6, 1, 212131573, 2, 'Entrada confirmada', '2021-10-05', '2022-03-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(7, 1, 212807810, 2, 'Entrada confirmada', '2021-10-05', '2022-04-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(8, 1, 212200796, 2, 'Entrada confirmada', '2021-10-05', '2022-05-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(9, 1, 212301274, 2, 'Entrada confirmada', '2021-10-05', '2022-06-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(10, 1, 212534104, 3, 'Entrada rejeitada', '2021-10-05', '2021-12-15', NULL, '0', '0', '399.99', '0', '0', '0', 1),
(11, 1, 212996051, 2, 'Entrada confirmada', '2021-10-05', '2022-08-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(12, 1, 212797237, 2, 'Entrada confirmada', '2021-10-05', '2022-09-30', NULL, '0', '0', '2260.96', '0', '0', '0', 1),
(13, 1, 212926533, 3, 'Entrada rejeitada', '2021-10-05', '2021-09-30', NULL, '0', '0', '1196.39', '0', '0', '0', 1),
(14, 1, 212378587, 2, 'Entrada confirmada', '2021-10-05', '2021-10-30', NULL, '0', '0', '1171.02', '0', '0', '0', 1),
(15, 1, 202145300, 6, 'Liquidação normal', '2021-10-05', '2021-09-30', '2021-10-06', '571.09', '0', '558.97', '0', '0.94', '11.18', 1),
(16, 1, 202430756, 6, 'Liquidação normal', '2021-10-05', '2021-10-05', '2021-10-06', '180', '0', '180', '0', '0', '0', 1),
(17, 1, 211000254, 6, 'Liquidação normal', '2021-10-05', '2021-09-30', '2021-10-06', '1351.48', '0', '1263.07', '0', '63.15', '25.26', 1),
(18, 1, 212464378, 6, 'Liquidação normal', '2021-10-05', '2021-09-10', '2021-10-06', '411.23', '0', '399.99', '0', '3.24', '8', 1),
(19, 1, 212688659, 6, 'Liquidação normal', '2021-10-05', '2021-08-25', '2021-10-06', '1190.62', '0', '1152', '0', '15.58', '23.04', 1),
(20, 1, 202145300, 28, 'Tarifa', '2021-10-05', '2021-09-30', '2021-10-05', '2.8', '0', '558.97', '2.8', '0', '0', 1),
(21, 1, 202430756, 28, 'Tarifa', '2021-10-05', '2021-10-05', '2021-10-05', '2.8', '0', '180', '2.8', '0', '0', 1),
(22, 1, 211000254, 28, 'Tarifa', '2021-10-05', '2021-09-30', '2021-10-05', '2.8', '0', '1263.07', '2.8', '0', '0', 1),
(23, 1, 212464378, 28, 'Tarifa', '2021-10-05', '2021-09-10', '2021-10-05', '2.8', '0', '399.99', '2.8', '0', '0', 1),
(24, 1, 212688659, 28, 'Tarifa', '2021-10-05', '2021-08-25', '2021-10-05', '2.8', '0', '1152', '2.8', '0', '0', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bank_slip_sends`
--

CREATE TABLE `bank_slip_sends` (
  `id` int(11) NOT NULL,
  `ids_bankSlip` text NOT NULL,
  `path_send_file` varchar(450) NOT NULL DEFAULT '0',
  `send_file_name` varchar(50) NOT NULL DEFAULT '0',
  `id_financial_accounts` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `add_now` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bank_slip_sends`
--

INSERT INTO `bank_slip_sends` (`id`, `ids_bankSlip`, `path_send_file`, `send_file_name`, `id_financial_accounts`, `date`, `add_now`) VALUES
(1, '1,2,3', '0', '54725725.CRM', 1, '2021-07-25', 0),
(2, '4,5,6,7', '0', '54725O28.CRM', 1, '2021-10-28', 0),
(3, '8,9', '0', '54725N03.CRM', 1, '2021-11-03', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cancel_contact_info`
--

CREATE TABLE `cancel_contact_info` (
  `id` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `future_value` varchar(50) DEFAULT NULL,
  `sale_commission_rate` int(11) DEFAULT NULL,
  `administrative_expenses` int(11) DEFAULT NULL,
  `iptu_debits` varchar(50) DEFAULT NULL,
  `others_debits` varchar(50) DEFAULT NULL,
  `administrative_debits` varchar(50) DEFAULT NULL,
  `specification_debits` varchar(50) DEFAULT NULL,
  `total_parcels_pad` varchar(50) DEFAULT NULL,
  `sale_commission` varchar(50) DEFAULT NULL,
  `sale_commission_adjusted` varchar(50) DEFAULT '',
  `return_value` varchar(50) DEFAULT NULL,
  `value_parcel_return` varchar(50) DEFAULT '0,00',
  `first_parcel_return` date DEFAULT NULL,
  `number_parcels_return` varchar(50) NOT NULL,
  `path_document_done` varchar(450) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `change_lot_info`
--

CREATE TABLE `change_lot_info` (
  `id` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL DEFAULT 0,
  `id_sale` int(11) NOT NULL,
  `old_lot` varchar(450) NOT NULL DEFAULT '',
  `old_lot_value` varchar(50) NOT NULL DEFAULT '',
  `lot_selected` varchar(450) NOT NULL DEFAULT '',
  `id_lot_selected` int(11) NOT NULL DEFAULT 0,
  `value_lot_selected` varchar(50) NOT NULL DEFAULT '',
  `total_parcels_pad` varchar(50) NOT NULL DEFAULT '',
  `new_value_pay` varchar(50) NOT NULL DEFAULT '',
  `number_parcels_pad` varchar(50) NOT NULL DEFAULT '',
  `number_parcels_to_pay` varchar(50) NOT NULL DEFAULT '',
  `value_after_change` varchar(50) NOT NULL DEFAULT '',
  `number_parcel_change_lot` varchar(50) NOT NULL DEFAULT '',
  `rate_financing` varchar(15) NOT NULL DEFAULT '',
  `value_parcel_change_lot` varchar(50) NOT NULL DEFAULT '',
  `first_parcel` date DEFAULT NULL,
  `path_document_done` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `change_owner_info`
--

CREATE TABLE `change_owner_info` (
  `id` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL DEFAULT 0,
  `id_sale` int(11) NOT NULL DEFAULT 0,
  `old_clients` text NOT NULL,
  `old_client_payment` int(11) NOT NULL DEFAULT 0,
  `old_clients_porc` text NOT NULL,
  `clients` text NOT NULL,
  `client_payment` int(11) NOT NULL,
  `clients_porc` text NOT NULL,
  `path_document_done` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(450) DEFAULT NULL,
  `company_name` varchar(450) DEFAULT NULL,
  `fantasy_name` varchar(450) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `kind_person` tinyint(4) DEFAULT NULL COMMENT '1-Pessoa Física; 2-Pessoa Juridica',
  `rg` varchar(30) DEFAULT NULL,
  `emitting_organ` varchar(30) DEFAULT NULL COMMENT 'Orgão Emissor RG',
  `cpf` char(14) DEFAULT NULL,
  `cnpj` char(18) DEFAULT NULL,
  `marital_status` tinyint(4) DEFAULT NULL COMMENT '1-Solteiro; 2-Casado; 3-Divorciado',
  `nationality` tinyint(4) DEFAULT NULL COMMENT '1-Brasileiro; 2-Estrangeiro',
  `sex` tinyint(4) DEFAULT NULL COMMENT '1-Masculino; 2-Feminino',
  `email` varchar(450) DEFAULT NULL,
  `occupation` varchar(450) DEFAULT NULL COMMENT 'Profissão',
  `street` varchar(450) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `complement` varchar(450) DEFAULT NULL,
  `cep` char(9) DEFAULT NULL,
  `neighborhood` varchar(450) DEFAULT NULL COMMENT 'Bairro',
  `city` varchar(450) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `spouse_name` varchar(450) DEFAULT NULL COMMENT 'Nome Cônjuge',
  `spouse_birth_date` date DEFAULT NULL COMMENT 'Data Nascimento Cônjuge',
  `spouse_rg` varchar(30) DEFAULT NULL COMMENT 'RG Cônjuge',
  `spouse_cpf` char(14) DEFAULT NULL COMMENT 'CPF Cônjuge',
  `spouse_emitting_organ` varchar(30) DEFAULT NULL COMMENT 'Orgão Emissor Cônjuge',
  `spouse_nationality` tinyint(4) DEFAULT NULL COMMENT '1-Brasileiro; 2-Estrangeiro',
  `spouse_sex` tinyint(4) DEFAULT NULL COMMENT '1-Masculino; 2-Feminino',
  `spouse_email` varchar(450) DEFAULT NULL COMMENT 'Email Cônjuge',
  `spouse_occupation` varchar(450) DEFAULT NULL COMMENT 'Profissão Cônjuge',
  `street_payment_collection` varchar(450) DEFAULT NULL COMMENT 'Rua Cobrança',
  `number_payment_collection` int(11) DEFAULT NULL COMMENT 'Numero Cobrança',
  `neighborhood_payment_collection` varchar(450) DEFAULT NULL COMMENT 'Bairro Cobrança',
  `city_payment_collection` varchar(450) DEFAULT NULL COMMENT 'Cidade Cobrança',
  `complement_payment_collection` varchar(450) DEFAULT NULL COMMENT 'Complemento Cobrança',
  `state_payment_collection` varchar(2) DEFAULT NULL,
  `cep_payment_collection` varchar(9) DEFAULT NULL,
  `phones` text DEFAULT NULL,
  `id_representative` int(11) DEFAULT NULL,
  `whatsAppNumber` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `clients`
--

INSERT INTO `clients` (`id`, `name`, `company_name`, `fantasy_name`, `birth_date`, `kind_person`, `rg`, `emitting_organ`, `cpf`, `cnpj`, `marital_status`, `nationality`, `sex`, `email`, `occupation`, `street`, `number`, `complement`, `cep`, `neighborhood`, `city`, `state`, `spouse_name`, `spouse_birth_date`, `spouse_rg`, `spouse_cpf`, `spouse_emitting_organ`, `spouse_nationality`, `spouse_sex`, `spouse_email`, `spouse_occupation`, `street_payment_collection`, `number_payment_collection`, `neighborhood_payment_collection`, `city_payment_collection`, `complement_payment_collection`, `state_payment_collection`, `cep_payment_collection`, `phones`, `id_representative`, `whatsAppNumber`) VALUES
(1, 'Mauricio Ferreira de Jesus', NULL, NULL, NULL, 1, NULL, NULL, '351.942.148-80', '', 1, 1, 1, NULL, NULL, 'Rua Raul Seixas', 114, NULL, '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, 'Rua Raul Seixas', 114, 'Vila Roseira II', 'São Paulo', NULL, 'AC', '08466-010', '(11)99999-9999', NULL, '(11)99999-9999'),
(29, NULL, 'MARCENEIRO ZEN', NULL, NULL, 2, NULL, NULL, NULL, '36.507.071/0001-22', 1, 1, 1, 'brunovelletto@hotmail.com', NULL, 'Rua Raul Seixas', NULL, NULL, '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, 'Rua Raul Seixas', NULL, 'Vila Roseira II', 'São Paulo', NULL, 'AC', '08466-010', '(11)99999-9999', 33, '(11)99999-9999'),
(33, 'Teste', NULL, NULL, NULL, 1, NULL, NULL, '782.190.460-07', '', 2, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', 'Leticia', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, '', NULL, NULL),
(34, NULL, 'Razao Teste', 'dfsafdfasdfsdf', NULL, 2, NULL, NULL, NULL, '92.950.512/0001-37', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AC', NULL, '', 35, NULL),
(59, 'Teste', NULL, NULL, NULL, 1, NULL, NULL, '374.909.810-77', NULL, 1, 2, 1, NULL, NULL, 'Rua Raul Seixas', NULL, NULL, '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Rua Raul Seixas', NULL, 'Vila Roseira II', 'São Paulo', NULL, 'SP', '08466-010', NULL, NULL, NULL),
(60, NULL, 'GENESIS LOTEADORA E COLONIZADORA S/S LTDA', NULL, NULL, 2, NULL, NULL, NULL, '00.664.563/0001-84', 1, 1, 1, 'imada@triploa.com', NULL, 'R PARA', 1531, 'SALA 802', '86020-400', 'CENTRO', 'LONDRINA', 'PR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'R PARA', 1531, 'CENTRO', 'LONDRINA', 'SALA 802', 'PR', '86020-400', NULL, 59, NULL),
(61, 'teste', NULL, NULL, '2021-10-15', 1, '38.555.419-9', NULL, '097.342.190-81', '', 1, 1, 1, NULL, NULL, 'Rua Raul Seixas', NULL, NULL, '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Rua Raul Seixas', NULL, 'Vila Roseira II', 'São Paulo', NULL, 'SP', '08466-010', '(11)56565-6565,(44)54545-4545', NULL, '(11)56565-6565');

-- --------------------------------------------------------

--
-- Estrutura da tabela `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` varchar(450) NOT NULL,
  `cnpj` char(18) NOT NULL,
  `street` varchar(450) NOT NULL,
  `number` varchar(50) NOT NULL,
  `complement` varchar(450) DEFAULT NULL,
  `cep` char(14) NOT NULL,
  `neighborhood` varchar(450) NOT NULL,
  `city` varchar(450) NOT NULL,
  `state` varchar(2) NOT NULL,
  `representative_name` varchar(450) DEFAULT NULL,
  `representative_marital_status` tinyint(4) DEFAULT NULL,
  `representative_occupation` varchar(100) DEFAULT NULL,
  `representative_rg` varchar(50) DEFAULT NULL,
  `representative_cpf` char(14) DEFAULT NULL,
  `representative_street` varchar(50) DEFAULT NULL,
  `representative_number` varchar(50) DEFAULT NULL,
  `representative_complement` varchar(50) DEFAULT NULL,
  `representative_cep` char(14) DEFAULT NULL,
  `representative_neighborhood` varchar(450) DEFAULT NULL,
  `representative_city` varchar(450) DEFAULT NULL,
  `representative_state` varchar(2) DEFAULT NULL,
  `representative_nationality` tinyint(4) DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `cnpj`, `street`, `number`, `complement`, `cep`, `neighborhood`, `city`, `state`, `representative_name`, `representative_marital_status`, `representative_occupation`, `representative_rg`, `representative_cpf`, `representative_street`, `representative_number`, `representative_complement`, `representative_cep`, `representative_neighborhood`, `representative_city`, `representative_state`, `representative_nationality`, `status`) VALUES
(1, 'GENESIS LOTEADORA E COLONIZADORA S/S LTDA', '00.664.563/0001-84', 'R PARA', '1531', 'SALA 802', '86020-400', 'CENTRO', 'LONDRINA', 'PR', 'CARLOS SHIGUERU IMADA', 2, 'ENGENHEIRO CIVIL', '536.905 SSP/MS', '595.291.481-00', 'R PARA', '1531', 'SALA 802', '86020-400', 'CENTRO', 'LONDRINA', 'PR', 1, 1),
(2, 'MARCENEIRO ZEN', '36.507.071/0001-22', 'RUA 801', '354', 'APT 501', '88330-717', 'CENTRO', 'BALNEARIO CAMBORIU', 'SC', 'Mauricio', 1, 'Programador', '6565456465445', '782.190.460-07', 'Rua Raul Seixas', '114', 'dsfasfs', '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', 1, 1),
(3, 'Teste', '75.822.808/0001-53', 'Rua Raul Seixas', '114', 'sdfsadfasdfsfda', '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', NULL, 1, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 1, 1),
(4, 'Teste', '75.822.808/0001-53', 'Rua Raul Seixas', '114', 'asdsadsa', '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', NULL, 1, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contact_sale`
--

CREATE TABLE `contact_sale` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL DEFAULT 0,
  `contact_client_name` varchar(450) NOT NULL,
  `where` varchar(450) NOT NULL DEFAULT '0',
  `subject_matter` text NOT NULL,
  `deadline` date NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `contactFile` varchar(50) DEFAULT NULL,
  `register_date` date NOT NULL,
  `expired_day` int(11) DEFAULT NULL,
  `solution` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `contact_sale`
--

INSERT INTO `contact_sale` (`id`, `id_sale`, `id_user`, `contact_client_name`, `where`, `subject_matter`, `deadline`, `type`, `status`, `contactFile`, `register_date`, `expired_day`, `solution`) VALUES
(8, 14, 1, 'mauricio', 'whatsapp', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', '2021-09-16', 4, 1, NULL, '2021-09-13', NULL, NULL),
(11, 14, 1, 'mauricio', 'whatsapp', 'dsfjklasfkjfds', '2021-10-28', 1, 1, NULL, '2021-10-28', NULL, NULL),
(12, 14, 1, 'mauricio', 'whatsapp', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', '2021-10-28', 1, 1, NULL, '2021-10-28', NULL, NULL),
(13, 14, 1, 'mauricio', 'whatsapp', 'saddddddddddd', '2021-11-17', 1, 1, NULL, '2021-11-17', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `index`
--

CREATE TABLE `index` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `index`
--

INSERT INTO `index` (`id`, `name`, `date`, `time`) VALUES
(5, 'IGPM', '2020-12-29', '01:24:19');

-- --------------------------------------------------------

--
-- Estrutura da tabela `index_value`
--

CREATE TABLE `index_value` (
  `id` int(11) NOT NULL,
  `value` varchar(50) NOT NULL,
  `idIndex` int(11) NOT NULL,
  `month` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `index_value`
--

INSERT INTO `index_value` (`id`, `value`, `idIndex`, `month`) VALUES
(4, '1', 5, '2020-03-01'),
(5, '4', 5, '2020-04-01'),
(7, '1', 5, '2020-06-01'),
(8, '2', 5, '2020-07-01'),
(22, '1', 5, '2020-05-01'),
(23, '1', 5, '2020-08-01'),
(25, '1', 5, '2020-09-01'),
(35, '1', 5, '2020-10-01'),
(38, '1', 5, '2020-02-01'),
(42, '1', 5, '2020-01-01'),
(44, '1', 5, '2020-11-01'),
(46, '-0.44444', 5, '2021-02-01'),
(51, '-0.5555', 5, '2021-03-01'),
(55, '1', 5, '2021-01-01'),
(56, '1', 5, '2020-12-01'),
(57, '1', 5, '2010-03-01'),
(58, '1', 5, '2010-04-01'),
(59, '1', 5, '2010-05-01'),
(60, '1', 5, '2010-06-01'),
(61, '1', 5, '2010-07-01'),
(62, '1', 5, '2010-08-01'),
(63, '1', 5, '2010-09-01'),
(64, '1', 5, '2010-10-01'),
(65, '1', 5, '2010-11-01'),
(66, '1', 5, '2010-12-01'),
(67, '1', 5, '2011-01-01'),
(68, '1', 5, '2011-02-01'),
(69, '1', 5, '2011-03-01'),
(70, '1', 5, '2011-04-01'),
(71, '1', 5, '2011-05-01'),
(72, '1', 5, '2011-06-01'),
(73, '1', 5, '2011-07-01'),
(75, '1', 5, '2021-04-01'),
(76, '1', 5, '2021-05-01'),
(77, '1', 5, '2021-06-01'),
(78, '1', 5, '2021-07-01'),
(79, '5', 5, '2021-08-01'),
(82, '1', 5, '2021-09-01');

-- --------------------------------------------------------

--
-- Estrutura da tabela `internal_accounts`
--

CREATE TABLE `internal_accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(8) NOT NULL DEFAULT '0',
  `description` varchar(450) NOT NULL DEFAULT '0',
  `id_user_permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `internal_accounts`
--

INSERT INTO `internal_accounts` (`id`, `name`, `description`, `id_user_permission`) VALUES
(2, 'Teste ED', 'dfsdfdf56445646', ''),
(3, 'Internal', 'sdfdsfsdfsadf', ''),
(6, 'Teste2', 'asdddddddddddddddddddddddddddddddddddddd', '5');

-- --------------------------------------------------------

--
-- Estrutura da tabela `interprises`
--

CREATE TABLE `interprises` (
  `id` int(11) NOT NULL,
  `name` varchar(450) NOT NULL DEFAULT '0',
  `city` varchar(450) NOT NULL DEFAULT '0',
  `state` varchar(2) NOT NULL DEFAULT '0',
  `observation` text DEFAULT NULL,
  `date` date NOT NULL,
  `company_ids` text DEFAULT NULL,
  `company_perc` text DEFAULT NULL,
  `id_user_permission` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `interprises`
--

INSERT INTO `interprises` (`id`, `name`, `city`, `state`, `observation`, `date`, `company_ids`, `company_perc`, `id_user_permission`) VALUES
(1, 'Empreendimento Teste', 'São Paulo', 'SP', NULL, '2020-12-19', '1,2', '1-50,2-50', '5'),
(2, 'Empreendimento  sdffasdfsdf', 'São Paulo', 'SP', 'dafssssssssssssssssss', '2020-12-20', '1', '1-100', NULL),
(3, 'Empreendimento Novo', 'São Paulo', 'SP', 'SADASDSADSADASD', '2021-02-09', '1', '1-100', NULL),
(6, 'teste empreendimento usuario', 'são paulo', 'SP', 'sadadadadadadadadadadadadad', '2021-04-11', '1', '1-100', ''),
(7, 'Novo Empreendimento', 'São Paulo', 'SP', NULL, '2021-10-27', '3', '3-100', '5');

-- --------------------------------------------------------

--
-- Estrutura da tabela `juridical_contacts`
--

CREATE TABLE `juridical_contacts` (
  `id` int(11) NOT NULL,
  `situation` text NOT NULL,
  `file_path` varchar(200) DEFAULT NULL,
  `id_juridicalUser` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `register_date` date NOT NULL,
  `register_time` time NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0- Pendente; \r\n1-Respondido pelo usuario juridico; \r\n2-Resolvido;\r\n 3-Autorizado a ação juridica (Aguardando Documentos);\r\n 4-documentos recebidos (Processo em andamento)',
  `resolution` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `date_authorization_juridical` date DEFAULT NULL,
  `document_juridical` varchar(200) DEFAULT NULL,
  `process_number` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `juridical_updates`
--

CREATE TABLE `juridical_updates` (
  `id` int(11) NOT NULL,
  `id_juridical` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) DEFAULT 0,
  `id_juridicalUser` int(11) DEFAULT 0,
  `update_decription` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `document` varchar(200) DEFAULT '',
  `update` tinyint(4) NOT NULL,
  `subject` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `juridical_updates`
--

INSERT INTO `juridical_updates` (`id`, `id_juridical`, `id_user`, `id_juridicalUser`, `update_decription`, `date`, `time`, `document`, `update`, `subject`) VALUES
(3, 1, 1, NULL, 'sdf asdfasdf asdfsdaffffffffffffffffff sdfaaaaaaaaa sdaf', '2021-03-24', '20:57:01', 'contactFiles/juridical/39764e70e03fe5f00db7fd03ea09244d.pdf', 0, 'dfasfasf'),
(4, 1, 1, NULL, 'c\\c\\ sadaaaaaaaaaaaaaaaaaaaa asdddddddddddddddddddd', '2021-03-24', '18:11:53', '', 0, 'asdfasdfasdf'),
(5, 1, NULL, 3, 'dasdasdsa sad afsssssssssssadsf                  dsafffffffffffffff', '2021-03-24', '18:33:43', '', 0, 'afdadsasdf'),
(6, 1, NULL, 3, 'asddddddddddddd asdddddddddddddddddddddd', '2021-03-24', '18:59:37', 'contactFiles/juridical/524cbd94781057b1d4c230a463c2b7d4.pdf', 0, 'adfsdsfdasfdfad'),
(7, 1, 1, NULL, 'asdddddddddddddddddddddddddddd dsaaaaaaaaaaaaa', '2021-03-24', '19:01:30', '', 0, 'asdfafdfdfsds'),
(8, 2, NULL, 3, 'hjhjkhdsad sadasjdjksadh', '2021-03-25', '14:45:48', '', 0, 'sdfsdfsdfsd');

-- --------------------------------------------------------

--
-- Estrutura da tabela `lots`
--

CREATE TABLE `lots` (
  `id` int(11) NOT NULL,
  `id_interprise` int(11) NOT NULL DEFAULT 0,
  `name` varchar(450) NOT NULL DEFAULT '0',
  `lot_number` int(11) NOT NULL,
  `block` varchar(450) NOT NULL,
  `area` varchar(450) NOT NULL,
  `confrontations` text DEFAULT NULL,
  `visible` tinyint(4) DEFAULT NULL,
  `registration_number` varchar(450) DEFAULT NULL,
  `municipal_registration` varchar(450) DEFAULT NULL,
  `present_value` varchar(30) NOT NULL,
  `future_value` varchar(30) NOT NULL,
  `input` varchar(30) DEFAULT NULL,
  `descont` varchar(30) DEFAULT NULL,
  `available` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `lots`
--

INSERT INTO `lots` (`id`, `id_interprise`, `name`, `lot_number`, `block`, `area`, `confrontations`, `visible`, `registration_number`, `municipal_registration`, `present_value`, `future_value`, `input`, `descont`, `available`) VALUES
(4, 2, 'Quadra B Lot 10', 10, 'B', '1500', 'saddddddddddddddddddddddd', 1, NULL, NULL, '25.000,00', '30.000,00', '5.000,00', NULL, 2),
(5, 1, 'Quadra ER; Lot 88', 88, 'ER', '5000', 'saddddddddddddddddddddddd', NULL, NULL, NULL, '100.000,00', '150.000,00', '5.000,00', NULL, 1),
(6, 2, 'Quadra C; Lot 55', 55, 'C', '8000', 'saddddddddddddddddddddddd', NULL, NULL, NULL, '50.000,00', '65.000,00', '1.000,00', NULL, 1),
(7, 1, 'Quadra ER Lot 878', 878, 'ER', '5000', 'saddddddddddddddddddddddd', 1, NULL, NULL, '1.000,00', '1.500,00', NULL, NULL, 1),
(8, 6, 'Quadra fdssdf; Lot 8787', 8787, 'fdssdf', '68989', NULL, NULL, NULL, NULL, '150,00', '2.000,00', '1.500,00', NULL, 1),
(9, 6, 'Quadra C Lot 155555', 155555, 'C', '5000', 'dsafffffffffffffffa', 1, '34.567', '101', '75.000,00', '115.000,00', '7.500,00', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notification_index_value`
--

CREATE TABLE `notification_index_value` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL DEFAULT 0,
  `month_index_empty` text NOT NULL,
  `done` tinyint(4) NOT NULL DEFAULT 0,
  `parcels_readjust` text NOT NULL,
  `index` int(11) NOT NULL DEFAULT 0,
  `type` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `notification_index_value`
--

INSERT INTO `notification_index_value` (`id`, `id_sale`, `month_index_empty`, `done`, `parcels_readjust`, `index`, `type`) VALUES
(6, 15, '', 1, ' Indices das datas: 2021-09-01', 5, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL DEFAULT 0,
  `num` int(11) DEFAULT 0,
  `date` date NOT NULL,
  `value` varchar(50) NOT NULL,
  `updated_value` varchar(20) DEFAULT NULL,
  `added_value` varchar(20) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `reajust` varchar(50) DEFAULT NULL,
  `prefix` varchar(50) DEFAULT NULL,
  `late_fine` varchar(50) DEFAULT NULL,
  `late_rate` varchar(50) DEFAULT NULL,
  `late_days` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `id_contact` int(11) DEFAULT NULL,
  `num_reissue` text DEFAULT NULL,
  `link` varchar(450) DEFAULT NULL,
  `our_number` char(5) NOT NULL,
  `send_bankSlip` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0- remessa mão feita; 1-remessa feita; 2-pendente',
  `pad_value` varchar(50) DEFAULT '0,00',
  `pad_date` date DEFAULT NULL,
  `pad_description` varchar(450) DEFAULT NULL,
  `idBankPayment` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `parcels`
--

INSERT INTO `parcels` (`id`, `id_sale`, `num`, `date`, `value`, `updated_value`, `added_value`, `status`, `reajust`, `prefix`, `late_fine`, `late_rate`, `late_days`, `type`, `id_contact`, `num_reissue`, `link`, `our_number`, `send_bankSlip`, `pad_value`, `pad_date`, `pad_description`, `idBankPayment`) VALUES
(4277, 14, 1, '2021-09-30', '43,34', '43,34', NULL, '1', NULL, NULL, NULL, NULL, NULL, 3, 8, NULL, NULL, '85354', 0, '43,34', '2021-09-13', 'sadfffffffffffffff', 1),
(4278, 14, 1, '2021-10-13', '150,70', '155,47', '4.77', '3', NULL, '', '3.01 (0,2%)', '1.76 (0.012%)', 35, 1, NULL, NULL, '6c61aa5c650b5fb2d1c0e1cd32d81703.pdf', '85366', 2, '0,00', NULL, NULL, 1),
(4279, 14, 2, '2021-11-13', '150,70', '153,91', '3.21', '3', NULL, '', '3.01 (0,2%)', '0.20 (0.001%)', 4, 1, NULL, NULL, '95043d2dc9863ca9b58810fa1600984f.pdf', '45860', 2, '0,00', NULL, NULL, 1),
(4280, 14, 3, '2021-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, '78ec7655acab4e5299b26eab8191d09e.pdf', '61493', 2, '0,00', NULL, NULL, 1),
(4281, 14, 4, '2022-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, 'e0e296a35d60d04b1507b3b30a99452d.pdf', '31480', 2, '0,00', NULL, NULL, 1),
(4282, 14, 5, '2022-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, '7a71049cc0c6fab0b05b0b6509bfb34a.pdf', '33634', 2, '0,00', NULL, NULL, 1),
(4283, 14, 6, '2022-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, 'cd44ad74ec2d1ae41970ac99d62aeac9.pdf', '74127', 2, '0,00', NULL, NULL, 1),
(4284, 14, 7, '2022-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, 'e647c7c48915af2bc1d8273b33be861f.pdf', '90850', 2, '0,00', NULL, NULL, NULL),
(4285, 14, 8, '2022-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31643', 0, '0,00', NULL, NULL, NULL),
(4286, 14, 9, '2022-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '74816', 0, '0,00', NULL, NULL, NULL),
(4287, 14, 10, '2022-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '51134', 0, '0,00', NULL, NULL, NULL),
(4288, 14, 11, '2022-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '55217', 0, '0,00', NULL, NULL, NULL),
(4289, 14, 12, '2022-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '81312', 0, '0,00', NULL, NULL, NULL),
(4290, 14, 13, '2022-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '15236', 0, '0,00', NULL, NULL, NULL),
(4291, 14, 14, '2022-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '33208', 0, '0,00', NULL, NULL, NULL),
(4292, 14, 15, '2022-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '53461', 0, '0,00', NULL, NULL, NULL),
(4293, 14, 16, '2023-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '78334', 0, '0,00', NULL, NULL, NULL),
(4294, 14, 17, '2023-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '82057', 0, '0,00', NULL, NULL, NULL),
(4295, 14, 18, '2023-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '88099', 0, '0,00', NULL, NULL, NULL),
(4296, 14, 19, '2023-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '30815', 0, '0,00', NULL, NULL, NULL),
(4297, 14, 20, '2023-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '18672', 0, '0,00', NULL, NULL, NULL),
(4298, 14, 21, '2023-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '54275', 0, '0,00', NULL, NULL, NULL),
(4299, 14, 22, '2023-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '22562', 0, '0,00', NULL, NULL, NULL),
(4300, 14, 23, '2023-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '90063', 0, '0,00', NULL, NULL, NULL),
(4301, 14, 24, '2023-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '87816', 0, '0,00', NULL, NULL, NULL),
(4302, 14, 25, '2023-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '96252', 0, '0,00', NULL, NULL, NULL),
(4303, 14, 26, '2023-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '96116', 0, '0,00', NULL, NULL, NULL),
(4304, 14, 27, '2023-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '29967', 0, '0,00', NULL, NULL, NULL),
(4305, 14, 28, '2024-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '62437', 0, '0,00', NULL, NULL, NULL),
(4306, 14, 29, '2024-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '44354', 0, '0,00', NULL, NULL, NULL),
(4307, 14, 30, '2024-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16351', 0, '0,00', NULL, NULL, NULL),
(4308, 14, 31, '2024-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '93333', 0, '0,00', NULL, NULL, NULL),
(4309, 14, 32, '2024-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '32426', 0, '0,00', NULL, NULL, NULL),
(4310, 14, 33, '2024-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '19595', 0, '0,00', NULL, NULL, NULL),
(4311, 14, 34, '2024-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '57312', 0, '0,00', NULL, NULL, NULL),
(4312, 14, 35, '2024-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '97709', 0, '0,00', NULL, NULL, NULL),
(4313, 14, 36, '2024-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '17660', 0, '0,00', NULL, NULL, NULL),
(4314, 14, 37, '2024-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '78679', 0, '0,00', NULL, NULL, NULL),
(4315, 14, 38, '2024-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '59272', 0, '0,00', NULL, NULL, NULL),
(4316, 14, 39, '2024-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '19436', 0, '0,00', NULL, NULL, NULL),
(4317, 14, 40, '2025-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '75347', 0, '0,00', NULL, NULL, NULL),
(4318, 14, 41, '2025-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '79287', 0, '0,00', NULL, NULL, NULL),
(4319, 14, 42, '2025-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '21812', 0, '0,00', NULL, NULL, NULL),
(4320, 14, 43, '2025-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71670', 0, '0,00', NULL, NULL, NULL),
(4321, 14, 44, '2025-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '15887', 0, '0,00', NULL, NULL, NULL),
(4322, 14, 45, '2025-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '37820', 0, '0,00', NULL, NULL, NULL),
(4323, 14, 46, '2025-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45440', 0, '0,00', NULL, NULL, NULL),
(4324, 14, 47, '2025-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '53336', 0, '0,00', NULL, NULL, NULL),
(4325, 14, 48, '2025-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '97592', 0, '0,00', NULL, NULL, NULL),
(4326, 14, 49, '2025-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '41680', 0, '0,00', NULL, NULL, NULL),
(4327, 14, 50, '2025-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '28696', 0, '0,00', NULL, NULL, NULL),
(4328, 14, 51, '2025-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16719', 0, '0,00', NULL, NULL, NULL),
(4329, 14, 52, '2026-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '79724', 0, '0,00', NULL, NULL, NULL),
(4330, 14, 53, '2026-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '92601', 0, '0,00', NULL, NULL, NULL),
(4331, 14, 54, '2026-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '76902', 0, '0,00', NULL, NULL, NULL),
(4332, 14, 55, '2026-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '33619', 0, '0,00', NULL, NULL, NULL),
(4333, 14, 56, '2026-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '48315', 0, '0,00', NULL, NULL, NULL),
(4334, 14, 57, '2026-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71631', 0, '0,00', NULL, NULL, NULL),
(4335, 14, 58, '2026-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '78757', 0, '0,00', NULL, NULL, NULL),
(4336, 14, 59, '2026-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '91928', 0, '0,00', NULL, NULL, NULL),
(4337, 14, 60, '2026-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '51288', 0, '0,00', NULL, NULL, NULL),
(4338, 14, 61, '2026-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '76862', 0, '0,00', NULL, NULL, NULL),
(4339, 14, 62, '2026-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '56622', 0, '0,00', NULL, NULL, NULL),
(4340, 14, 63, '2026-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '42188', 0, '0,00', NULL, NULL, NULL),
(4341, 14, 64, '2027-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '83244', 0, '0,00', NULL, NULL, NULL),
(4342, 14, 65, '2027-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '40559', 0, '0,00', NULL, NULL, NULL),
(4343, 14, 66, '2027-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '30192', 0, '0,00', NULL, NULL, NULL),
(4344, 14, 67, '2027-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '48411', 0, '0,00', NULL, NULL, NULL),
(4345, 14, 68, '2027-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '40489', 0, '0,00', NULL, NULL, NULL),
(4346, 14, 69, '2027-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '18707', 0, '0,00', NULL, NULL, NULL),
(4347, 14, 70, '2027-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '79293', 0, '0,00', NULL, NULL, NULL),
(4348, 14, 71, '2027-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '77749', 0, '0,00', NULL, NULL, NULL),
(4349, 14, 72, '2027-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '28809', 0, '0,00', NULL, NULL, NULL),
(4350, 14, 73, '2027-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23729', 0, '0,00', NULL, NULL, NULL),
(4351, 14, 74, '2027-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '72879', 0, '0,00', NULL, NULL, NULL),
(4352, 14, 75, '2027-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '42701', 0, '0,00', NULL, NULL, NULL),
(4353, 14, 76, '2028-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '12046', 0, '0,00', NULL, NULL, NULL),
(4354, 14, 77, '2028-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '46165', 0, '0,00', NULL, NULL, NULL),
(4355, 14, 78, '2028-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '49633', 0, '0,00', NULL, NULL, NULL),
(4356, 14, 79, '2028-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '24040', 0, '0,00', NULL, NULL, NULL),
(4357, 14, 80, '2028-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31711', 0, '0,00', NULL, NULL, NULL),
(4358, 14, 81, '2028-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '12276', 0, '0,00', NULL, NULL, NULL),
(4359, 14, 82, '2028-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '44048', 0, '0,00', NULL, NULL, NULL),
(4360, 14, 83, '2028-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '68999', 0, '0,00', NULL, NULL, NULL),
(4361, 14, 84, '2028-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '35323', 0, '0,00', NULL, NULL, NULL),
(4362, 14, 85, '2028-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '74614', 0, '0,00', NULL, NULL, NULL),
(4363, 14, 86, '2028-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '34087', 0, '0,00', NULL, NULL, NULL),
(4364, 14, 87, '2028-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '76890', 0, '0,00', NULL, NULL, NULL),
(4365, 14, 88, '2029-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '90736', 0, '0,00', NULL, NULL, NULL),
(4366, 14, 89, '2029-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '78257', 0, '0,00', NULL, NULL, NULL),
(4367, 14, 90, '2029-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '70612', 0, '0,00', NULL, NULL, NULL),
(4368, 14, 91, '2029-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '46274', 0, '0,00', NULL, NULL, NULL),
(4369, 14, 92, '2029-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '39769', 0, '0,00', NULL, NULL, NULL),
(4370, 14, 93, '2029-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '84769', 0, '0,00', NULL, NULL, NULL),
(4371, 14, 94, '2029-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '20167', 0, '0,00', NULL, NULL, NULL),
(4372, 14, 95, '2029-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71802', 0, '0,00', NULL, NULL, NULL),
(4373, 14, 96, '2029-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '46394', 0, '0,00', NULL, NULL, NULL),
(4374, 14, 97, '2029-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '55257', 0, '0,00', NULL, NULL, NULL),
(4375, 14, 98, '2029-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '47243', 0, '0,00', NULL, NULL, NULL),
(4376, 14, 99, '2029-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '49076', 0, '0,00', NULL, NULL, NULL),
(4377, 14, 100, '2030-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '83974', 0, '0,00', NULL, NULL, NULL),
(4378, 14, 101, '2030-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '26921', 0, '0,00', NULL, NULL, NULL),
(4379, 14, 102, '2030-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '30886', 0, '0,00', NULL, NULL, NULL),
(4380, 14, 103, '2030-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '18008', 0, '0,00', NULL, NULL, NULL),
(4381, 14, 104, '2030-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '34392', 0, '0,00', NULL, NULL, NULL),
(4382, 14, 105, '2030-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '34860', 0, '0,00', NULL, NULL, NULL),
(4383, 14, 106, '2030-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23994', 0, '0,00', NULL, NULL, NULL),
(4384, 14, 107, '2030-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '52112', 0, '0,00', NULL, NULL, NULL),
(4385, 14, 108, '2030-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '49696', 0, '0,00', NULL, NULL, NULL),
(4386, 14, 109, '2030-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '36709', 0, '0,00', NULL, NULL, NULL),
(4387, 14, 110, '2030-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31044', 0, '0,00', NULL, NULL, NULL),
(4388, 14, 111, '2030-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45727', 0, '0,00', NULL, NULL, NULL),
(4389, 14, 112, '2031-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45741', 0, '0,00', NULL, NULL, NULL),
(4390, 14, 113, '2031-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '38964', 0, '0,00', NULL, NULL, NULL),
(4391, 14, 114, '2031-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '24346', 0, '0,00', NULL, NULL, NULL),
(4392, 14, 115, '2031-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '29264', 0, '0,00', NULL, NULL, NULL),
(4393, 14, 116, '2031-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '78465', 0, '0,00', NULL, NULL, NULL),
(4394, 14, 117, '2031-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '85246', 0, '0,00', NULL, NULL, NULL),
(4395, 14, 118, '2031-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '10520', 0, '0,00', NULL, NULL, NULL),
(4396, 14, 119, '2031-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '80563', 0, '0,00', NULL, NULL, NULL),
(4397, 14, 120, '2031-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '64232', 0, '0,00', NULL, NULL, NULL),
(4398, 14, 121, '2031-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23247', 0, '0,00', NULL, NULL, NULL),
(4399, 14, 122, '2031-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '99734', 0, '0,00', NULL, NULL, NULL),
(4400, 14, 123, '2031-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '28966', 0, '0,00', NULL, NULL, NULL),
(4401, 14, 124, '2032-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '92806', 0, '0,00', NULL, NULL, NULL),
(4402, 14, 125, '2032-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '75682', 0, '0,00', NULL, NULL, NULL),
(4403, 14, 126, '2032-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '35001', 0, '0,00', NULL, NULL, NULL),
(4404, 14, 127, '2032-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '40644', 0, '0,00', NULL, NULL, NULL),
(4405, 14, 128, '2032-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '91984', 0, '0,00', NULL, NULL, NULL),
(4406, 14, 129, '2032-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71980', 0, '0,00', NULL, NULL, NULL),
(4407, 14, 130, '2032-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '88177', 0, '0,00', NULL, NULL, NULL),
(4408, 14, 131, '2032-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '86071', 0, '0,00', NULL, NULL, NULL),
(4409, 14, 132, '2032-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '90993', 0, '0,00', NULL, NULL, NULL),
(4410, 14, 133, '2032-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16365', 0, '0,00', NULL, NULL, NULL),
(4411, 14, 134, '2032-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '82325', 0, '0,00', NULL, NULL, NULL),
(4412, 14, 135, '2032-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '40731', 0, '0,00', NULL, NULL, NULL),
(4413, 14, 136, '2033-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '39969', 0, '0,00', NULL, NULL, NULL),
(4414, 14, 137, '2033-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '58028', 0, '0,00', NULL, NULL, NULL),
(4415, 14, 138, '2033-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '13983', 0, '0,00', NULL, NULL, NULL),
(4416, 14, 139, '2033-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '35894', 0, '0,00', NULL, NULL, NULL),
(4417, 14, 140, '2033-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '91053', 0, '0,00', NULL, NULL, NULL),
(4418, 14, 141, '2033-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '15012', 0, '0,00', NULL, NULL, NULL),
(4419, 14, 142, '2033-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '91491', 0, '0,00', NULL, NULL, NULL),
(4420, 14, 143, '2033-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '82249', 0, '0,00', NULL, NULL, NULL),
(4421, 14, 144, '2033-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '24536', 0, '0,00', NULL, NULL, NULL),
(4422, 14, 145, '2033-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '43509', 0, '0,00', NULL, NULL, NULL),
(4423, 14, 146, '2033-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '11183', 0, '0,00', NULL, NULL, NULL),
(4424, 14, 147, '2033-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '15977', 0, '0,00', NULL, NULL, NULL),
(4425, 14, 148, '2034-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '73176', 0, '0,00', NULL, NULL, NULL),
(4426, 14, 149, '2034-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '37461', 0, '0,00', NULL, NULL, NULL),
(4427, 14, 150, '2034-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '68022', 0, '0,00', NULL, NULL, NULL),
(4428, 14, 151, '2034-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '92868', 0, '0,00', NULL, NULL, NULL),
(4429, 14, 152, '2034-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '14692', 0, '0,00', NULL, NULL, NULL),
(4430, 14, 153, '2034-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '68295', 0, '0,00', NULL, NULL, NULL),
(4431, 14, 154, '2034-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '24201', 0, '0,00', NULL, NULL, NULL),
(4432, 14, 155, '2034-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '87875', 0, '0,00', NULL, NULL, NULL),
(4433, 14, 156, '2034-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '52346', 0, '0,00', NULL, NULL, NULL),
(4434, 14, 157, '2034-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45782', 0, '0,00', NULL, NULL, NULL),
(4435, 14, 158, '2034-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '12587', 0, '0,00', NULL, NULL, NULL),
(4436, 14, 159, '2034-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '57493', 0, '0,00', NULL, NULL, NULL),
(4437, 14, 160, '2035-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '93805', 0, '0,00', NULL, NULL, NULL),
(4438, 14, 161, '2035-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '81636', 0, '0,00', NULL, NULL, NULL),
(4439, 14, 162, '2035-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '87330', 0, '0,00', NULL, NULL, NULL),
(4440, 14, 163, '2035-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '97620', 0, '0,00', NULL, NULL, NULL),
(4441, 14, 164, '2035-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '77971', 0, '0,00', NULL, NULL, NULL),
(4442, 14, 165, '2035-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '28655', 0, '0,00', NULL, NULL, NULL),
(4443, 14, 166, '2035-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '22522', 0, '0,00', NULL, NULL, NULL),
(4444, 14, 167, '2035-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '89972', 0, '0,00', NULL, NULL, NULL),
(4445, 14, 168, '2035-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '27078', 0, '0,00', NULL, NULL, NULL),
(4446, 14, 169, '2035-10-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '12797', 0, '0,00', NULL, NULL, NULL),
(4447, 14, 170, '2035-11-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '74771', 0, '0,00', NULL, NULL, NULL),
(4448, 14, 171, '2035-12-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '26017', 0, '0,00', NULL, NULL, NULL),
(4449, 14, 172, '2036-01-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '63335', 0, '0,00', NULL, NULL, NULL),
(4450, 14, 173, '2036-02-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '24119', 0, '0,00', NULL, NULL, NULL),
(4451, 14, 174, '2036-03-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '69296', 0, '0,00', NULL, NULL, NULL),
(4452, 14, 175, '2036-04-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '96629', 0, '0,00', NULL, NULL, NULL),
(4453, 14, 176, '2036-05-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '38731', 0, '0,00', NULL, NULL, NULL),
(4454, 14, 177, '2036-06-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '18875', 0, '0,00', NULL, NULL, NULL),
(4455, 14, 178, '2036-07-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71143', 0, '0,00', NULL, NULL, NULL),
(4456, 14, 179, '2036-08-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '15182', 0, '0,00', NULL, NULL, NULL),
(4457, 14, 180, '2036-09-13', '150,70', '150,70', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '53204', 0, '0,00', NULL, NULL, NULL),
(4578, 15, 1, '2021-10-31', '371,41', '371,41', NULL, '1', NULL, 'taxa', NULL, NULL, NULL, 3, 9, NULL, NULL, '83911', 0, '371,41', '2021-10-15', 'asddddddddddddd', 1),
(4579, 15, 1, '2021-12-15', '971,98', '971,98', NULL, '1', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '00025', 0, '1351.48', '2021-10-05', 'Pago por boleto', NULL),
(4580, 15, 2, '2022-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '64758', 0, '0,00', NULL, NULL, NULL),
(4581, 15, 3, '2022-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '50547', 0, '0,00', NULL, NULL, NULL),
(4582, 15, 4, '2022-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '65406', 0, '0,00', NULL, NULL, NULL),
(4583, 15, 5, '2022-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '42807', 0, '0,00', NULL, NULL, NULL),
(4584, 15, 6, '2022-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23410', 0, '0,00', NULL, NULL, NULL),
(4585, 15, 7, '2022-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '91796', 0, '0,00', NULL, NULL, NULL),
(4586, 15, 8, '2022-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '38756', 0, '0,00', NULL, NULL, NULL),
(4587, 15, 9, '2022-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '62572', 0, '0,00', NULL, NULL, NULL),
(4588, 15, 10, '2022-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '69390', 0, '0,00', NULL, NULL, NULL),
(4589, 15, 11, '2022-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '59096', 0, '0,00', NULL, NULL, NULL),
(4590, 15, 12, '2022-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16433', 0, '0,00', NULL, NULL, NULL),
(4591, 15, 13, '2022-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '53969', 0, '0,00', NULL, NULL, NULL),
(4592, 15, 14, '2023-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '28038', 0, '0,00', NULL, NULL, NULL),
(4593, 15, 15, '2023-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '76981', 0, '0,00', NULL, NULL, NULL),
(4594, 15, 16, '2023-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '49764', 0, '0,00', NULL, NULL, NULL),
(4595, 15, 17, '2023-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '10800', 0, '0,00', NULL, NULL, NULL),
(4596, 15, 18, '2023-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '46005', 0, '0,00', NULL, NULL, NULL),
(4597, 15, 19, '2023-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '54979', 0, '0,00', NULL, NULL, NULL),
(4598, 15, 20, '2023-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '49658', 0, '0,00', NULL, NULL, NULL),
(4599, 15, 21, '2023-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '98125', 0, '0,00', NULL, NULL, NULL),
(4600, 15, 22, '2023-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '56297', 0, '0,00', NULL, NULL, NULL),
(4601, 15, 23, '2023-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '84389', 0, '0,00', NULL, NULL, NULL),
(4602, 15, 24, '2023-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '47725', 0, '0,00', NULL, NULL, NULL),
(4603, 15, 25, '2023-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '93952', 0, '0,00', NULL, NULL, NULL),
(4604, 15, 26, '2024-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '30987', 0, '0,00', NULL, NULL, NULL),
(4605, 15, 27, '2024-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '66265', 0, '0,00', NULL, NULL, NULL),
(4606, 15, 28, '2024-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '36763', 0, '0,00', NULL, NULL, NULL),
(4607, 15, 29, '2024-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16926', 0, '0,00', NULL, NULL, NULL),
(4608, 15, 30, '2024-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71369', 0, '0,00', NULL, NULL, NULL),
(4609, 15, 31, '2024-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '77765', 0, '0,00', NULL, NULL, NULL),
(4610, 15, 32, '2024-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '34166', 0, '0,00', NULL, NULL, NULL),
(4611, 15, 33, '2024-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '35631', 0, '0,00', NULL, NULL, NULL),
(4612, 15, 34, '2024-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '64446', 0, '0,00', NULL, NULL, NULL),
(4613, 15, 35, '2024-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '80566', 0, '0,00', NULL, NULL, NULL),
(4614, 15, 36, '2024-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '98576', 0, '0,00', NULL, NULL, NULL),
(4615, 15, 37, '2024-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '70717', 0, '0,00', NULL, NULL, NULL),
(4616, 15, 38, '2025-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '48783', 0, '0,00', NULL, NULL, NULL),
(4617, 15, 39, '2025-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45914', 0, '0,00', NULL, NULL, NULL),
(4618, 15, 40, '2025-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '54655', 0, '0,00', NULL, NULL, NULL),
(4619, 15, 41, '2025-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '85856', 0, '0,00', NULL, NULL, NULL),
(4620, 15, 42, '2025-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '20856', 0, '0,00', NULL, NULL, NULL),
(4621, 15, 43, '2025-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '39233', 0, '0,00', NULL, NULL, NULL),
(4622, 15, 44, '2025-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '51722', 0, '0,00', NULL, NULL, NULL),
(4623, 15, 45, '2025-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '78002', 0, '0,00', NULL, NULL, NULL),
(4624, 15, 46, '2025-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '11648', 0, '0,00', NULL, NULL, NULL),
(4625, 15, 47, '2025-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '95275', 0, '0,00', NULL, NULL, NULL),
(4626, 15, 48, '2025-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31914', 0, '0,00', NULL, NULL, NULL),
(4627, 15, 49, '2025-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '99363', 0, '0,00', NULL, NULL, NULL),
(4628, 15, 50, '2026-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '10624', 0, '0,00', NULL, NULL, NULL),
(4629, 15, 51, '2026-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '11722', 0, '0,00', NULL, NULL, NULL),
(4630, 15, 52, '2026-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '49896', 0, '0,00', NULL, NULL, NULL),
(4631, 15, 53, '2026-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '84254', 0, '0,00', NULL, NULL, NULL),
(4632, 15, 54, '2026-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '62939', 0, '0,00', NULL, NULL, NULL),
(4633, 15, 55, '2026-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '60055', 0, '0,00', NULL, NULL, NULL),
(4634, 15, 56, '2026-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '88161', 0, '0,00', NULL, NULL, NULL),
(4635, 15, 57, '2026-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '56638', 0, '0,00', NULL, NULL, NULL),
(4636, 15, 58, '2026-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '86812', 0, '0,00', NULL, NULL, NULL),
(4637, 15, 59, '2026-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '85820', 0, '0,00', NULL, NULL, NULL),
(4638, 15, 60, '2026-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '11202', 0, '0,00', NULL, NULL, NULL),
(4639, 15, 61, '2026-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '70250', 0, '0,00', NULL, NULL, NULL),
(4640, 15, 62, '2027-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23786', 0, '0,00', NULL, NULL, NULL),
(4641, 15, 63, '2027-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45605', 0, '0,00', NULL, NULL, NULL),
(4642, 15, 64, '2027-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '87231', 0, '0,00', NULL, NULL, NULL),
(4643, 15, 65, '2027-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23502', 0, '0,00', NULL, NULL, NULL),
(4644, 15, 66, '2027-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '77729', 0, '0,00', NULL, NULL, NULL),
(4645, 15, 67, '2027-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '14600', 0, '0,00', NULL, NULL, NULL),
(4646, 15, 68, '2027-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '73441', 0, '0,00', NULL, NULL, NULL),
(4647, 15, 69, '2027-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '11708', 0, '0,00', NULL, NULL, NULL),
(4648, 15, 70, '2027-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '61528', 0, '0,00', NULL, NULL, NULL),
(4649, 15, 71, '2027-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16312', 0, '0,00', NULL, NULL, NULL),
(4650, 15, 72, '2027-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '46964', 0, '0,00', NULL, NULL, NULL),
(4651, 15, 73, '2027-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '82661', 0, '0,00', NULL, NULL, NULL),
(4652, 15, 74, '2028-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '63734', 0, '0,00', NULL, NULL, NULL),
(4653, 15, 75, '2028-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16738', 0, '0,00', NULL, NULL, NULL),
(4654, 15, 76, '2028-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '13593', 0, '0,00', NULL, NULL, NULL),
(4655, 15, 77, '2028-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '92462', 0, '0,00', NULL, NULL, NULL),
(4656, 15, 78, '2028-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '22859', 0, '0,00', NULL, NULL, NULL),
(4657, 15, 79, '2028-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '97709', 0, '0,00', NULL, NULL, NULL),
(4658, 15, 80, '2028-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '39683', 0, '0,00', NULL, NULL, NULL),
(4659, 15, 81, '2028-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '56631', 0, '0,00', NULL, NULL, NULL),
(4660, 15, 82, '2028-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '95669', 0, '0,00', NULL, NULL, NULL),
(4661, 15, 83, '2028-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31216', 0, '0,00', NULL, NULL, NULL),
(4662, 15, 84, '2028-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '97450', 0, '0,00', NULL, NULL, NULL),
(4663, 15, 85, '2028-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '26181', 0, '0,00', NULL, NULL, NULL),
(4664, 15, 86, '2029-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '11243', 0, '0,00', NULL, NULL, NULL),
(4665, 15, 87, '2029-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '91049', 0, '0,00', NULL, NULL, NULL),
(4666, 15, 88, '2029-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '26881', 0, '0,00', NULL, NULL, NULL),
(4667, 15, 89, '2029-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '41494', 0, '0,00', NULL, NULL, NULL),
(4668, 15, 90, '2029-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '16144', 0, '0,00', NULL, NULL, NULL),
(4669, 15, 91, '2029-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '32106', 0, '0,00', NULL, NULL, NULL),
(4670, 15, 92, '2029-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '48156', 0, '0,00', NULL, NULL, NULL),
(4671, 15, 93, '2029-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '84328', 0, '0,00', NULL, NULL, NULL),
(4672, 15, 94, '2029-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '33279', 0, '0,00', NULL, NULL, NULL),
(4673, 15, 95, '2029-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '44129', 0, '0,00', NULL, NULL, NULL),
(4674, 15, 96, '2029-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '93864', 0, '0,00', NULL, NULL, NULL),
(4675, 15, 97, '2029-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '55038', 0, '0,00', NULL, NULL, NULL),
(4676, 15, 98, '2030-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '57509', 0, '0,00', NULL, NULL, NULL),
(4677, 15, 99, '2030-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31302', 0, '0,00', NULL, NULL, NULL),
(4678, 15, 100, '2030-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '26135', 0, '0,00', NULL, NULL, NULL),
(4679, 15, 101, '2030-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '79816', 0, '0,00', NULL, NULL, NULL),
(4680, 15, 102, '2030-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '35205', 0, '0,00', NULL, NULL, NULL),
(4681, 15, 103, '2030-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '31110', 0, '0,00', NULL, NULL, NULL),
(4682, 15, 104, '2030-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '63429', 0, '0,00', NULL, NULL, NULL),
(4683, 15, 105, '2030-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '61734', 0, '0,00', NULL, NULL, NULL),
(4684, 15, 106, '2030-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '83476', 0, '0,00', NULL, NULL, NULL),
(4685, 15, 107, '2030-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '84397', 0, '0,00', NULL, NULL, NULL),
(4686, 15, 108, '2030-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '42849', 0, '0,00', NULL, NULL, NULL),
(4687, 15, 109, '2030-12-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71743', 0, '0,00', NULL, NULL, NULL),
(4688, 15, 110, '2031-01-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '21862', 0, '0,00', NULL, NULL, NULL),
(4689, 15, 111, '2031-02-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '55082', 0, '0,00', NULL, NULL, NULL),
(4690, 15, 112, '2031-03-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '65077', 0, '0,00', NULL, NULL, NULL),
(4691, 15, 113, '2031-04-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '45422', 0, '0,00', NULL, NULL, NULL),
(4692, 15, 114, '2031-05-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '55980', 0, '0,00', NULL, NULL, NULL),
(4693, 15, 115, '2031-06-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '94149', 0, '0,00', NULL, NULL, NULL),
(4694, 15, 116, '2031-07-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '59473', 0, '0,00', NULL, NULL, NULL),
(4695, 15, 117, '2031-08-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '23119', 0, '0,00', NULL, NULL, NULL),
(4696, 15, 118, '2031-09-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '35978', 0, '0,00', NULL, NULL, NULL),
(4697, 15, 119, '2031-10-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '71034', 0, '0,00', NULL, NULL, NULL),
(4698, 15, 120, '2031-11-15', '971,98', '971,98', NULL, '2', NULL, '', NULL, NULL, NULL, 1, NULL, NULL, NULL, '89700', 0, '0,00', NULL, NULL, NULL),
(4700, 14, 1, '2021-10-29', '15,00', '15,00', NULL, '1', NULL, 'taxa', NULL, NULL, NULL, 2, 11, NULL, NULL, '58536', 0, '0,00', NULL, NULL, NULL),
(4701, 14, 1, '2021-10-28', '15,00', '15,00', NULL, '1', NULL, 'taxa', NULL, NULL, NULL, 2, 12, NULL, NULL, '27652', 0, '0,00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `programed_payments`
--

CREATE TABLE `programed_payments` (
  `id` int(11) NOT NULL,
  `id_internal_account` int(11) NOT NULL DEFAULT 0,
  `description` varchar(450) NOT NULL DEFAULT '0',
  `id_provider` int(11) NOT NULL DEFAULT 0,
  `value` varchar(10) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `num` int(11) NOT NULL,
  `totalNumberParcels` int(11) NOT NULL,
  `totalValue` varchar(10) NOT NULL DEFAULT '',
  `proof_payment` varchar(50) DEFAULT '',
  `payment_method` varchar(450) DEFAULT '',
  `payment_date` date DEFAULT NULL,
  `value_payment` varchar(10) DEFAULT '',
  `idBank` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `programed_payments`
--

INSERT INTO `programed_payments` (`id`, `id_internal_account`, `description`, `id_provider`, `value`, `date`, `num`, `totalNumberParcels`, `totalValue`, `proof_payment`, `payment_method`, `payment_date`, `value_payment`, `idBank`, `status`) VALUES
(0, 2, 'saddddddddddddddddddd', 29, '50,00', '2021-04-15', 1, 3, '150', 'proof_payment/23dcec70aa3dfa335ff0c17b186d0908.php', 'dsfaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2021-04-16', '50,00', 1, 1),
(2, 2, 'saddddddddddddddddddd', 29, '50,00', '2021-05-15', 2, 3, '150', 'proof_payment/a1697bdffba65dca315c2df3862538d9.pdf', 'asddddddddddddddddddddasdsad', '2021-05-10', '50,00', 1, 1),
(3, 2, 'saddddddddddddddddddd', 29, '50,00', '2021-06-15', 3, 3, '150', '', '', NULL, '', NULL, 2),
(4, 3, 'dfsaaaaaaaaaaaaaaaaaa', 1, '50,00', '2021-04-01', 1, 3, '150', '', '', NULL, '', NULL, 3),
(5, 3, 'dfsaaaaaaaaaaaaaaaaaa', 1, '50,00', '2021-04-16', 2, 3, '150', '', '', NULL, '', NULL, 3),
(6, 3, 'dfsaaaaaaaaaaaaaaaaaa', 1, '50,00', '2021-05-01', 3, 3, '150', '', '', NULL, '', NULL, 3),
(7, 2, 'sdaaaaaaaaaaaaaaaaaaaaa', 1, '15,00', '2021-03-31', 1, 1, '15,00', 'proof_payment/6ddb2596d84eb9b532b570e80a9a5201.pdf', 'dsaaaaaaaaaaaaaa sad', '2021-04-10', '15,00', 2, 1),
(11, 2, 'dgsssssssssssssss', 1, '150,00', '2021-04-14', 1, 1, '150,00', '', '', NULL, '', NULL, 3),
(12, 5, 'mnnnnnnnnnnnnnnnn', 29, '150,00', '2021-04-14', 1, 1, '150,00', '', '', NULL, '', NULL, 3),
(13, 2, 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', 1, '50,00', '2021-04-16', 1, 1, '50,00', 'proof_payment/7e2e487944659375fc9613ca7f8eb591.txt', 'sdaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2021-04-16', '50,00', 2, 1),
(14, 6, 'asddddddddddddddddddddddddddddddd', 29, '180,00', '2021-04-16', 1, 1, '180,00', '', '', NULL, '', NULL, 3),
(15, 6, 'dafsssssssssssssssss', 29, '150,00', '2021-04-26', 1, 1, '150,00', 'proof_payment/44190d1cb71cc5f3d400dba699e9a17f.pdf', 'dasffffffffffffffffffffffffffffff', '2021-04-26', '150,00', 1, 1),
(16, 2, 'dfasasasasasasasasasasasas', 1, '150,00', '2021-05-10', 1, 1, '150,00', 'proof_payment/a478153d2578ced78859ad0e55442543.pdf', 'asfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfdasfd', '2021-05-10', '150,00', 1, 1),
(17, 2, 'fasasasasasasasasasasasasasasasg', 29, '100,00', '2021-05-10', 1, 2, '200', '', '', NULL, '', NULL, 2),
(18, 2, 'fasasasasasasasasasasasasasasasg', 29, '100,00', '2021-06-02', 2, 2, '200', '', '', NULL, '', NULL, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `refinancing_info`
--

CREATE TABLE `refinancing_info` (
  `id` int(11) NOT NULL,
  `id_contact` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL,
  `total_value` varchar(50) NOT NULL,
  `number_parcels` int(11) NOT NULL,
  `value_parcel` varchar(50) NOT NULL,
  `value_fine_percentage` varchar(50) NOT NULL,
  `value_fine` varchar(50) NOT NULL,
  `number_parcels_fine` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `sufix` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `refinancing_info`
--

INSERT INTO `refinancing_info` (`id`, `id_contact`, `id_sale`, `total_value`, `number_parcels`, `value_parcel`, `value_fine_percentage`, `value_fine`, `number_parcels_fine`, `date`, `time`, `sufix`) VALUES
(1, 2, 6, '26750,21', 180, '148.61', '10', '28.76', 1, '2021-09-13', '11:13:37', NULL),
(2, 3, 8, '26750,21', 180, '148.61', '10', '28.75', 1, '2021-09-13', '11:24:16', NULL),
(3, 4, 9, '26750,21', 180, '148.61', '10', '28.76', 1, '2021-09-13', '11:29:33', NULL),
(4, 5, 10, '27000,22', 180, '150.00', '10', '43.33', 1, '2021-09-13', '18:07:46', NULL),
(5, 6, 12, '27000,22', 180, '150.00', '10', '43.33', 1, '2021-09-13', '18:19:16', NULL),
(6, 7, 13, '27000,22', 180, '150.00', '10', '43.33', 1, '2021-09-13', '18:23:27', NULL),
(7, 8, 14, '27125,22', 180, '150.70', '10', '43.34', 1, '2021-09-13', '18:25:47', NULL),
(8, 9, 15, '116637,07', 120, '971.98', '10', '371.41', 1, '2021-10-15', '16:56:11', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reissue_contact_info`
--

CREATE TABLE `reissue_contact_info` (
  `id` int(11) NOT NULL,
  `id_sale` int(11) NOT NULL DEFAULT 0,
  `id_contact` int(11) NOT NULL DEFAULT 0,
  `parcel_late_sum` varchar(50) NOT NULL DEFAULT '0',
  `rate_reissue` varchar(50) NOT NULL DEFAULT '0',
  `deadline_reissue` varchar(50) NOT NULL DEFAULT '0',
  `total_reissue` varchar(50) NOT NULL DEFAULT '0',
  `parcels_selected` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_interprise` int(11) NOT NULL DEFAULT 0,
  `id_lot` int(11) NOT NULL DEFAULT 0,
  `contract_number` varchar(100) DEFAULT NULL,
  `value` varchar(20) DEFAULT NULL,
  `input` varchar(20) DEFAULT NULL,
  `descont` varchar(20) DEFAULT NULL,
  `parcels` varchar(20) DEFAULT NULL,
  `index` varchar(20) DEFAULT NULL,
  `interest_per_year` varchar(20) DEFAULT NULL,
  `first_parcel` date DEFAULT NULL,
  `expiration_day` int(11) DEFAULT NULL,
  `clients` text DEFAULT NULL,
  `client_payment_id` int(11) DEFAULT NULL,
  `id_clients_porc` text DEFAULT NULL,
  `contract` text DEFAULT NULL,
  `almostFinishFile` text DEFAULT NULL,
  `finishFileSale` text DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `propose_date` date DEFAULT NULL,
  `begin_contract_date` date DEFAULT NULL,
  `value_parcel` varchar(30) DEFAULT NULL,
  `annual_rate` float NOT NULL DEFAULT 0,
  `html_contract` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `sales`
--

INSERT INTO `sales` (`id`, `id_interprise`, `id_lot`, `contract_number`, `value`, `input`, `descont`, `parcels`, `index`, `interest_per_year`, `first_parcel`, `expiration_day`, `clients`, `client_payment_id`, `id_clients_porc`, `contract`, `almostFinishFile`, `finishFileSale`, `type`, `propose_date`, `begin_contract_date`, `value_parcel`, `annual_rate`, `html_contract`) VALUES
(14, 2, 4, '5656565', '25.000,00', '5.000,00', '0,00', '180', '5', NULL, '2021-06-13', 15, '1', 1, '1-100', 'contractSale/8ea86a98a5b141f13a7200c17fdf5ceb.pdf', NULL, NULL, 2, '2021-09-13', '2021-09-13', '138,89', 6, NULL),
(15, 6, 9, '665656', '107.500,00', '7.500,00', '0,00', '120', '5', NULL, '2021-07-15', 10, '1', 1, '1-100', 'contractSale/e6c45e4de3c49b462e34b9cf01eedba9.pdf', NULL, NULL, 2, '2021-10-15', '2021-10-15', '895,83', 6, NULL),
(16, 6, 8, '587877', '500,00', '1.500,00', '0,00', '5', '5', NULL, '2021-11-01', 15, '1', 1, '1-100', NULL, NULL, NULL, 1, '2021-10-28', NULL, '100,00', 6, '<p>&nbsp;</p>\n<h2 class=\"title\">CONTRATO PARTICULAR DE VENDA E COMPRA - N.&ordm; 587877- EDITADOOOOOOOOOOO</h2>\n<p>&nbsp;</p>\n<h5>Promitente Vendedor</h5>\n<p style=\"text-align: justify; ;width: 70%;\">GENESIS LOTEADORA E COLONIZADORA S/S LTDA., pessoa(s) jur&iacute;dica(s) de direito privado, com sede R PARA, 1531 CENTRO LONDRINA - PR, inscrita no CNPJ/MF sob o n.&ordm; 00.664.563/0001-84; neste ato representada por CARLOS SHIGUERU IMADA, brasileiro, engenheiro civil, portador da C&eacute;dula de Identidade 536.905-SSP/MS e CPF/MF 595.291.481-00;</p>\n<p>&nbsp;</p>\n<h5>Compromiss&aacute;rio(a)(s) Comprador(a)(es)</h5>\n<p>Mauricio Ferreira de Jesus residente e domiciliado &agrave; Rua Raul Seixas, 114 Vila Roseira II, S&atilde;o Paulo - SP, portador(a)(es) das C&eacute;dulas de Identidade RG N&Atilde;O INFORMADO e do CPF/MF 351.942.148-80 ;</p>\n<p>As partes acima identificadas t&ecirc;m entre si contratado o presente instrumento sob as seguintes condi&ccedil;&otilde;es:</p>\n<h4 class=\"title\">CL&Aacute;USULA PRIMEIRA</h4>\n<p>A(s) Promitente Vendedora &eacute;(s&atilde;o) leg&iacute;tima(s) senhora(s) e possuidora(s) do Lote 8787 da Quadra fdssdf do teste empreendimento usuario, na cidade de SP/UF, com &aacute;rea de 68989 m&sup2;, objeto da matr&iacute;cula n.&ordm; do Cart&oacute;rio de Registro de Im&oacute;veis da respectiva comarca. O referido lote tem as seguintes divisas e confronta&ccedil;&otilde;es: .</p>\n<h4 class=\"title\">CL&Aacute;USULA SEGUNDA</h4>\n<p>A Promitente Vendedora promete vender e o(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) se compromete(m) a comprar o im&oacute;vel descrito na Cl&aacute;usula Primeira pelo pre&ccedil;o certo e ajustado de R$ 500,00, a ser pago da seguinte forma:<br />a) Arras/Sinal do Neg&oacute;cio: no valor de R$ 1.500,00, representada por 01 (uma) parcela paga no ato da assinatura da Proposta de Compra;<br />b) Saldo: no valor de R$ 500,00 a ser pago em 5 parcelas mensais e consecutivas no valor de R$ 100,00 cada, com vencimento todo dia 15 do m&ecirc;s, sendo a primeira a vencer em 01/11/2021.<br />&sect; Primeiro &ndash; As parcelas ser&atilde;o reajustadas anualmente, a partir da data da respectiva Proposta de Compra, observando-se sempre o m&iacute;nimo de 10 (dez) por cento que se d&aacute; por conta da infla&ccedil;&atilde;o e reestabelecimento econ&ocirc;mico e financeiro das presta&ccedil;&otilde;es vincendas.<br />&sect; Segundo &ndash; As parcelas ser&atilde;o pagas exclusivamente atrav&eacute;s de cobran&ccedil;a banc&aacute;ria.<br />&sect; Terceiro &ndash; Os pagamentos das parcelas mensais efetuados at&eacute; o dia do vencimento, ter&atilde;o um abatimento de 10% (dez por cento) no valor nominal, a t&iacute;tulo de bonifica&ccedil;&atilde;o por pontualidade.<br />&sect; Quarto &ndash; Ocorrendo a impontualidade no pagamento das presta&ccedil;&otilde;es, al&eacute;m de perder o abono, incidir&atilde;o juros de 1% (um por cento) ao m&ecirc;s e multa pecuni&aacute;ria de 2% (dois por cento) sobre o valor da presta&ccedil;&atilde;o na data do efetivo pagamento.<br />&sect; Quinto &ndash; No caso de atraso superior a 03 (tr&ecirc;s) parcelas, consecutivas ou n&atilde;o, dever&atilde;o ser pagos, al&eacute;m de juros e da multa, honor&aacute;rios advocat&iacute;cios fixados em 10% (dez por cento) no caso de transa&ccedil;&atilde;o amig&aacute;vel e de 20% (vinte por cento) no caso de transa&ccedil;&atilde;o judicial, independentemente do pagamento das custas judiciais e extrajudiciais porventura existentes, &ocirc;nus estes atribu&iacute;dos &agrave; parte faltante.</p>\n<h4 class=\"title\">CL&Aacute;USULA TERCEIRA</h4>\n<p>A presente promessa de venda e compra &eacute; celebrada em car&aacute;ter irrevog&aacute;vel e irretrat&aacute;vel podendo, por&eacute;m ser rescindida por inadimplemento de qualquer de suas cl&aacute;usulas e ou condi&ccedil;&otilde;es.<br />a) No caso da rescis&atilde;o vier a ocorrer por culpa da Promitente Vendedora, dever&aacute; esta devolver toda e qualquer import&acirc;ncia que houver recebido, com as mesmas corre&ccedil;&otilde;es do &sect; Segundo da Cl&aacute;usula Segunda, e parceladamente nas mesmas condi&ccedil;&otilde;es em que recebeu do(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es).<br />b) No caso da rescis&atilde;o vier a ser causada por desist&ecirc;ncia ou inadimpl&ecirc;ncia por culpa do(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es), e ap&oacute;s ter(em) sido devidamente notificado(s), conforme preceitua o &sect; Primeiro, do art. 32, da Lei 6.766, de 19.12.79, ficar&aacute; a Promitente Vendedora investida no direito de ajuizar a&ccedil;&atilde;o de reintegra&ccedil;&atilde;o de posse, com pedido de liminar e restituir ao(&agrave;)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) o valor de que deste(s) tiver recebido como pagamento de pre&ccedil;o, sem corre&ccedil;&atilde;o, deduzidas, entretanto, em seu favor, a verbas equivalentes a:<br />I. 6,0% (seis por cento) do pre&ccedil;o total do lote, com os devidos reajustes, atualizados sobre o montante da venda da cl&aacute;usula segunda, a t&iacute;tulo de comiss&atilde;o de corretagem deste neg&oacute;cio;;<br />II. 20,0% (vinte por cento) dos valores principais pagos pelo(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es), com os devidos reajustes, atualizados sobre o montante da venda da cl&aacute;usula segunda, a t&iacute;tulo de despesas com an&aacute;lise e controle cadastral, elabora&ccedil;&atilde;o de contrato, consultas diversas, e de despesas administrativas de cobran&ccedil;as.<br />III. Os valores relativos a juros e multas decorrentes de atrasos nos pagamentos das parcelas. IV. Os valores, devidamente corrigidos, que a Promitente Vendedora houver antecipado com despesas havidas para a rescis&atilde;o deste contrato, tais como custas, emolumentos, notifica&ccedil;&otilde;es, intima&ccedil;&otilde;es, honor&aacute;rios advocat&iacute;cios, etc.<br />&sect; Primeiro &ndash; Caso os valores pagos pelo(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) sejam inferiores aos valores dos itens &ldquo;I&rdquo;, &ldquo;II&rdquo;, &ldquo;III&rdquo; e &ldquo;IV&rdquo;, este(s) dever&aacute;(ao) completar o pagamento para se fazer frente a estas despesas.<br />&sect; Segundo &ndash; Os valores a serem restitu&iacute;dos ao(&agrave;)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) ser&atilde;o pagos a este em igual n&uacute;mero de parcelas que o(s) mesmo(s) efetuou(aram) &agrave; Promitente Vendedora, de forma sucessiva, sem corre&ccedil;&atilde;o, a partir de 30 (trinta) dias da rescis&atilde;o contratual.</p>\n<h4 class=\"title\">CL&Aacute;USULA QUARTA</h4>\n<p>A Promitente Vendedora, no ato da assinatura deste instrumento, mediante as cl&aacute;usulas pactuadas, transmite ao(&agrave;)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) toda a posse direta, dom&iacute;nio, direito e a&ccedil;&atilde;o que at&eacute; ent&atilde;o exercia sobre o referido im&oacute;vel, comprometendo-se por si, herdeiros e legais sucessores, a faz&ecirc;-la sempre boa, firme e valiosa e a responderem pela evic&ccedil;&atilde;o de direito na forma da Lei.<br />A Promitente Vendedora se compromete a entregar o im&oacute;vel com toda a infra-estrutura b&aacute;sica, de acordo com o compromisso firmado com a Prefeitura Municipal. O im&oacute;vel ser&aacute; entregue livre e desembara&ccedil;ado de todos e quaisquer &ocirc;nus judiciais e extrajudiciais, foro, pens&atilde;o e hipoteca de qualquer natureza, bem como quite de todos os impostos e taxas.</p>\n<h4 class=\"title\">CL&Aacute;USULA QUINTA</h4>\n<p>Correr&atilde;o por conta do(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) todas as despesas decorrentes da aquisi&ccedil;&atilde;o do lote, tais como Escritura P&uacute;blica de Compra e Venda (com Pacto Comiss&oacute;rio para o caso de ainda existir cr&eacute;ditos em favor da Promitente Vendedora), ITBI, FUNREJUS, registros em cart&oacute;rios, bem como quaisquer outros impostos e taxa que venham a incidir sobre o mesmo a partir da data da assinatura da Proposta de Compra, documento gerador deste compromisso, ainda que encargos sejam lan&ccedil;ados em nome da Promitente Vendedora.<br />&Eacute; de responsabilidade do(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) manter o referido lote em perfeito estado de limpeza e higiene; a retirar expensas muros e cercas em desacordo com a demarca&ccedil;&atilde;o correta.<br />&Eacute; de responsabilidade do(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) informar para a Promitente Vendedora, num prazo de 10 (dez) dias, quando da(s) sua(s) altera&ccedil;&atilde;o(&otilde;es) de resid&ecirc;ncia ou de endere&ccedil;o para recebimento de correspond&ecirc;ncia, avisos e carn&ecirc;s, sob pena de n&atilde;o o fazendo, ser considerado como estando em lugar incerto e n&atilde;o sabido, sofrendo as conseq&uuml;&ecirc;ncias judiciais dessa caracteriza&ccedil;&atilde;o.</p>\n<h4 class=\"title\">CL&Aacute;USULA SEXTA</h4>\n<p>No caso do(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es) desejar(em) ceder ou transferir &agrave; terceiros os direitos e deveres ajustados no presente contrato, somente poder&aacute;(ao) faz&ecirc;-lo, desde que sem d&eacute;bito pecuni&aacute;rio vencido frente &agrave; Promitente Vendedora. A cess&atilde;o e transfer&ecirc;ncia de direitos e obriga&ccedil;&otilde;es realizada pelo(a)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es), mesmo em situa&ccedil;&atilde;o regular de pagamentos, somente ter&aacute; efic&aacute;cia em rela&ccedil;&atilde;o &agrave; Promitente Vendedora caso esta venha a anuir expressamente ao cession&aacute;rio no instrumento que materializar o ato, ou, se notificada nos termos da Lei para tanto, n&atilde;o se opor no prazo legal ao neg&oacute;cio.<br />&sect; &Uacute;nico &ndash; O(A)(s) Compromiss&aacute;rio(a)(s) Comprador(a)(es), neste caso, arcar&aacute;(ao) com todas as despesas, inclusive de ordem meramente administrativas &agrave; Promitente Vendedora, decorrentes da cess&atilde;o transfer&ecirc;ncia que realizar no valor de 3,0% (tr&ecirc;s por cento) sobre o valor do lote, devidamente corrigido na referida data da anu&ecirc;ncia.</p>\n<h4 class=\"title\">CL&Aacute;USULA SETIMA</h4>\n<p>Quaisquer controv&eacute;rsias, diverg&ecirc;ncias ou conflitos resultantes desse contrato ou incidentes nas cl&aacute;usulas do mesmo, ser&atilde;o resolvidos pelo procedimento arbitral, conforme a lei de arbitragem n&deg; 9.307/96, adotando a regra do direito, por interm&eacute;dio do TACOM - PR &ndash; C&acirc;mara de Arbitragem, Concilia&ccedil;&atilde;o e Media&ccedil;&atilde;o do Paran&aacute;, localizado na Avenida Paissandu, n&deg; 1062, Zona 03, na cidade de Maring&aacute;, Estado do Paran&aacute;, CNPJ/MF sob o n&deg; 05.475.080/0001-55, de acordo com seu regulamento, regimento e demais normas de procedimentos, por um &aacute;rbitro integrante de seu quadro, no idioma portugu&ecirc;s.</p>\n<p>E, por estarem de acordo, justos e contratados, assinam o presente em VIAS vias, de igual teor e forma, na presen&ccedil;a das testemunhas abaixo.</p>\n<p>s&atilde;o paulo - SP, 28 de Outubro de 2021</p>\n<p><br /><br /><br /></p>\n<div class=\"container_signature__row\">\n<div class=\"container_signature__item\"><strong>ORTORGANTE CEDENTE</strong></div>\n<div class=\"container_signature__item signature_line\">\n<div class=\"container_signature_line\">CARLOS SHIGUERU IMADA</div>\n</div>\n</div>\n<p><br /><br /></p>\n<div class=\"container_signature__row\">\n<div class=\"container_signature__item\"><strong>ORTORGANTE CESSION&Aacute;RIO</strong></div>\n<div class=\"container_signature__item signature_line\">\n<div class=\"container_signature_line\">Mauricio Ferreira de Jesus</div>\n</div>\n</div>\n<p><br /><br /></p>\n<!--    <div class=\"container_signature__row\">\n        <div class=\"container_signature__item\">\n            <b>INTERVENIENTE ANUENTE</b>\n        </div>\n\n        <div class=\"container_signature__item signature_line\">\n            <div class=\"container_signature_line\">\n                GENESIS LOTEADORA E COLONIZADORA S/S LTDA\n            </div>\n        </div>\n    </div>\n    -->\n<p>&nbsp;</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `transfers_banks`
--

CREATE TABLE `transfers_banks` (
  `id` int(11) NOT NULL,
  `id_origin_bank` int(11) NOT NULL DEFAULT 0,
  `description` varchar(450) NOT NULL,
  `value` varchar(10) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `id_destiny_bank` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `transfers_banks`
--

INSERT INTO `transfers_banks` (`id`, `id_origin_bank`, `description`, `value`, `date`, `time`, `id_destiny_bank`) VALUES
(1, 1, 'sfdaaaaaaaaaaaaaaaaa', '200,00', '2021-04-16', '09:37:55', 2),
(2, 2, 'sadadadadadadadadadadadadadadad', '150,00', '2021-04-16', '10:23:46', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `unscheduled_payments`
--

CREATE TABLE `unscheduled_payments` (
  `id` int(11) NOT NULL,
  `id_internal_account` int(11) NOT NULL DEFAULT 0,
  `id_provider` int(11) NOT NULL DEFAULT 0,
  `description` varchar(450) NOT NULL DEFAULT '0',
  `value` varchar(10) NOT NULL DEFAULT '0',
  `deadline` date NOT NULL,
  `payment_value` varchar(50) NOT NULL DEFAULT '0',
  `payment_date` date NOT NULL,
  `id_payment_bank` int(11) NOT NULL DEFAULT 0,
  `payment_method` varchar(450) NOT NULL DEFAULT '0',
  `proof_payment` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `unscheduled_payments`
--

INSERT INTO `unscheduled_payments` (`id`, `id_internal_account`, `id_provider`, `description`, `value`, `deadline`, `payment_value`, `payment_date`, `id_payment_bank`, `payment_method`, `proof_payment`) VALUES
(1, 2, 29, 'sadsadsadasd', '500,00', '2021-03-30', '500,00', '2021-03-31', 2, '2', ''),
(2, 3, 29, 'sdsadsadsad', '500,00', '2021-03-31', '500,00', '2021-03-30', 2, 'sdaaaaaaaaaaaaaaaaaaaaaaaa', ''),
(4, 2, 60, 'dfsafdfssfdfsa', '150,00', '2021-03-31', '500,00', '2021-03-30', 2, 'sdfsadffffsafsadddsafasfd', 'proof_payment/4cc515abb897aa4500cfa9b9b2128795.pdf'),
(5, 2, 29, 'sdsdsadsadsad sdadas', '150,00', '2021-04-05', '150,00', '2021-03-31', 3, 'sadsadsadasdasdasd', 'proof_payment/a4125d0ebc2f31efdf1de0f849f4717a.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `photo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `date` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idClient` int(11) DEFAULT 0,
  `token_access` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `type`, `photo`, `status`, `date`, `idClient`, `token_access`) VALUES
(1, 'Mauricio', 'mauricio-ferreira2015@outlook.com', NULL, '$2y$10$RpkgX7.mGQmde912.TGF7ODJMu55GXWrDcFmACfFdhXpjwHlUoyGq', 'BpRZ7lzgIU4ZA9Hle4SCY81V9eEg61f2m3oWwqkLkbnh0j5JYuy7RyJoekBo', 1, '9076975c6e871d0287fa797e4c867aaa.jpg', 1, '', 0, '$2y$10$CoRJl2CrG/IWn3UrGFQkseISNQh.643fbNZPwm7pJe8qpGtIzYkC2'),
(2, 'Mauricio Ferreira de Jesus', 'mauriciolinkinpark2012@hotmail.com', NULL, '$2y$10$CDZKAfOnd2bVeTrhnsVki.5t/R1fNr2iFIt.8sDkJD6h4q/t/c4Xe', NULL, 1, 'user-default.png', 2, '2021-02-23', 0, NULL),
(3, 'Usuario Juridico', 'Teste@hotmail.com', NULL, '$2y$10$jLgaA9qAsmRzLXIZda9GWuQVZtxqSEoxZjjNSQ86ki4.8LhKJRYwu', NULL, 5, 'user-default.png', 1, '2021-03-22', 0, NULL),
(4, 'user juridico2', 'Juridico@hotmail.com', NULL, '$2y$10$NPwgsP0d9iESUOtVLEJixuCnYhOJLWYX5zuot0aVm7rh7MMvUQcIW', NULL, 5, 'user-default.png', 1, '2021-03-24', 0, NULL),
(5, 'Usuario Operacao', 'Operacao@hotmail.com', NULL, '$2y$10$95CPtpzV1wlfb2qTrVH2eu8y.iaBVnYuoQjjr28mDe8EIfkzECPMK', NULL, 3, 'user-default.png', 1, '2021-03-29', 0, '$2y$10$y7mCxG6sIIXliUqhGmH7rOljVBLIBVJG45ZjIX7qCBYfkKYH6weDm'),
(6, 'GestaoUsuario', 'gestao@hotmail.com', NULL, '$2y$10$VxB369iJO3oiThgdgYgqnu6CgRcH3nJQJkQEkMol09MhNcBphKET6', NULL, 2, 'user-default.png', 1, '2021-03-29', 0, '$2y$10$4aadvOjBsQgM4nKZrMXMReWyNBZtFX9DESUxOlUXWWBPtZLCqrv3W'),
(7, 'comercialUsuario', 'comercializacao@hotmail.com', NULL, '$2y$10$pk8.vZ1adgHXB2aHFMGi8eJugmYzUoSqwVOMGuv2mTtVz0.xlCizO', NULL, 4, 'user-default.png', 1, '2021-03-29', 0, NULL),
(9, 'operacaoNovo', 'operacaoNovo@hotmail.com', NULL, '$2y$10$SgMMUZG3uI.o31acN450Q.PigvacCiR/rFhFiREGXNmqKh4fFIGFC', NULL, 3, 'user-default.png', 1, '2021-04-27', 0, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `bank_slip`
--
ALTER TABLE `bank_slip`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `bank_slip_return`
--
ALTER TABLE `bank_slip_return`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `bank_slip_return_info`
--
ALTER TABLE `bank_slip_return_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `bank_slip_sends`
--
ALTER TABLE `bank_slip_sends`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cancel_contact_info`
--
ALTER TABLE `cancel_contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `change_lot_info`
--
ALTER TABLE `change_lot_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `change_owner_info`
--
ALTER TABLE `change_owner_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `contact_sale`
--
ALTER TABLE `contact_sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__clients` (`id_user`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `index`
--
ALTER TABLE `index`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `index_value`
--
ALTER TABLE `index_value`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `internal_accounts`
--
ALTER TABLE `internal_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `interprises`
--
ALTER TABLE `interprises`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `juridical_contacts`
--
ALTER TABLE `juridical_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `juridical_updates`
--
ALTER TABLE `juridical_updates`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__interprises` (`id_interprise`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `notification_index_value`
--
ALTER TABLE `notification_index_value`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `programed_payments`
--
ALTER TABLE `programed_payments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `refinancing_info`
--
ALTER TABLE `refinancing_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `reissue_contact_info`
--
ALTER TABLE `reissue_contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_sales_interprises` (`id_interprise`),
  ADD KEY `FK_sales_lot` (`id_lot`);

--
-- Índices para tabela `transfers_banks`
--
ALTER TABLE `transfers_banks`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `unscheduled_payments`
--
ALTER TABLE `unscheduled_payments`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `bank_slip`
--
ALTER TABLE `bank_slip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `bank_slip_return`
--
ALTER TABLE `bank_slip_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `bank_slip_return_info`
--
ALTER TABLE `bank_slip_return_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `bank_slip_sends`
--
ALTER TABLE `bank_slip_sends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `cancel_contact_info`
--
ALTER TABLE `cancel_contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `change_lot_info`
--
ALTER TABLE `change_lot_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `change_owner_info`
--
ALTER TABLE `change_owner_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `contact_sale`
--
ALTER TABLE `contact_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `index`
--
ALTER TABLE `index`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `index_value`
--
ALTER TABLE `index_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de tabela `internal_accounts`
--
ALTER TABLE `internal_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `interprises`
--
ALTER TABLE `interprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `juridical_contacts`
--
ALTER TABLE `juridical_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `juridical_updates`
--
ALTER TABLE `juridical_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `lots`
--
ALTER TABLE `lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `notification_index_value`
--
ALTER TABLE `notification_index_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4702;

--
-- AUTO_INCREMENT de tabela `programed_payments`
--
ALTER TABLE `programed_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `refinancing_info`
--
ALTER TABLE `refinancing_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `reissue_contact_info`
--
ALTER TABLE `reissue_contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `transfers_banks`
--
ALTER TABLE `transfers_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `unscheduled_payments`
--
ALTER TABLE `unscheduled_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `lots`
--
ALTER TABLE `lots`
  ADD CONSTRAINT `FK__interprises` FOREIGN KEY (`id_interprise`) REFERENCES `interprises` (`id`);

--
-- Limitadores para a tabela `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `FK_sales_interprises` FOREIGN KEY (`id_interprise`) REFERENCES `interprises` (`id`),
  ADD CONSTRAINT `FK_sales_lot` FOREIGN KEY (`id_lot`) REFERENCES `lots` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
