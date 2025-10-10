<!-- 54def484-0a69-4692-a146-73b459c009a0 3ccbcf3b-975a-4664-af3e-9d38f72984b0 -->
# Corrigir Login e Implementar Admin de Escola Completo

## 1. Corrigir Problema de Autorização nos Middlewares

**Problema:** Middlewares fazem redirect sem usar basePath, causando erro 404.

**Atualizar todos os middlewares em `app/Middleware/`:**

- `AuthMiddleware.php`: Trocar `$response->redirect('/login')` por usar a função `url()`
- `AdminEscolaMiddleware.php`: Trocar `$response->redirect('/login')` e `$response->redirect('/unauthorized')` por usar `url()`
- `SuperAdminMiddleware.php`: Corrigir redirects
- `ProfessorMiddleware.php`: Corrigir redirects
- `AlunoMiddleware.php`: Corrigir redirects
- `PaisMiddleware.php`: Corrigir redirects

**Solução:** Criar método helper nos middlewares para pegar basePath do App e construir URL correta.

## 2. Atualizar Dashboard do Admin Escola

**Modificar `Views/admin_escola/index.php`:**

- Remover números hardcoded (1, 1, 1, 1)
- Usar variável `$estatisticas` passada do controller
- Adicionar estatísticas reais: total_alunos, total_professores, total_turmas, total_materias
- Atualizar links para usar função `url()`

**Modificar `EscolaController@index`:**

- Buscar estatísticas de turmas e matérias também
- Passar dados completos para a view

## 3. Criar CRUD Completo de Turmas

**Criar métodos em `EscolaController.php`:**

- `turmas()`: listar turmas da escola
- `createTurma()`: formulário de criação
- `storeTurma()`: salvar nova turma
- `editTurma($id)`: formulário de edição
- `updateTurma($id)`: atualizar turma
- `deleteTurma($id)`: excluir/desativar turma
- `validateTurma($data, $escolaId, $excludeId = null)`: validação

**Criar views em `Views/escola/turmas/`:**

- `index.php`: Tabela listando turmas (nome, série, ano, total alunos, ações)
- `create.php`: Formulário criar turma (nome, série, ano, capacidade)
- `edit.php`: Formulário editar turma

**Padrão visual:** Seguir mesmo design de `Views/global_admin/` com cards, tabelas Bootstrap, ícones Bootstrap Icons.

## 4. Criar CRUD Completo de Matérias

**Criar métodos em `EscolaController.php`:**

- `materias()`: listar matérias da escola
- `createMateria()`: formulário de criação
- `storeMateria()`: salvar nova matéria
- `editMateria($id)`: formulário de edição
- `updateMateria($id)`: atualizar matéria
- `deleteMateria($id)`: excluir matéria
- `validateMateria($data, $escolaId, $excludeId = null)`: validação

**Criar views em `Views/escola/materias/`:**

- `index.php`: Tabela listando matérias (nome, professor, ações)
- `create.php`: Formulário criar matéria (nome, professor)
- `edit.php`: Formulário editar matéria

## 5. Atualizar Views Existentes do Admin Escola

**Atualizar todas as views em `Views/escola/` para:**

- Usar função `url()` em todos os links e formulários
- Seguir padrão visual do global_admin (cards, tabelas, headers, botões)
- Adicionar breadcrumbs e navegação consistente
- Usar ícones Bootstrap Icons
- Adicionar mensagens de sucesso/erro via `$_SESSION`

**Views a atualizar:**

- `alunos/index.php`
- `alunos/create.php`
- `professores/index.php`
- `professores/create.php`
- `professores/edit.php`
- `pais/index.php`
- `pais/create.php`

## 6. Adicionar Rotas Faltantes

**Adicionar em `index.php`:**

```php
// Admin Escola - Turmas
$router->get('/admin-escola/turmas/create', 'EscolaController@createTurma')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->post('/admin-escola/turmas', 'EscolaController@storeTurma')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola/turmas/{id}/edit', 'EscolaController@editTurma')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->put('/admin-escola/turmas/{id}', 'EscolaController@updateTurma')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->delete('/admin-escola/turmas/{id}', 'EscolaController@deleteTurma')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');

// Admin Escola - Matérias
$router->get('/admin-escola/materias/create', 'EscolaController@createMateria')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->post('/admin-escola/materias', 'EscolaController@storeMateria')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->get('/admin-escola/materias/{id}/edit', 'EscolaController@editMateria')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->put('/admin-escola/materias/{id}', 'EscolaController@updateMateria')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->delete('/admin-escola/materias/{id}', 'EscolaController@deleteMateria')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');

// Admin Escola - Alunos, Professores, Pais (adicionar rotas POST para criar/editar)
$router->post('/admin-escola/alunos', 'EscolaController@storeAluno')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->post('/admin-escola/professores', 'EscolaController@storeProfessor')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
$router->post('/admin-escola/pais', 'EscolaController@storePai')->middleware('AuthMiddleware')->middleware('AdminEscolaMiddleware');
```

## 7. Criar Layout Consistente

**Criar `Views/layouts/admin-escola.php`** (se não existir):

- Header com logo e nome da escola
- Menu lateral com navegação (Dashboard, Alunos, Professores, Turmas, Matérias, Pais)
- Área de conteúdo
- Footer
- Mesmo padrão visual do `admin-global.php`

## 8. Adicionar Funcionalidades de Sessão

**Atualizar todas as views para exibir:**

- Mensagens de sucesso: `$_SESSION['success']`
- Mensagens de erro: `$_SESSION['error']`
- Limpar mensagens após exibir

## Arquivos a Modificar/Criar

**Modificar:**

- `app/Middleware/AuthMiddleware.php` - corrigir redirect
- `app/Middleware/AdminEscolaMiddleware.php` - corrigir redirect
- `app/Middleware/SuperAdminMiddleware.php` - corrigir redirect
- `app/Middleware/ProfessorMiddleware.php` - corrigir redirect
- `app/Middleware/AlunoMiddleware.php` - corrigir redirect
- `app/Middleware/PaisMiddleware.php` - corrigir redirect
- `app/Controllers/EscolaController.php` - adicionar CRUD turmas/matérias
- `Views/admin_escola/index.php` - dashboard funcional
- `index.php` - adicionar rotas faltantes
- Todas as views em `Views/escola/` - atualizar padrão visual

**Criar:**

- `Views/escola/turmas/index.php`
- `Views/escola/turmas/create.php`
- `Views/escola/turmas/edit.php`
- `Views/escola/materias/index.php`
- `Views/escola/materias/create.php`
- `Views/escola/materias/edit.php`
- `Views/layouts/admin-escola.php` (se não existir)

### To-dos

- [ ] Corrigir redirects em todos os middlewares para usar basePath corretamente
- [ ] Atualizar dashboard do admin escola com estatísticas reais
- [ ] Implementar CRUD completo de turmas (controller + views)
- [ ] Implementar CRUD completo de matérias (controller + views)
- [ ] Atualizar views existentes com padrão visual consistente e função url()
- [ ] Adicionar rotas faltantes para turmas, matérias e outras ações
- [ ] Criar layout consistente para admin de escola
- [ ] Implementar exibição de mensagens de sucesso/erro em todas as views