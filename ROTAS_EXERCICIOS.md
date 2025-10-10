# Rotas do Sistema de Exercícios

## URLs Completas (Localhost)

**Base:** `http://localhost/educatudo`

### Listagem e Visualização

| Método | URL | Descrição |
|--------|-----|-----------|
| GET | `/educatudo/admin/exercicios` | Lista todas as listas de exercícios |
| GET | `/educatudo/admin/exercicios/listas/{id}` | Detalhes de uma lista específica |

### Criação Manual

| Método | URL | Descrição |
|--------|-----|-----------|
| GET | `/educatudo/admin/exercicios/listas/create` | Formulário de criação |
| POST | `/educatudo/admin/exercicios/listas` | Salvar nova lista |

### Edição

| Método | URL | Descrição |
|--------|-----|-----------|
| GET | `/educatudo/admin/exercicios/listas/{id}/edit` | Formulário de edição |
| PUT | `/educatudo/admin/exercicios/listas/{id}` | Atualizar lista (AJAX) |
| POST | `/educatudo/admin/exercicios/listas/{id}/update` | Atualizar lista (Form) |

### Exclusão

| Método | URL | Descrição |
|--------|-----|-----------|
| DELETE | `/educatudo/admin/exercicios/listas/{id}` | Excluir lista (AJAX) |
| POST | `/educatudo/admin/exercicios/listas/{id}/delete` | Excluir lista (Form) |

### Importação JSON

| Método | URL | Descrição |
|--------|-----|-----------|
| GET | `/educatudo/admin/exercicios/import` | Formulário de importação |
| POST | `/educatudo/admin/exercicios/import` | Processar importação |

## Acesso Rápido

**Para criar uma nova lista:**
```
http://localhost/educatudo/admin/exercicios/listas/create
```

**Para importar JSON:**
```
http://localhost/educatudo/admin/exercicios/import
```

**Para listar exercícios:**
```
http://localhost/educatudo/admin/exercicios
```

## Observações

1. **Autenticação obrigatória:** Todas as rotas exigem login como Super Admin
2. **Middleware:** `AuthMiddleware` + `SuperAdminMiddleware`
3. **Formato JSON:** Para requisições AJAX, enviar `Content-Type: application/json`
4. **Métodos HTTP:** PUT e DELETE via AJAX, POST via formulários HTML

## CRUD Completo Disponível

✅ **Create** - Criação manual e importação JSON  
✅ **Read** - Listagem e visualização de detalhes  
✅ **Update** - Edição completa de listas e questões  
✅ **Delete** - Exclusão com confirmação  

Todos os métodos estão implementados e funcionando!

