# 🎓 Atualização: Matérias por Escola

## ✨ O que foi implementado

### 1. **Constraint de Unicidade** (Linha 96)
```sql
UNIQUE KEY unique_materia_escola (escola_id, nome)
```
- Impede que a mesma matéria seja cadastrada duas vezes na mesma escola
- Permite que escolas diferentes tenham matérias com o mesmo nome
- **Exemplo**: "Matemática" pode existir na Escola Demo E no Colégio Exemplo

### 2. **Timestamps** (Linhas 92-93)
```sql
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```
- Rastreia quando a matéria foi criada e atualizada

### 3. **Comentário Explicativo** (Linha 86)
```sql
-- Cada escola tem suas próprias matérias independentes
```

### 4. **Dados de Exemplo Demonstrativos**

#### Escola Demo (ID 1):
- Matemática
- Português
- História
- Geografia
- Ciências

#### Colégio Exemplo (ID 2):
- Matemática (independente da Escola Demo!)
- Física
- Química
- Biologia
- Inglês

---

## 🔄 Como Aplicar no Banco Remoto

### Opção 1: Recriar o Banco (⚠️ Perda de Dados)
```bash
# Via phpMyAdmin ou console MySQL
DROP DATABASE IF EXISTS educatudo_platform;
SOURCE database/schema.sql;
```

### Opção 2: Migração Segura (✅ Recomendado)

#### Passo 1: Adicionar os novos campos
```sql
ALTER TABLE materias 
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
```

#### Passo 2: Adicionar a constraint de unicidade
```sql
-- Verificar se não há duplicatas antes
SELECT escola_id, nome, COUNT(*) 
FROM materias 
GROUP BY escola_id, nome 
HAVING COUNT(*) > 1;

-- Se não houver duplicatas, adicionar a constraint
ALTER TABLE materias 
ADD UNIQUE KEY unique_materia_escola (escola_id, nome);
```

---

## ✅ Verificação

### Testar que funciona corretamente:

```sql
-- 1. Criar matéria na Escola Demo (ID 1)
INSERT INTO materias (escola_id, nome) VALUES (1, 'Artes');
-- ✅ Sucesso!

-- 2. Tentar criar a mesma matéria na Escola Demo novamente
INSERT INTO materias (escola_id, nome) VALUES (1, 'Artes');
-- ❌ Erro: Duplicate entry (esperado!)

-- 3. Criar matéria "Artes" no Colégio Exemplo (ID 2)
INSERT INTO materias (escola_id, nome) VALUES (2, 'Artes');
-- ✅ Sucesso! (escola diferente)

-- 4. Listar matérias por escola
SELECT * FROM materias WHERE escola_id = 1;  -- Escola Demo
SELECT * FROM materias WHERE escola_id = 2;  -- Colégio Exemplo
```

---

## 📋 Checklist de Validação

- [x] Campo `escola_id` é obrigatório (NOT NULL)
- [x] Foreign key para `escolas` com CASCADE delete
- [x] Constraint única composta (escola_id + nome)
- [x] Timestamps para auditoria
- [x] Índice na coluna escola_id para performance
- [x] Dados de exemplo demonstrando isolamento

---

## 🎯 Comportamento Esperado

### ✅ O que PODE:
- Escola Demo ter "Matemática"
- Colégio Exemplo ter "Matemática" (diferente da Demo)
- Cada escola gerenciar suas matérias independentemente

### ❌ O que NÃO PODE:
- Escola Demo ter "Matemática" duplicada
- Cadastrar matéria sem escola_id
- Matérias compartilhadas entre escolas

---

## 📝 Observações Importantes

1. **O código PHP já está preparado**: Os models e controllers já filtram por `escola_id`
2. **Não é necessário alterar código**: Apenas atualizar o banco de dados
3. **Banco remoto**: Como seu banco é remoto, aplique via phpMyAdmin ou cliente MySQL
4. **Backup**: Sempre faça backup antes de alterar o schema em produção

---

**Última atualização**: Schema otimizado para isolamento total de matérias por escola.

