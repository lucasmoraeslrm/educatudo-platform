<?php

namespace Educatudo\Controllers;

use Educatudo\Core\{App, Database, Controller};

class EscolaController extends Controller
{
    private Database $db;
    private App $app;

    public function __construct()
    {
        $this->app = App::getInstance();
        $this->db = Database::getInstance($this->app);
    }

    public function index()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        $escolaId = $user['escola_id'];
        
        // Buscar estatísticas gerais
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $paiModel = new \Educatudo\Models\Pai($this->db);
        
        $estatisticas = [
            'alunos' => $alunoModel->getEstatisticas($escolaId),
            'professores' => $professorModel->getEstatisticas($escolaId),
            'pais' => $paiModel->getEstatisticas($escolaId)
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
            return $this->redirect('/admin-escola/professores');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao criar professor: ' . $e->getMessage();
            return $this->redirect('/admin-escola/professores/create');
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
            return $this->redirect('/admin-escola/professores');
        }
        
        // Buscar dados do usuário
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($id);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            $_SESSION['error'] = 'Professor não encontrado';
            return $this->redirect('/admin-escola/professores');
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
            return $this->redirect('/admin-escola/professores/' . $id . '/edit');
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
            return $this->redirect('/admin-escola/professores');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao atualizar professor: ' . $e->getMessage();
            return $this->redirect('/admin-escola/professores/' . $id . '/edit');
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
        
        return $this->redirect('/admin-escola/professores');
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
            return $this->redirect('/admin-escola/alunos');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao criar aluno: ' . $e->getMessage();
            return $this->redirect('/admin-escola/alunos/create');
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
            return $this->redirect('/admin-escola/pais');
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $_SESSION['error'] = 'Erro ao criar pai: ' . $e->getMessage();
            return $this->redirect('/admin-escola/pais/create');
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
}