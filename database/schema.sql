-- Banco de dados Educatudo Platform
-- Schema baseado na documentação completa

CREATE DATABASE IF NOT EXISTS educatudo_platform;
USE educatudo_platform;

-- Tabela: escolas
CREATE TABLE escolas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    subdominio VARCHAR(100) UNIQUE NOT NULL,
    cnpj VARCHAR(18),
    logo_url VARCHAR(500),
    cor_primaria VARCHAR(7) DEFAULT '#007bff',
    cor_secundaria VARCHAR(7) DEFAULT '#6c757d',
    ativa BOOLEAN DEFAULT TRUE,
    plano ENUM('basico', 'avancado', 'premium') DEFAULT 'basico',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela: usuarios (todos os logins)
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    escola_id INT,
    tipo ENUM('super_admin', 'admin_escola', 'professor', 'aluno', 'pai') NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    senha_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE CASCADE,
    INDEX idx_escola_tipo (escola_id, tipo),
    INDEX idx_email (email)
);

-- Tabela: alunos
CREATE TABLE alunos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    ra VARCHAR(50) NOT NULL,
    turma_id INT,
    serie VARCHAR(50),
    data_nasc DATE,
    responsavel_id INT,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    UNIQUE KEY unique_ra_escola (ra, usuario_id)
);

-- Tabela: professores
CREATE TABLE professores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    codigo_prof VARCHAR(50) NOT NULL,
    materias JSON,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_codigo_prof (codigo_prof, usuario_id)
);

-- Tabela: pais
CREATE TABLE pais (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    cpf VARCHAR(14),
    telefone VARCHAR(20),
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela: turmas
CREATE TABLE turmas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    escola_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    ano_letivo INT NOT NULL,
    serie VARCHAR(50),
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE CASCADE,
    INDEX idx_escola_ano (escola_id, ano_letivo)
);

-- Tabela: materias
CREATE TABLE materias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    escola_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    professor_id INT,
    FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE SET NULL,
    INDEX idx_escola (escola_id)
);

-- Tabela: jornadas
CREATE TABLE jornadas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    professor_id INT NOT NULL,
    turma_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    estrutura JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE,
    FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    INDEX idx_professor (professor_id),
    INDEX idx_turma (turma_id)
);

-- Tabela: exercicios
CREATE TABLE exercicios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jornada_id INT NOT NULL,
    enunciado TEXT NOT NULL,
    resposta TEXT,
    tipo ENUM('multipla_escolha', 'dissertativa') DEFAULT 'multipla_escolha',
    gerado_ia BOOLEAN DEFAULT FALSE,
    aprovado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (jornada_id) REFERENCES jornadas(id) ON DELETE CASCADE,
    INDEX idx_jornada (jornada_id)
);

-- Tabela: redacoes
CREATE TABLE redacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id INT NOT NULL,
    tema VARCHAR(255) NOT NULL,
    texto TEXT,
    imagem_url VARCHAR(500),
    correcao TEXT,
    nota DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    INDEX idx_aluno (aluno_id)
);

-- Tabela: relatorios
CREATE TABLE relatorios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id INT NOT NULL,
    tipo ENUM('desempenho', 'jornada', 'redacao') NOT NULL,
    dados JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    INDEX idx_aluno_tipo (aluno_id, tipo)
);

-- Tabela: assinaturas (controle de planos)
CREATE TABLE assinaturas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    escola_id INT NOT NULL,
    plano ENUM('basico', 'avancado', 'premium') NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    ativa BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (escola_id) REFERENCES escolas(id) ON DELETE CASCADE,
    INDEX idx_escola_ativa (escola_id, ativa)
);

-- Inserir dados iniciais
INSERT INTO escolas (nome, subdominio, plano) VALUES
('Escola Demo', 'demo', 'avancado'),
('Colégio Exemplo', 'colegio', 'basico');

-- Usuário super admin padrão
INSERT INTO usuarios (tipo, nome, email, senha_hash) VALUES
('super_admin', 'Administrador Global', 'admin@educatudo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Admin da escola demo
INSERT INTO usuarios (escola_id, tipo, nome, email, senha_hash) VALUES
(1, 'admin_escola', 'Admin Escola Demo', 'admin@demo.educatudo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Professor exemplo
INSERT INTO usuarios (escola_id, tipo, nome, email, senha_hash) VALUES
(1, 'professor', 'Professor João', 'joao@demo.educatudo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO professores (usuario_id, codigo_prof, materias) VALUES
(3, 'PROF001', '["Matemática", "Física"]');

-- Aluno exemplo
INSERT INTO usuarios (escola_id, tipo, nome, email, senha_hash) VALUES
(1, 'aluno', 'Aluno Maria', 'maria@demo.educatudo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO alunos (usuario_id, ra, serie, data_nasc) VALUES
(4, 'RA001', '1º Ano', '2010-05-15');

-- Turma exemplo
INSERT INTO turmas (escola_id, nome, ano_letivo, serie) VALUES
(1, '1º A', 2024, '1º Ano');

-- Matéria exemplo
INSERT INTO materias (escola_id, nome, professor_id) VALUES
(1, 'Matemática', 1);

-- Assinatura exemplo
INSERT INTO assinaturas (escola_id, plano, data_inicio, data_fim) VALUES
(1, 'avancado', '2024-01-01', '2024-12-31');
