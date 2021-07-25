-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Jul-2021 às 01:28
-- Versão do servidor: 10.4.19-MariaDB
-- versão do PHP: 7.3.28

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
(1, 1, 1, 10, 1, 2, 1, 90, NULL, '78508b7bee60ad5a8b8df288f646b806.pdf', 1, '29934', '2021-07-25', '20:24:51', '', 0),
(2, 1, 2, 10, 1, 2, 1, 90, NULL, '0947f9f5a2ca8315720a2a90344e9492.pdf', 1, '63602', '2021-07-25', '20:24:51', '', 0),
(3, 1, 3, 10, 1, 2, 1, 90, NULL, '0c3f35a45d8b000e86e8cc65eb6c7374.pdf', 1, '41289', '2021-07-25', '20:24:51', '', 0);

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
(1, '1,2,3', '0', '54725725.CRM', 1, '2021-07-25', 1);

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
(60, NULL, 'GENESIS LOTEADORA E COLONIZADORA S/S LTDA', NULL, NULL, 2, NULL, NULL, NULL, '00.664.563/0001-84', 1, 1, 1, 'imada@triploa.com', NULL, 'R PARA', 1531, 'SALA 802', '86020-400', 'CENTRO', 'LONDRINA', 'PR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'R PARA', 1531, 'CENTRO', 'LONDRINA', 'SALA 802', 'PR', '86020-400', NULL, 59, NULL);

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
  `representative_name` varchar(450) NOT NULL,
  `representative_marital_status` tinyint(4) NOT NULL,
  `representative_occupation` varchar(100) NOT NULL,
  `representative_rg` varchar(50) NOT NULL,
  `representative_cpf` char(14) NOT NULL,
  `representative_street` varchar(50) NOT NULL,
  `representative_number` varchar(50) NOT NULL,
  `representative_complement` varchar(50) NOT NULL,
  `representative_cep` char(14) NOT NULL,
  `representative_neighborhood` varchar(450) NOT NULL,
  `representative_city` varchar(450) NOT NULL,
  `representative_state` varchar(2) NOT NULL,
  `representative_nationality` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `cnpj`, `street`, `number`, `complement`, `cep`, `neighborhood`, `city`, `state`, `representative_name`, `representative_marital_status`, `representative_occupation`, `representative_rg`, `representative_cpf`, `representative_street`, `representative_number`, `representative_complement`, `representative_cep`, `representative_neighborhood`, `representative_city`, `representative_state`, `representative_nationality`, `status`) VALUES
(1, 'GENESIS LOTEADORA E COLONIZADORA S/S LTDA', '00.664.563/0001-84', 'R PARA', '1531', 'SALA 802', '86020-400', 'CENTRO', 'LONDRINA', 'PR', 'CARLOS SHIGUERU IMADA', 2, 'ENGENHEIRO CIVIL', '536.905 SSP/MS', '595.291.481-00', 'R PARA', '1531', 'SALA 802', '86020-400', 'CENTRO', 'LONDRINA', 'PR', 1, 1),
(2, 'MARCENEIRO ZEN', '36.507.071/0001-22', 'RUA 801', '354', 'APT 501', '88330-717', 'CENTRO', 'BALNEARIO CAMBORIU', 'SC', 'Mauricio', 1, 'Programador', '6565456465445', '782.190.460-07', 'Rua Raul Seixas', '114', 'dsfasfs', '08466-010', 'Vila Roseira II', 'São Paulo', 'SP', 1, 1);

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
(77, '1', 5, '2021-06-01');

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
(6, 'empreendimento usuario', 'são paulo', 'SP', 'sadadadadadadadadadadadadad', '2021-04-11', '1', '1-100', '');

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

--
-- Extraindo dados da tabela `juridical_contacts`
--

