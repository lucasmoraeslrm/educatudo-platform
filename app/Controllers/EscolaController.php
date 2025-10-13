<?php

namespace Educatudo\Controllers;

use Educatudo\Core\{App, Database, Controller};

class EscolaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function redirectToEscola()
    {
        $basePath = $this->app->getBasePath();
        return $this->redirect($basePath . '/escola');
    }

    public function index()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        
        
        $escolaId = $user['escola_id'];
        
        // Verificar se o usuário tem escola_id
        if (!$escolaId) {
            $_SESSION['error'] = 'Usuário não está associado a nenhuma escola.';
            $basePath = $this->app->getBasePath();
            return $this->redirect($basePath . '/login');
        }
        
        // Buscar estatísticas gerais
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        
        $estatisticas = [
            'alunos' => $alunoModel->getEstatisticas($escolaId),
            'professores' => $professorModel->getEstatisticas($escolaId),
            'pais' => $paiModel->getEstatisticas($escolaId),
            'turmas' => $turmaModel->getEstatisticas($escolaId)
        ];
        
        return $this->view('escola.index', [
            'title' => 'Dashboard - Admin Escola',
            'estatisticas' => $estatisticas
        ]);
    }

    // ===== PROFESSORES =====
    
    public function professores()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $professores = $professorModel->getByEscola($escolaId);
        
        return $this->view('escola.professores.index', [
            'title' => 'Professores - Admin Escola',
            'professores' => $professores
        ]);
    }
    
    public function createProfessor()
    {
        $this->requireAuth();
        
        return $this->view('escola.professores.create', [
            'title' => 'Novo Professor - Admin Escola'
        ]);
    }
    
    public function storeProfessor()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validateProfessor($data, $escolaId);
        
        if (!empty($errors)) {
            return $this->view('escola.professores.create', [
                'title' => 'Novo Professor - Admin Escola',
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Criar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'escola_id' => $escolaId,
                'tipo' => 'professor',
                'nome' => $data['nome'],
                'email' => $data['email'],
                'senha_hash' => password_hash($data['senha'], PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            // Criar professor
            $professorModel = new \Educatudo\Models\Professor($this->db);
            $professorData = [
                'usuario_id' => $usuarioId,
                'codigo_prof' => $data['codigo_prof'],
                'materias' => $data['materias'],
                'ativo' => 1
            ];
            
            $professorModel->create($professorData);
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Professor criado com sucesso!';
            return $this->redirect('/escola/professores');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao criar professor: ' . $e->getMessage();
            return $this->redirect('/escola/professores/create');
        }
    }
    
    public function editProfessor($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $professor = $professorModel->findByUsuarioId($id);
        
        if (!$professor) {
            $_SESSION['error'] = 'Professor não encontrado';
            return $this->redirect('/escola/professores');
        }
        
        // Buscar dados do usuário
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($id);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Professor não encontrado';
            return $this->redirect('/escola/professores');
        }
        
        return $this->view('escola.professores.edit', [
            'title' => 'Editar Professor - Admin Escola',
            'professor' => $professor,
            'usuario' => $usuario
        ]); 
    }
    
    public function updateProfessor($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validateProfessor($data, $escolaId, $id);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect('/escola/professores/' . $id . '/edit');
        }
        
        try {
            $this->db->beginTransaction();
            
            // Atualizar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => $data['email']
            ];
            
            if (!empty($data['senha'])) {
                $usuarioData['senha_hash'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            
            $usuarioModel->update($id, $usuarioData);
            
            // Atualizar professor
            $professorModel = new \Educatudo\Models\Professor($this->db);
            $professorData = [
                'codigo_prof' => $data['codigo_prof'],
                'materias' => $data['materias']
            ];
            
            $professor = $professorModel->findByUsuarioId($id);
            $professorModel->update($professor['id'], $professorData);
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Professor atualizado com sucesso!';
            return $this->redirect('/escola/professores');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao atualizar professor: ' . $e->getMessage();
            return $this->redirect('/escola/professores/' . $id . '/edit');
        }
    }
    
    public function deleteProfessor($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        try {
            $this->db->beginTransaction();
            
            // Verificar se o professor pertence à escola
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($id);
            
            if (!$usuario || $usuario['escola_id'] != $escolaId) {
                throw new \Exception('Professor não encontrado');
            }
            
            // Desativar professor
            $professorModel = new \Educatudo\Models\Professor($this->db);
            $professor = $professorModel->findByUsuarioId($id);
            $professorModel->updateStatus($professor['id'], false);
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Professor desativado com sucesso!';
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao desativar professor: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/professores');
    }

    // ===== ALUNOS =====
    
    public function alunos()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $alunos = $alunoModel->getByEscola($escolaId);
        
        return $this->view('escola.alunos.index', [
            'title' => 'Alunos - Admin Escola',
            'alunos' => $alunos
        ]);
    }
    
    public function createAluno()
    {
        $this->requireAuth();
        
        // Buscar turmas e pais para os selects
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $paiModel = new \Educatudo\Models\Pai($this->db);
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $turmas = $turmaModel->getByEscola($escolaId);
        $pais = $paiModel->getByEscola($escolaId);
        
        return $this->view('escola.alunos.create', [
            'title' => 'Novo Aluno - Admin Escola',
            'turmas' => $turmas,
            'pais' => $pais
        ]);
    }
    
    public function storeAluno()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validateAluno($data, $escolaId);
        
        if (!empty($errors)) {
            return $this->view('escola.alunos.create', [
                'title' => 'Novo Aluno - Admin Escola',
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Criar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'escola_id' => $escolaId,
                'tipo' => 'aluno',
                'nome' => $data['nome'],
                'email' => null, // Alunos não têm email
                'senha_hash' => password_hash($data['senha'], PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            // Criar aluno
            $alunoModel = new \Educatudo\Models\Aluno($this->db);
            $alunoData = [
                'usuario_id' => $usuarioId,
                'ra' => $data['ra'],
                'turma_id' => $data['turma_id'] ?: null,
                'serie' => $data['serie'],
                'data_nasc' => $data['data_nasc'],
                'responsavel_id' => $data['responsavel_id'] ?: null,
                'ativo' => 1
            ];
            
            $alunoModel->create($alunoData);
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Aluno criado com sucesso!';
            return $this->redirect('/escola/alunos');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao criar aluno: ' . $e->getMessage();
            return $this->redirect('/escola/alunos/create');
        }
    }

    // ===== PAIS =====
    
    public function pais()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $pais = $paiModel->getByEscola($escolaId);
        
        return $this->view('escola.pais.index', [
            'title' => 'Pais - Admin Escola',
            'pais' => $pais
        ]);
    }
    
    public function createPai()
    {
        $this->requireAuth();
        
        return $this->view('escola.pais.create', [
            'title' => 'Novo Pai - Admin Escola'
        ]);
    }
    
    public function storePai()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validatePai($data, $escolaId);
        
        if (!empty($errors)) {
            return $this->view('escola.pais.create', [
                'title' => 'Novo Pai - Admin Escola',
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Criar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'escola_id' => $escolaId,
                'tipo' => 'pai',
                'nome' => $data['nome'],
                'email' => $data['email'],
                'senha_hash' => password_hash($data['senha'], PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            // Criar pai
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $paiData = [
                'usuario_id' => $usuarioId,
                'cpf' => $data['cpf'],
                'telefone' => $data['telefone'],
                'ativo' => 1
            ];
            
            $paiModel->create($paiData);
            
            $this->db->commit();
            
            $_SESSION['success'] = 'Pai criado com sucesso!';
            return $this->redirect('/escola/pais');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao criar pai: ' . $e->getMessage();
            return $this->redirect('/escola/pais/create');
        }
    }

    // ===== VALIDAÇÕES =====
    
    private function validateProfessor(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        
        if (empty($data['codigo_prof'])) {
            $errors['codigo_prof'] = 'Código do professor é obrigatório';
        }
        
        if (empty($data['senha']) && !$excludeId) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        // Verificar se código já existe
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $existing = $professorModel->findByCodigo($data['codigo_prof'], $escolaId);
        if ($existing && (!$excludeId || $existing['usuario_id'] != $excludeId)) {
            $errors['codigo_prof'] = 'Código já está em uso';
        }
        
        return $errors;
    }
    
    private function validateAluno(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (empty($data['ra'])) {
            $errors['ra'] = 'RA é obrigatório';
        }
        
        if (empty($data['senha']) && !$excludeId) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        // Verificar se RA já existe
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $existing = $alunoModel->findByRa($data['ra'], $escolaId);
        if ($existing && (!$excludeId || $existing['usuario_id'] != $excludeId)) {
            $errors['ra'] = 'RA já está em uso';
        }
        
        return $errors;
    }
    
    private function validatePai(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        
        if (empty($data['cpf'])) {
            $errors['cpf'] = 'CPF é obrigatório';
        }
        
        if (empty($data['senha']) && !$excludeId) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        return $errors;
    }

    // ===== CONFIGURAÇÕES =====
    
    public function configuracoes()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        // Buscar matérias e turmas da escola
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        
        $materias = $materiaModel->getByEscola($escolaId);
        $turmas = $turmaModel->getByEscola($escolaId);
        
        return $this->view('escola.configuracoes.index', [
            'title' => 'Configurações - Escola',
            'materias' => $materias,
            'turmas' => $turmas
        ]);
    }
    
    public function storeMateria()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validateMateria($data, $escolaId);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            // Determinar para onde redirecionar baseado na origem
            $redirectTo = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/escola/materias') !== false 
                ? '/escola/materias/create' 
                : '/escola/configuracoes';
            return $this->redirect($redirectTo);
        }
        
        try {
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $materiaData = [
                'escola_id' => $escolaId,
                'nome' => $data['nome'],
                'descricao' => $data['descricao'] ?? null
            ];
            
            $materiaModel->create($materiaData);
            
            $_SESSION['success'] = 'Matéria criada com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao criar matéria: ' . $e->getMessage();
        }
        
        // Determinar para onde redirecionar baseado na origem
        $redirectTo = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/escola/materias') !== false 
            ? '/escola/materias' 
            : '/escola/configuracoes';
        return $this->redirect($redirectTo);
    }
    
    public function deleteMateria($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        try {
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $materia = $materiaModel->find($id);
            
            if (!$materia || $materia['escola_id'] != $escolaId) {
                throw new \Exception('Matéria não encontrada');
            }
            
            $materiaModel->delete($id);
            
            $_SESSION['success'] = 'Matéria excluída com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao excluir matéria: ' . $e->getMessage();
        }
        
        // Determinar para onde redirecionar baseado na origem
        $redirectTo = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/escola/materias') !== false 
            ? '/escola/materias' 
            : '/escola/configuracoes';
        return $this->redirect($redirectTo);
    }
    
    public function storeSerie()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome da turma é obrigatório';
        }
        
        if (empty($data['serie'])) {
            $errors['serie'] = 'Série é obrigatória';
        }
        
        if (empty($data['ano_letivo'])) {
            $errors['ano_letivo'] = 'Ano letivo é obrigatório';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            return $this->redirect('/escola/configuracoes');
        }
        
        try {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turmaData = [
                'escola_id' => $escolaId,
                'nome' => $data['nome'],
                'serie' => $data['serie'],
                'ano_letivo' => $data['ano_letivo'],
                'ativo' => 1
            ];
            
            $turmaModel->create($turmaData);
            
            $_SESSION['success'] = 'Série/Turma criada com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao criar série/turma: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/configuracoes');
    }
    
    public function deleteSerie($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        try {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turma = $turmaModel->find($id);
            
            if (!$turma || $turma['escola_id'] != $escolaId) {
                throw new \Exception('Série/Turma não encontrada');
            }
            
            $turmaModel->delete($id);
            
            $_SESSION['success'] = 'Série/Turma excluída com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao excluir série/turma: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/configuracoes');
    }

    // ===== GESTÃO DE USUÁRIOS =====
    
    public function usuarios()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuarios = $usuarioModel->getByEscola($escolaId);
        
        return $this->view('escola.usuarios.index', [
            'title' => 'Usuários - Admin Escola',
            'usuarios' => $usuarios
        ]);
    }
    
    public function createUsuario()
    {
        $this->requireAuth();
        
        return $this->view('escola.usuarios.create', [
            'title' => 'Novo Usuário - Admin Escola'
        ]);
    }
    
    public function storeUsuario()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validateUsuario($data, $escolaId);
        
        if (!empty($errors)) {
            return $this->view('escola.usuarios.create', [
                'title' => 'Novo Usuário - Admin Escola',
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'escola_id' => $escolaId,
                'tipo' => $data['tipo'],
                'nome' => $data['nome'],
                'email' => $data['email'],
                'senha_hash' => password_hash($data['senha'], PASSWORD_DEFAULT)
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            // Criar registro específico baseado no tipo
            if ($data['tipo'] === 'professor') {
                $professorModel = new \Educatudo\Models\Professor($this->db);
                $professorData = [
                    'usuario_id' => $usuarioId,
                    'disciplina' => $data['disciplina'] ?? null,
                    'ativo' => 1
                ];
                $professorModel->create($professorData);
            } elseif ($data['tipo'] === 'aluno') {
                $alunoModel = new \Educatudo\Models\Aluno($this->db);
                $alunoData = [
                    'usuario_id' => $usuarioId,
                    'ra' => $data['ra'] ?? null,
                    'turma_id' => $data['turma_id'] ?? null,
                    'serie' => $data['serie'] ?? null,
                    'ativo' => 1
                ];
                $alunoModel->create($alunoData);
            } elseif ($data['tipo'] === 'pai') {
                $paiModel = new \Educatudo\Models\Pai($this->db);
                $paiData = [
                    'usuario_id' => $usuarioId,
                    'telefone' => $data['telefone'] ?? null,
                    'ativo' => 1
                ];
                $paiModel->create($paiData);
            }
            
            $this->db->commit();
            $_SESSION['success'] = 'Usuário criado com sucesso!';
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Erro ao criar usuário: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/usuarios');
    }
    
    public function editUsuario($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($id);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Usuário não encontrado.';
            return $this->redirect('/escola/usuarios');
        }
        
        return $this->view('escola.usuarios.edit', [
            'title' => 'Editar Usuário - Admin Escola',
            'usuario' => $usuario
        ]);
    }
    
    public function updateUsuario($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($id);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Usuário não encontrado.';
            return $this->redirect('/escola/usuarios');
        }
        
        $data = $_POST;
        $errors = $this->validateUsuario($data, $escolaId, $id);
        
        if (!empty($errors)) {
            return $this->view('escola.usuarios.edit', [
                'title' => 'Editar Usuário - Admin Escola',
                'usuario' => $usuario,
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $updateData = [
                'nome' => $data['nome'],
                'email' => $data['email']
            ];
            
            if (!empty($data['senha'])) {
                $updateData['senha_hash'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            
            $usuarioModel->update($id, $updateData);
            
            $_SESSION['success'] = 'Usuário atualizado com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar usuário: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/usuarios');
    }
    
    public function deleteUsuario($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($id);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Usuário não encontrado.';
            return $this->redirect('/escola/usuarios');
        }
        
        // Não permitir excluir o próprio usuário
        if ($usuario['id'] == $user['id']) {
            $_SESSION['error'] = 'Você não pode excluir seu próprio usuário.';
            return $this->redirect('/escola/usuarios');
        }
        
        try {
            $usuarioModel->delete($id);
            $_SESSION['success'] = 'Usuário excluído com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao excluir usuário: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/usuarios');
    }

    // ===== GESTÃO DE MATÉRIAS =====
    
    public function materias()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materias = $materiaModel->getByEscola($escolaId);
        
        return $this->view('escola.materias.index', [
            'title' => 'Matérias - Admin Escola',
            'materias' => $materias
        ]);
    }
    
    public function createMateria()
    {
        $this->requireAuth();
        
        return $this->view('escola.materias.create', [
            'title' => 'Nova Matéria - Admin Escola'
        ]);
    }
    
    public function editMateria($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materia = $materiaModel->find($id);
        
        if (!$materia || $materia['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Matéria não encontrada.';
            return $this->redirect('/escola/materias');
        }
        
        return $this->view('escola.materias.edit', [
            'title' => 'Editar Matéria - Admin Escola',
            'materia' => $materia
        ]);
    }
    
    public function updateMateria($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materia = $materiaModel->find($id);
        
        if (!$materia || $materia['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Matéria não encontrada.';
            return $this->redirect('/escola/materias');
        }
        
        $data = $_POST;
        $errors = $this->validateMateria($data, $escolaId, $id);
        
        if (!empty($errors)) {
            return $this->view('escola.materias.edit', [
                'title' => 'Editar Matéria - Admin Escola',
                'materia' => $materia,
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $materiaModel->update($id, [
                'nome' => $data['nome'],
                'descricao' => $data['descricao'] ?? null
            ]);
            
            $_SESSION['success'] = 'Matéria atualizada com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar matéria: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/materias');
    }
    

    // ===== GESTÃO DE TURMAS =====
    
    public function turmas()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turmas = $turmaModel->getByEscola($escolaId);
        
        return $this->view('escola.turmas.index', [
            'title' => 'Turmas - Admin Escola',
            'turmas' => $turmas
        ]);
    }
    
    public function createTurma()
    {
        $this->requireAuth();
        
        return $this->view('escola.turmas.create', [
            'title' => 'Nova Turma - Admin Escola'
        ]);
    }
    
    public function storeTurma()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $data = $_POST;
        $errors = $this->validateTurma($data, $escolaId);
        
        if (!empty($errors)) {
            return $this->view('escola.turmas.create', [
                'title' => 'Nova Turma - Admin Escola',
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turmaData = [
                'escola_id' => $escolaId,
                'nome' => $data['nome'],
                'serie' => $data['serie'],
                'turno' => $data['turno'] ?? null,
                'capacidade_maxima' => $data['capacidade_maxima'] ?? null,
                'ativo' => 1
            ];
            
            $turmaModel->create($turmaData);
            
            $_SESSION['success'] = 'Turma criada com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao criar turma: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/turmas');
    }
    
    public function editTurma($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turma = $turmaModel->find($id);
        
        if (!$turma || $turma['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Turma não encontrada.';
            return $this->redirect('/escola/turmas');
        }
        
        return $this->view('escola.turmas.edit', [
            'title' => 'Editar Turma - Admin Escola',
            'turma' => $turma
        ]);
    }
    
    public function updateTurma($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turma = $turmaModel->find($id);
        
        if (!$turma || $turma['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Turma não encontrada.';
            return $this->redirect('/escola/turmas');
        }
        
        $data = $_POST;
        $errors = $this->validateTurma($data, $escolaId, $id);
        
        if (!empty($errors)) {
            return $this->view('escola.turmas.edit', [
                'title' => 'Editar Turma - Admin Escola',
                'turma' => $turma,
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        try {
            $turmaModel->update($id, [
                'nome' => $data['nome'],
                'serie' => $data['serie'],
                'turno' => $data['turno'] ?? null,
                'capacidade_maxima' => $data['capacidade_maxima'] ?? null
            ]);
            
            $_SESSION['success'] = 'Turma atualizada com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao atualizar turma: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/turmas');
    }
    
    public function deleteTurma($id)
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turma = $turmaModel->find($id);
        
        if (!$turma || $turma['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Turma não encontrada.';
            return $this->redirect('/escola/turmas');
        }
        
        try {
            $turmaModel->delete($id);
            $_SESSION['success'] = 'Turma excluída com sucesso!';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Erro ao excluir turma: ' . $e->getMessage();
        }
        
        return $this->redirect('/escola/turmas');
    }

    // ===== RELATÓRIOS =====
    
    public function relatorios()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        // Buscar estatísticas gerais
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        
        $estatisticas = [
            'alunos' => $alunoModel->getEstatisticas($escolaId),
            'professores' => $professorModel->getEstatisticas($escolaId),
            'pais' => $paiModel->getEstatisticas($escolaId),
            'turmas' => $turmaModel->getEstatisticas($escolaId)
        ];
        
        return $this->view('escola.relatorios.index', [
            'title' => 'Relatórios - Admin Escola',
            'estatisticas' => $estatisticas
        ]);
    }

    // ===== MÉTODOS AUXILIARES =====
    
    private function validateUsuario(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório.';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido.';
        } else {
            // Verificar se email já existe
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $existing = $usuarioModel->findByEmail($data['email']);
            if ($existing && (!$excludeId || $existing['id'] != $excludeId)) {
                $errors['email'] = 'Email já está em uso.';
            }
        }
        
        if (empty($data['senha']) && !$excludeId) {
            $errors['senha'] = 'Senha é obrigatória.';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres.';
        }
        
        if (empty($data['tipo'])) {
            $errors['tipo'] = 'Tipo é obrigatório.';
        } elseif (!in_array($data['tipo'], ['professor', 'aluno', 'pai'])) {
            $errors['tipo'] = 'Tipo inválido.';
        }
        
        return $errors;
    }
    
    private function validateMateria(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório.';
        } else {
            // Verificar se nome já existe na escola
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $existing = $materiaModel->findByNameAndEscola($data['nome'], $escolaId);
            if ($existing && (!$excludeId || $existing['id'] != $excludeId)) {
                $errors['nome'] = 'Matéria com este nome já existe.';
            }
        }
        
        return $errors;
    }
    
    private function validateTurma(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório.';
        }
        
        if (empty($data['serie'])) {
            $errors['serie'] = 'Série é obrigatória.';
        }
        
        return $errors;
    }
}