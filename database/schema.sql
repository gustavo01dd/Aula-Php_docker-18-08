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
