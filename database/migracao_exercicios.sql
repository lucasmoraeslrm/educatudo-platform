-- ============================================
-- Migração: Sistema de Exercícios
-- Data: 2025-10-10
-- Descrição: Remove funcionalidade de IA e cria banco de questões global
-- ============================================

-- 1. Remover colunas de IA da tabela exercicios
ALTER TABLE exercicios DROP COLUMN IF EXISTS gerado_ia;
ALTER TABLE exercicios DROP COLUMN IF EXISTS aprovado;

-- 2. Criar tabela de listas de exercícios (banco global)
CREATE TABLE IF NOT EXISTS listas_exercicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    materia VARCHAR(100) NOT NULL,
    serie VARCHAR(100) NOT NULL,
    nivel_dificuldade ENUM('Fácil', 'Médio', 'Difícil') NOT NULL,
    total_questoes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_materia (materia),
    INDEX idx_serie (serie),
    INDEX idx_nivel (nivel_dificuldade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Criar tabela de questões (banco global)
CREATE TABLE IF NOT EXISTS questoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lista_id INT NOT NULL,
    ordem INT NOT NULL,
    pergunta TEXT NOT NULL,
    tipo ENUM('multipla_escolha', 'dissertativa') DEFAULT 'multipla_escolha',
    resposta_correta VARCHAR(1),
    explicacao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lista_id) REFERENCES listas_exercicios(id) ON DELETE CASCADE,
    INDEX idx_lista (lista_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Criar tabela de alternativas (para questões de múltipla escolha)
CREATE TABLE IF NOT EXISTS alternativas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    questao_id INT NOT NULL,
    letra VARCHAR(1) NOT NULL,
    texto TEXT NOT NULL,
    FOREIGN KEY (questao_id) REFERENCES questoes(id) ON DELETE CASCADE,
    INDEX idx_questao (questao_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Criar tabela de relacionamento jornadas-questões
CREATE TABLE IF NOT EXISTS jornada_questoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jornada_id INT NOT NULL,
    questao_id INT NOT NULL,
    ordem INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jornada_id) REFERENCES jornadas(id) ON DELETE CASCADE,
    FOREIGN KEY (questao_id) REFERENCES questoes(id) ON DELETE CASCADE,
    INDEX idx_jornada (jornada_id),
    INDEX idx_questao (questao_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Fim da migração
-- ============================================

