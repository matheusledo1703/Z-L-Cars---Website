CREATE TABLE `Pais`(
    `id_pais` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nm_pais` VARCHAR(50) NOT NULL
);
CREATE TABLE `Estado`(
    `id_estado` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ds_estado` VARCHAR(50) NOT NULL,
    `id_pais` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `Cidade`(
    `id_cidade` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ds_cidade` VARCHAR(50) NOT NULL,
    `id_estado` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `Endereco`(
    `id_endereco` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ds_endereco` VARCHAR(100) NOT NULL,
    `nr_endereco` VARCHAR(10) NOT NULL,
    `bairro` VARCHAR(50) NOT NULL,
    `nr_cep` VARCHAR(10) NOT NULL,
    `id_cidade` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `Pessoa`(
    `id_pessoa` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nome_pessoa` VARCHAR(100) NOT NULL,
    `data_nascimento` DATE NOT NULL,
    `ds_sexo` VARCHAR(10) NOT NULL,
    `id_endereco` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `Contato`(
    `id_contato` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nr_telefone` VARCHAR(20) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `id_pessoa` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `Tipo_Documento`(
    `id_tipodoc` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ds_tipodoc` VARCHAR(40) NOT NULL
);
CREATE TABLE `Documento`(
    `id_documento` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nr_documento` VARCHAR(30) NOT NULL,
    `id_tipodoc` BIGINT UNSIGNED NOT NULL,
    `id_pessoa` BIGINT UNSIGNED NOT NULL
);
CREATE TABLE `Tipo_Produto`(
    `id_tipo_produto` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ds_tipo` VARCHAR(30) NOT NULL
);
CREATE TABLE `Produto`(
    `id_produto` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_tipo_produto` BIGINT UNSIGNED NOT NULL,
    `marca` VARCHAR(30) NOT NULL,
    `modelo` VARCHAR(30) NOT NULL,
    `ano` INT NOT NULL,
    `cor` VARCHAR(20) NOT NULL,
    `placa` VARCHAR(10) NOT NULL,
    `km` INT NOT NULL,
    `defeitos` VARCHAR(255) NULL
);
CREATE TABLE `Carrinho`(
    `id_carrinho` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_pessoa` BIGINT UNSIGNED NOT NULL
);
ALTER TABLE
    `Carrinho` ADD UNIQUE `carrinho_id_pessoa_unique`(`id_pessoa`);
CREATE TABLE `Carrinho_Produto`(
    `id_carrinho_produto` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_carrinho` BIGINT UNSIGNED NOT NULL,
    `id_produto` BIGINT UNSIGNED NOT NULL,
    `quantidade` INT NOT NULL
);
ALTER TABLE
    `Contato` ADD CONSTRAINT `contato_id_pessoa_foreign` FOREIGN KEY(`id_pessoa`) REFERENCES `Pessoa`(`id_pessoa`);
ALTER TABLE
    `Endereco` ADD CONSTRAINT `endereco_id_cidade_foreign` FOREIGN KEY(`id_cidade`) REFERENCES `Cidade`(`id_cidade`);
ALTER TABLE
    `Documento` ADD CONSTRAINT `documento_id_tipodoc_foreign` FOREIGN KEY(`id_tipodoc`) REFERENCES `Tipo_Documento`(`id_tipodoc`);
ALTER TABLE
    `Carrinho_Produto` ADD CONSTRAINT `carrinho_produto_id_produto_foreign` FOREIGN KEY(`id_produto`) REFERENCES `Produto`(`id_produto`);
ALTER TABLE
    `Carrinho_Produto` ADD CONSTRAINT `carrinho_produto_id_carrinho_foreign` FOREIGN KEY(`id_carrinho`) REFERENCES `Carrinho`(`id_carrinho`);
ALTER TABLE
    `Documento` ADD CONSTRAINT `documento_id_pessoa_foreign` FOREIGN KEY(`id_pessoa`) REFERENCES `Pessoa`(`id_pessoa`);
ALTER TABLE
    `Estado` ADD CONSTRAINT `estado_id_pais_foreign` FOREIGN KEY(`id_pais`) REFERENCES `Pais`(`id_pais`);
ALTER TABLE
    `Pessoa` ADD CONSTRAINT `pessoa_id_pessoa_foreign` FOREIGN KEY(`id_pessoa`) REFERENCES `Carrinho`(`id_pessoa`);
ALTER TABLE
    `Produto` ADD CONSTRAINT `produto_id_tipo_produto_foreign` FOREIGN KEY(`id_tipo_produto`) REFERENCES `Tipo_Produto`(`id_tipo_produto`);
ALTER TABLE
    `Cidade` ADD CONSTRAINT `cidade_id_estado_foreign` FOREIGN KEY(`id_estado`) REFERENCES `Estado`(`id_estado`);
ALTER TABLE
    `Pessoa` ADD CONSTRAINT `pessoa_id_endereco_foreign` FOREIGN KEY(`id_endereco`) REFERENCES `Endereco`(`id_endereco`);

    INSERT INTO Tipo_Produto (id_tipo_produto, ds_tipo) VALUES
(1, 'Carro'),
(2, 'Moto'),
(3, 'Caminhonete');
 
-- ============ CARROS (id_tipo_produto = 1) ============
INSERT INTO Produto (id_tipo_produto, marca, modelo, ano, cor, placa, km, defeitos) VALUES
(1, 'CHEVROLET', 'ONIX', 2024, 'BRANCO', 'AAA0A00', 0, NULL),
(1, 'CHEVROLET', 'ONIX', 2024, 'CINZA',  'BBB0B00', 0, NULL),
(1, 'CHEVROLET', 'ONIX', 2024, 'CINZA',  'CCC0C00', 0, NULL),
(1, 'VOLKSWAGEN', 'VIRTUS', 2024, 'BRANCO', 'DDD0D00', 0, NULL);
 
-- ============ MOTOS (id_tipo_produto = 2) ============
INSERT INTO Produto (id_tipo_produto, marca, modelo, ano, cor, placa, km, defeitos) VALUES
(2, 'HONDA', 'BROS 160', 2024, 'VERMELHA', 'EEE0E00', 0, NULL),
(2, 'HONDA', 'CG 150 FAN', 2024, 'CINZA', 'FFF0F00', 0, NULL);
 
-- ============ CAMINHONETES (id_tipo_produto = 3) ============
INSERT INTO Produto (id_tipo_produto, marca, modelo, ano, cor, placa, km, defeitos) VALUES
(3, 'CHEVROLET', 'S10', 2024, 'PRETA', 'GGG0G00', 0, NULL),
(3, 'FORD', 'RANGER', 2024, 'BRANCA', 'HHH0H00', 0, NULL);
 