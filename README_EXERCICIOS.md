# Sistema de Exercícios - Guia de Uso

## Visão Geral

O sistema de exercícios foi completamente reformulado para remover a funcionalidade de "Gerar com IA" e implementar um banco de questões global, onde é possível:

1. **Criar listas de exercícios manualmente**
2. **Importar listas a partir de arquivos JSON**
3. **Visualizar e editar listas existentes**
4. **Organizar questões por matéria, série e nível de dificuldade**

## Como Acessar

1. Fazer login como **Super Admin**
2. Acessar o menu **Exercícios** ou ir para `/admin/exercicios`

## Funcionalidades

### 1. Criar Lista Manualmente

**Passo a passo:**

1. Na página de Exercícios, clique em **"Nova Lista"** ou **"Criar Lista Manual"**
2. Preencha as informações da lista:
   - Título da Lista
   - Matéria
   - Série
   - Nível de Dificuldade (Fácil, Médio ou Difícil)
3. Clique em **"Adicionar Questão"** para cada questão que deseja adicionar
4. Para cada questão:
   - Digite a pergunta
   - Escolha o tipo (Múltipla Escolha ou Dissertativa)
   - Se for múltipla escolha:
     - Preencha as 4 alternativas (A, B, C, D)
     - Marque o círculo da alternativa correta
   - Adicione uma explicação (opcional)
5. Clique em **"Criar Lista"** para salvar

**Dica:** Use o botão de lixeira ao lado de cada questão para removê-la.

### 2. Importar Listas via JSON

**Passo a passo:**

1. Prepare um arquivo JSON no formato especificado (veja exemplo abaixo)
2. Na página de Exercícios, clique em **"Importar JSON"**
3. Clique em **"Selecione o arquivo JSON"** e escolha seu arquivo
4. Clique em **"Preview do Arquivo"**
5. Revise as listas que serão importadas
6. Clique em **"Confirmar Importação"**

**Formato do JSON:**

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
        },
        {
          "id": 2,
          "pergunta": "Resolva a equação 3x - 9 = 0.",
          "alternativas": {
            "A": "x = 3",
            "B": "x = -3",
            "C": "x = 9",
            "D": "x = -9"
          },
          "resposta_correta": "A",
          "explicacao": "Somando 9 aos dois lados: 3x = 9. Dividindo por 3, obtemos x = 3."
        }
      ]
    },
    {
      "materia": "Português",
      "serie": "2º ano do Ensino Médio",
      "nivel_dificuldade": "Médio",
      "titulo_lista": "Interpretação de Texto",
      "questoes": [
        {
          "id": 1,
          "pergunta": "Analise o texto a seguir...",
          "alternativas": {
            "A": "Opção A",
            "B": "Opção B",
            "C": "Opção C",
            "D": "Opção D"
          },
          "resposta_correta": "C",
          "explicacao": "A resposta é C porque..."
        }
      ]
    }
  ]
}
```

**Campos obrigatórios:**
- `materia` - Nome da matéria
- `serie` - Série escolar
- `nivel_dificuldade` - Fácil, Médio ou Difícil
- `titulo_lista` - Título descritivo da lista
- `questoes` - Array com pelo menos uma questão
  - `pergunta` - Enunciado da questão
  - `alternativas` - Objeto com as alternativas A, B, C e D
  - `resposta_correta` - Letra da alternativa correta

**Campos opcionais:**
- `explicacao` - Explicação da resposta
- `id` - Identificador sequencial (apenas para organização, será ignorado)

### 3. Visualizar Lista

1. Na página de Exercícios, localize a lista desejada
2. Clique no ícone de **olho** na coluna "Ações"
3. Você verá:
   - Informações da lista (matéria, série, nível)
   - Todas as questões com suas alternativas
   - Alternativas corretas destacadas em verde
   - Explicações (se houver)

### 4. Editar Lista

1. Na página de Exercícios, localize a lista desejada
2. Clique no ícone de **lápis** na coluna "Ações" (ou clique em "Editar" na página de detalhes)
3. Modifique as informações desejadas
4. Adicione, edite ou remova questões
5. Clique em **"Salvar Alterações"**

**Nota:** Ao editar, todas as questões antigas serão substituídas pelas novas.

### 5. Excluir Lista

1. Na página de Exercícios, localize a lista desejada
2. Clique no ícone de **lixeira** na coluna "Ações"
3. Confirme a exclusão

**Atenção:** Esta ação é irreversível! Todas as questões da lista serão permanentemente removidas.

## Estatísticas

Na página principal de Exercícios, você verá:

- **Listas de Exercícios:** Total de listas cadastradas
- **Total de Questões:** Soma de todas as questões de todas as listas
- **Matérias:** Quantidade de matérias diferentes cadastradas

## Filtros e Busca

- Use o campo de busca no topo da tabela para procurar listas por título, matéria ou série
- As listas são ordenadas por data de criação (mais recentes primeiro)

## Badges de Nível de Dificuldade

- **Verde (Fácil):** Questões básicas e introdutórias
- **Amarelo (Médio):** Questões intermediárias
- **Vermelho (Difícil):** Questões avançadas

## Dicas

1. **Organize por matéria e série:** Facilita a busca posterior
2. **Use títulos descritivos:** Ex: "Equações do 1º Grau - Parte 1"
3. **Adicione explicações:** Ajuda alunos a entenderem as respostas
4. **Revise antes de importar:** Use o preview do JSON para verificar os dados
5. **Faça backup do JSON:** Mantenha uma cópia do arquivo JSON antes de importar

## Resolução de Problemas

### "Formato JSON inválido"
- Verifique se o JSON está bem formatado
- Use um validador JSON online (como jsonlint.com)
- Certifique-se de que todos os campos obrigatórios estão presentes

### "Erro ao criar lista"
- Verifique se todos os campos obrigatórios foram preenchidos
- Certifique-se de que pelo menos uma questão foi adicionada
- Para questões de múltipla escolha, todas as 4 alternativas devem estar preenchidas

### Lista não aparece após criação
- Atualize a página (F5)
- Verifique se não há erros no console do navegador
- Entre em contato com o suporte técnico

## Próximos Desenvolvimentos

Em breve, professores poderão:
- Selecionar questões do banco ao criar jornadas
- Filtrar questões por múltiplos critérios
- Exportar listas para JSON
- Duplicar listas existentes

---

**Desenvolvido para Educatudo Platform**  
Versão: 1.0.0 | Data: 10/10/2025

