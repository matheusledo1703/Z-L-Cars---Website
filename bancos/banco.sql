-- ============================================================
-- Z&L CARS — Banco de Dados
-- Execute este arquivo no MySQL da sua máquina virtual
-- ============================================================

-- 1. Cria o banco
CREATE DATABASE IF NOT EXISTS zlcars_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zlcars_db;

-- 2. Cria a tabela de veículos
CREATE TABLE IF NOT EXISTS veiculos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100)  NOT NULL,
    categoria   ENUM('carro','moto','camioneta') NOT NULL,
    ano         YEAR,
    km          INT           DEFAULT 0,
    preco       DECIMAL(12,2),
    combustivel VARCHAR(30),
    descricao   TEXT,
    ativo       TINYINT(1)    DEFAULT 1,
    criado_em   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- 3. Insere os carros solicitados
--    (Valores em branco — você preenche depois, veja o tutorial abaixo)
INSERT INTO veiculos (nome, categoria, ano, km, preco, combustivel, descricao) VALUES
('Cruze',    'carro', NULL, NULL, NULL, NULL, NULL),
('Onix',     'carro', NULL, NULL, NULL, NULL, NULL),
('Onix',     'carro', NULL, NULL, NULL, NULL, NULL),
('Onix',     'carro', NULL, NULL, NULL, NULL, NULL),
('Volvo V40','carro', NULL, NULL, NULL, NULL, NULL),
('Fox',      'carro', NULL, NULL, NULL, NULL, NULL),
('Captiva',  'carro', NULL, NULL, NULL, NULL, NULL);

-- ============================================================
-- COMO PREENCHER OS DADOS DOS VEÍCULOS
-- ============================================================
-- Após inserir, você pode atualizar cada um pelo ID.
-- Use o comando UPDATE:
--
-- Exemplo — atualizar o Cruze (id = 1):
--   UPDATE veiculos
--   SET ano = 2020, km = 45000, preco = 85000.00,
--       combustivel = 'Flex', descricao = 'Cruze LT automático, completo.'
--   WHERE id = 1;
--
-- Exemplo — atualizar todos os Onix de uma vez (ids 2, 3 e 4):
--   UPDATE veiculos SET preco = 62000.00 WHERE nome = 'Onix';
--
-- Para ver todos os veículos cadastrados:
--   SELECT * FROM veiculos;
--
-- Para adicionar um novo veículo:
--   INSERT INTO veiculos (nome, categoria, ano, km, preco, combustivel, descricao)
--   VALUES ('Hilux', 'camioneta', 2022, 30000, 195000.00, 'Diesel', 'Hilux SR 4x4');
-- ============================================================