INSERT INTO `juridical_contacts` (`id`, `situation`, `file_path`, `id_juridicalUser`, `id_user`, `id_sale`, `register_date`, `register_time`, `status`, `resolution`, `deadline`, `date_authorization_juridical`, `document_juridical`, `process_number`) VALUES
(1, 'Varias parcelas em atraso', 'contactFiles/juridical/c5112a139def0a7dcccd25dda4bcdff4.pdf', 3, 1, 4, '2021-03-24', '00:58:47', 4, 'sdafsajdlfkljsadkfjkj dsafkljkasdjfsadklfj adskljfksadjfkljsdf', '2021-05-24', '2021-03-24', 'contactFiles/juridical/94a2a90b9c217d39236f048303ac6c72.docx', '4654654151'),
(2, 'dfsfasdfasdfsadfsdafsadf', '', 3, 1, 4, '2021-03-25', '14:19:31', 4, 'fdsfsd fsdf sdfsdf sdfsdfsdsdfsdf', '2021-05-25', '2021-03-25', 'contactFiles/juridical/e368be615c439e0527653dabbdd52711.pdf', '45468798');

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
(4, 2, 'Quadra B Lot 10', 10, 'B', '1500', 'saddddddddddddddddddddddd', 1, NULL, NULL, '25.000,00', '30.000,00', '5.000,00', NULL, 1),
(5, 1, 'Quadra ER; Lot 88', 88, 'ER', '5000', 'saddddddddddddddddddddddd', NULL, NULL, NULL, '100.000,00', '150.000,00', '5.000,00', NULL, 1),
(6, 2, 'Quadra C; Lot 55', 55, 'C', '8000', 'saddddddddddddddddddddddd', NULL, NULL, NULL, '50.000,00', '65.000,00', '1.000,00', NULL, 1),
(7, 1, 'Quadra ER Lot 878', 878, 'ER', '5000', 'saddddddddddddddddddddddd', 1, NULL, NULL, '1.000,00', '1.500,00', NULL, NULL, 1),
(8, 6, 'Quadra fdssdf; Lot 8787', 8787, 'fdssdf', '68989', NULL, NULL, NULL, NULL, '150,00', '2.000,00', '1.500,00', NULL, 1),
(9, 6, 'Quadra B; Lot 155555', 155555, 'B', '5000', 'dsafffffffffffffffa', NULL, '34.567', '101', '75.000,00', '115.000,00', '7.500,00', NULL, 2);

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
(25, 1, '', 1, 'Parcelas 13 até 24', 5, 1),
(26, 1, '', 1, 'Parcelas 13 até 24', 5, 1),
(47, 2, '', 1, ' Indices das datas: 2020-11-01', 5, 2),
(55, 4, '', 1, ' Indices das datas: 2021-02-01', 5, 2),
(61, 1, '', 1, ' Indices das datas: 2021-09-01', 5, 2),
(63, 1, '', 1, ' Indices das datas: 2021-03-01', 5, 2),
(64, 1, '', 1, ' Indices das datas: 2021-04-01', 5, 2),
(65, 1, '', 1, ' Indices das datas: 2021-04-01', 5, 2),
(67, 2, '', 1, 'Parcelas 13 até 24', 5, 1),
(68, 2, '', 1, 'Parcelas 13 até 24', 5, 1),
(69, 2, '', 1, 'Parcelas 24 até 35', 5, 1),
(76, 3, '', 1, ' Indices das datas: 2010-03-01,2010-04-01,2010-05-01,2010-06-01,2010-07-01,2010-08-01,2010-09-01,2010-10-01,2010-11-01,2010-12-01,2011-01-01,2011-02-01,2011-03-01,2011-04-01,2011-05-01,2011-06-01,2011-07-01', 5, 2),
(77, 3, '2011-12-01,2012-01-01,2012-02-01,2012-03-01,2012-04-01,2012-05-01,2012-06-01,2012-07-01,2012-08-01,2012-09-01,2012-10-01,2012-11-01,2012-12-01', 0, ' Indices das datas: 2011-08-01,2011-09-01,2011-10-01,2011-11-01,2011-12-01,2012-01-01,2012-02-01,2012-03-01,2012-04-01,2012-05-01,2012-06-01,2012-07-01,2012-08-01,2012-09-01,2012-10-01,2012-11-01,2012-12-01', 5, 2),
(81, 4, '', 1, ' Indices das datas: 2021-05-01', 5, 2),
(82, 4, '', 1, ' Indices das datas: 2021-04-01', 5, 2),
(83, 4, '', 1, ' Indices das datas: 2021-05-01', 5, 2),
(84, 1, '1970-01-01,1970-03-01,1970-03-01,1970-05-01,1970-05-01,1970-07-01,1970-07-01,1970-08-01,1970-10-01,1970-10-01,1970-12-01,1970-12-01,1971-01-01,1971-03-01,1971-03-01,1971-05-01,1971-05-01,1971-07-01,1971-07-01,1971-08-01,1971-10-01,1971-10-01,1971-12-01,1971-12-01,1972-01-01,1972-03-01,1972-03-01,1972-05-01,1972-05-01,1972-07-01,1972-07-01,1972-08-01,1972-10-01', 0, ' Indices das datas: 1969-12-01,1970-01-01,1970-03-01,1970-03-01,1970-05-01,1970-05-01,1970-07-01,1970-07-01,1970-08-01,1970-10-01,1970-10-01,1970-12-01,1970-12-01,1971-01-01,1971-03-01,1971-03-01,1971-05-01,1971-05-01,1971-07-01,1971-07-01,1971-08-01,1971-10-01,1971-10-01,1971-12-01,1971-12-01,1972-01-01,1972-03-01,1972-03-01,1972-05-01,1972-05-01,1972-07-01,1972-07-01,1972-08-01,1972-10-01', 5, 2),
(85, 2, '', 1, ' Indices das datas: 2021-06-01', 5, 2);

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
(1, 1, 1, '2021-08-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '78508b7bee60ad5a8b8df288f646b806.pdf', '29934', 2, '0,00', NULL, NULL, 1),
(2, 1, 2, '2021-09-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '0947f9f5a2ca8315720a2a90344e9492.pdf', '63602', 2, '0,00', NULL, NULL, 1),
(3, 1, 3, '2021-10-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '0c3f35a45d8b000e86e8cc65eb6c7374.pdf', '41289', 2, '0,00', NULL, NULL, 1),
(4, 1, 4, '2021-11-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '93363', 0, '0,00', NULL, NULL, NULL),
(5, 1, 5, '2021-12-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '42107', 0, '0,00', NULL, NULL, NULL),
(6, 1, 6, '2022-01-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '75397', 0, '0,00', NULL, NULL, NULL),
(7, 1, 7, '2022-02-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '27249', 0, '0,00', NULL, NULL, NULL),
(8, 1, 8, '2022-03-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '68119', 0, '0,00', NULL, NULL, NULL),
(9, 1, 9, '2022-04-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '46566', 0, '0,00', NULL, NULL, NULL),
(10, 1, 10, '2022-05-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '18957', 0, '0,00', NULL, NULL, NULL),
(11, 1, 11, '2022-06-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '49732', 0, '0,00', NULL, NULL, NULL),
(12, 1, 12, '2022-07-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '40831', 0, '0,00', NULL, NULL, NULL),
(13, 1, 13, '2022-08-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '39451', 0, '0,00', NULL, NULL, NULL),
(14, 1, 14, '2022-09-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '97683', 0, '0,00', NULL, NULL, NULL),
(15, 1, 15, '2022-10-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '38139', 0, '0,00', NULL, NULL, NULL),
(16, 1, 16, '2022-11-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '71292', 0, '0,00', NULL, NULL, NULL),
(17, 1, 17, '2022-12-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '15057', 0, '0,00', NULL, NULL, NULL),
(18, 1, 18, '2023-01-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '74521', 0, '0,00', NULL, NULL, NULL),
(19, 1, 19, '2023-02-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '16439', 0, '0,00', NULL, NULL, NULL),
(20, 1, 20, '2023-03-05', '5.375,00', '5.375,00', NULL, '2', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '20011', 0, '0,00', NULL, NULL, NULL);

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
  `annual_rate` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `sales`
--

INSERT INTO `sales` (`id`, `id_interprise`, `id_lot`, `contract_number`, `value`, `input`, `descont`, `parcels`, `index`, `interest_per_year`, `first_parcel`, `expiration_day`, `clients`, `client_payment_id`, `id_clients_porc`, `contract`, `almostFinishFile`, `finishFileSale`, `type`, `propose_date`, `begin_contract_date`, `value_parcel`, `annual_rate`) VALUES
(1, 6, 9, '5656565', '107.500,00', '7.500,00', '0,00', '20', '5', NULL, '2021-08-05', 5, '1', 1, '1-100', 'contractSale/65127915426d689e929bd5f324997cd1.pdf', NULL, NULL, 2, '2021-07-25', '2021-07-25', '5.375,00', 6);

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
(1, 'Mauricio', 'mauricio-ferreira2015@outlook.com', NULL, '$2y$10$RpkgX7.mGQmde912.TGF7ODJMu55GXWrDcFmACfFdhXpjwHlUoyGq', 'uanMtYrMnetin1i4aMdgaKsnwE93oYbQmPvaJhDdaRB4CucZ7Yyoi3Ffehqn', 1, '9076975c6e871d0287fa797e4c867aaa.jpg', 1, '', 0, '$2y$10$gw/7JfC.MILevQRG3gFJXuNsvuof7WtdnUweVMqt8/kY3.xdMoeje'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `bank_slip_return`
--
ALTER TABLE `bank_slip_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bank_slip_return_info`
--
ALTER TABLE `bank_slip_return_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bank_slip_sends`
--
ALTER TABLE `bank_slip_sends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `contact_sale`
--
ALTER TABLE `contact_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT de tabela `internal_accounts`
--
ALTER TABLE `internal_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `interprises`
--
ALTER TABLE `interprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de tabela `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `programed_payments`
--
ALTER TABLE `programed_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `refinancing_info`
--
ALTER TABLE `refinancing_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reissue_contact_info`
--
ALTER TABLE `reissue_contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
