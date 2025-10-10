# Atualização do Sistema de Exercícios

## Data: 10/10/2025

## Resumo das Mudanças

Esta atualização remove completamente a funcionalidade de "Gerar com IA" e implementa um banco de questões global onde é possível criar listas de exercícios manualmente ou importando via JSON.

## Mudanças no Banco de Dados

### 1. Tabela `exercicios` (Modificada)
- **Removido:** Coluna `gerado_ia` (BOOLEAN)
- **Removido:** Coluna `aprovado` (BOOLEAN)
- **Mantido:** Estrutura existente para questões dissertativas/personalizadas criadas diretamente nas jornadas

### 2. Nova Tabela: `listas_exercicios`
Armazena listas de exercícios globais (acessíveis por todas as escolas).

```sql
CREATE TABLE listas_exercicios (
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
);
```

### 3. Nova Tabela: `questoes`
Armazena as questões que pertencem às listas.

```sql
CREATE TABLE questoes (
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
);
```

### 4. Nova Tabela: `alternativas`
Armazena alternativas para questões de múltipla escolha.

```sql
CREATE TABLE alternativas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    questao_id INT NOT NULL,
    letra VARCHAR(1) NOT NULL,
    texto TEXT NOT NULL,
    FOREIGN KEY (questao_id) REFERENCES questoes(id) ON DELETE CASCADE,
    INDEX idx_questao (questao_id)
);
```

### 5. Nova Tabela: `jornada_questoes`
Relaciona questões do banco global com as jornadas dos professores.

```sql
CREATE TABLE jornada_questoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jornada_id INT NOT NULL,
    questao_id INT NOT NULL,
    ordem INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jornada_id) REFERENCES jornadas(id) ON DELETE CASCADE,
    FOREIGN KEY (questao_id) REFERENCES questoes(id) ON DELETE CASCADE,
    INDEX idx_jornada (jornada_id),
    INDEX idx_questao (questao_id)
);
```

## Novos Arquivos Criados

### Models
- `app/Models/ListaExercicio.php` - Gerencia listas de exercícios
- `app/Models/Questao.php` - Gerencia questões
- `app/Models/JornadaQuestao.php` - Gerencia relacionamento entre jornadas e questões

### Views
- `Views/global_admin/exercicios-import.php` - Interface de importação JSON
- `Views/global_admin/lista-create.php` - Formulário de criação manual
- `Views/global_admin/lista-details.php` - Visualização de detalhes da lista
- `Views/global_admin/lista-edit.php` - Formulário de edição

### Migrations
- `database/migracao_exercicios.sql` - Script SQL para aplicar as mudanças

## Arquivos Modificados

### Controllers
- `app/Controllers/GlobalAdminController.php`
  - Adicionado: `exercicios()` - Lista listas de exercícios
  - Adicionado: `importExerciciosForm()` - Formulário de importação
  - Adicionado: `importExercicios()` - Processa importação JSON
  - Adicionado: `createLista()` - Formulário de criação
  - Adicionado: `storeLista()` - Salva nova lista
  - Adicionado: `showLista()` - Detalhes da lista
  - Adicionado: `editLista()` - Formulário de edição
  - Adicionado: `updateLista()` - Atualiza lista
  - Adicionado: `deleteLista()` - Remove lista

### Views
- `Views/global_admin/exercicios.php`
  - **Removido:** Card "Gerados por IA"
  - **Removido:** Botão "Gerar com IA"
  - **Removido:** Filtro "IA"
  - **Atualizado:** Mostra listas de exercícios ao invés de exercícios individuais
  - **Atualizado:** Estatísticas agora mostram dados reais do banco

### Routes
- `index.php`
  - Adicionado: `GET /admin/exercicios/import` - Formulário de importação
  - Adicionado: `POST /admin/exercicios/import` - Processa importação
  - Adicionado: `GET /admin/exercicios/listas/create` - Criar lista
  - Adicionado: `POST /admin/exercicios/listas` - Salvar lista
  - Adicionado: `GET /admin/exercicios/listas/{id}` - Ver detalhes
  - Adicionado: `GET /admin/exercicios/listas/{id}/edit` - Editar lista
  - Adicionado: `PUT /admin/exercicios/listas/{id}` - Atualizar lista
  - Adicionado: `DELETE /admin/exercicios/listas/{id}` - Excluir lista

### Core
- `app/Core/Request.php`
  - Adicionado: `getJsonBody()` - Método para obter corpo JSON da requisição

### Schema
- `database/schema.sql`
  - **Modificado:** Tabela `exercicios` (removidas colunas de IA)
  - **Adicionado:** Tabelas `listas_exercicios`, `questoes`, `alternativas`, `jornada_questoes`

## Como Aplicar a Migração

1. **Backup do Banco de Dados:**
   ```bash
   mysqldump -u usuario -p educatudo_platform > backup_antes_migracao.sql
   ```

2. **Aplicar a Migração:**
   ```bash
   mysql -u usuario -p educatudo_platform < database/migracao_exercicios.sql
   ```

3. **Verificar:**
   - Acessar `/admin/exercicios` no sistema
   - Verificar se as estatísticas aparecem zeradas (esperado)
   - Testar criação manual de lista
   - Testar importação JSON

## Formato JSON para Importação

```json
{
  "exercicios": [
    {
      "materia": "Matemática",
      "serie": "1º ano do Ensino Médio",
      "nivel_dificuldade": "Fácil",
      "titulo_lista": "Equações do 1º Grau",
      "questoes": [
        {
          "id": 1,
          "pergunta": "Qual é o valor de x na equação 2x + 6 = 14?",
          "alternativas": {
            "A": "x = 3",
            "B": "x = 4",
            "C": "x = 5",
            "D": "x = 6"
          },
          "resposta_correta": "B",
          "explicacao": "Subtraindo 6 dos dois lados: 2x = 8. Dividindo ambos os lados por 2, temos x = 4."
        }
      ]
    }
  ]
}
```

**Nota:** O campo `id` nas questões é apenas para organização no JSON e será ignorado na importação.

## Funcionalidades Disponíveis

### Admin Global

1. **Visualizar Banco de Questões**
   - `/admin/exercicios`
   - Lista todas as listas de exercícios
   - Mostra estatísticas (total de listas, questões, matérias)

2. **Criar Lista Manualmente**
   - `/admin/exercicios/listas/create`
   - Formulário dinâmico para adicionar questões
   - Suporte para múltipla escolha e dissertativa

3. **Importar JSON**
   - `/admin/exercicios/import`
   - Upload de arquivo JSON
   - Preview antes de confirmar
   - Validação de formato

4. **Editar Lista**
   - Modificar informações da lista
   - Adicionar/remover/editar questões

5. **Excluir Lista**
   - Remove lista e todas as questões associadas
   - Cascata automática no banco

## Próximos Passos (Futuro)

- [ ] Interface para professores selecionarem questões do banco ao criar jornadas
- [ ] Filtros avançados por matéria, série, nível
- [ ] Exportação de listas para JSON
- [ ] Duplicação de listas
- [ ] Categorias/tags adicionais
- [ ] Imagens nas questões
- [ ] Estatísticas de uso das questões

## Observações Importantes

- **Exercícios globais:** As listas são compartilhadas entre todas as escolas
- **Sem isolamento:** Diferente de matérias e turmas, as listas NÃO possuem `escola_id`
- **Compatibilidade:** A tabela `exercicios` antiga permanece para questões personalizadas
- **Cascata:** Ao excluir uma lista, todas as questões e alternativas são removidas automaticamente

