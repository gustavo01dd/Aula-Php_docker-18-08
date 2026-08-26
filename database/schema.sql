USE controle_financeiro;
-- Tabela de Categorias Financeiras
CREATE TABLE IF NOT EXISTS `categorias` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `cor` VARCHAR(7) NOT NULL DEFAULT '#6c757d',
    `tipo` ENUM('receita', 'despesa') NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Transações Financeiras (Receitas e Despesas)
CREATE TABLE IF NOT EXISTS `transacoes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `descricao` VARCHAR(255) NOT NULL,
    `valor` DECIMAL(10, 2) NOT NULL,
    `tipo` ENUM('receita', 'despesa') NOT NULL,
    `categoria_id` INT NOT NULL,
    `data_transacao` DATE NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_transacoes_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

USE controle_financeiro;

-- 1. Inserindo dados na tabela 'categorias'
INSERT INTO `categorias` (`nome`, `cor`, `tipo`) VALUES 
('Salário', '#28a745', 'receita'),       
('Freelance', '#17a2b8', 'receita'),     
('Alimentação', '#dc3545', 'despesa'),  
('Transporte', '#ffc107', 'despesa'),    
('Moradia', '#6c757d', 'despesa'),       
('Lazer', '#6f42c1', 'despesa');         


-- 2. Inserindo dados na tabela 'transacoes'
INSERT INTO `transacoes` (`descricao`, `valor`, `tipo`, `categoria_id`, `data_transacao`) VALUES 
('Salário do mês', 4500.00, 'receita', 1, '2023-10-05'),
('Projeto de site (Freela)', 1200.00, 'receita', 2, '2023-10-10'),
('Compra no Supermercado', 540.50, 'despesa', 3, '2023-10-12'),
('Aluguel', 1500.00, 'despesa', 5, '2023-10-15'),
('Uber para reunião', 35.00, 'despesa', 4, '2023-10-18'),
('Cinema e pipoca', 85.00, 'despesa', 6, '2023-10-20'),
('Padaria', 22.30, 'despesa', 3, '2023-10-22'),
('Abastecimento do carro', 200.00, 'despesa', 4, '2023-10-25');
