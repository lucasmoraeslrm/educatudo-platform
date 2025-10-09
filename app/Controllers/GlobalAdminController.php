<?php

namespace Educatudo\Controllers;

use Educatudo\Core\Controller;

class GlobalAdminController extends Controller
{
    public function index()
    {
        $this->requireAuth();
        
        $user = $this->getUser();
        
        // Estatísticas gerais
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $estatisticas = $escolaModel->getEstatisticas();
        
        return $this->view('global_admin.index', [
            'title' => 'Admin Global - Educatudo',
            'user' => $user,
            'estatisticas' => $estatisticas
        ]);
    }

    public function escolas()
    {
        $this->requireAuth();
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escolas = $escolaModel->getAll();
        
        return $this->view('global_admin.escolas', [
            'title' => 'Gerenciar Escolas - Educatudo',
            'escolas' => $escolas
        ]);
    }

    public function showEscola(int $id)
    {
        // Verificação de autenticação manual
        if (!isset($_SESSION['user_id'])) {
            error_log("Usuário não logado - redirecionando para login");
            return $this->redirect('/login');
        }
        
        // Verificar se é super admin
        $sql = "SELECT tipo FROM usuarios WHERE id = :id";
        $user = $this->db->fetch($sql, ['id' => $_SESSION['user_id']]);
        
        if (!$user || $user['tipo'] !== 'super_admin') {
            error_log("Usuário não é super admin - tipo: " . ($user['tipo'] ?? 'null'));
            return $this->redirect('/unauthorized');
        }
        
        // Debug
        error_log("showEscola chamado com ID: " . $id);
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($id);
        
        if (!$escola) {
            error_log("Escola não encontrada com ID: " . $id);
            return $this->redirect('/admin/escolas');
        }
        
        error_log("Escola encontrada: " . $escola['nome']);
        
        // Buscar estatísticas da escola
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        
        $estatisticas = [
            'usuarios' => $usuarioModel->getEstatisticas($id),
            'alunos' => $alunoModel->getEstatisticas($id),
            'professores' => $professorModel->getEstatisticas($id),
            'pais' => $paiModel->getEstatisticas($id)
        ];
        
        $usuarios = $usuarioModel->getByEscola($id);
        $alunos = $alunoModel->getByEscola($id);
        $professores = $professorModel->getByEscola($id);
        $pais = $paiModel->getByEscola($id);
        $turmas = $turmaModel->getByEscola($id);
        
        // Buscar matérias com tratamento de erro
        try {
            $materias = $materiaModel->getByEscola($id);
            // Garantir que seja um array
            if (!is_array($materias)) {
                error_log("Materias retornou tipo inválido: " . gettype($materias));
                $materias = [];
            }
        } catch (\Exception $e) {
            error_log("Erro ao buscar matérias: " . $e->getMessage());
            $materias = [];
        }
        
        error_log("Retornando view escola-details");
        
        return $this->view('global_admin.escola-details', [
            'title' => 'Detalhes da Escola - Educatudo',
            'escola' => $escola,
            'estatisticas' => $estatisticas,
            'usuarios' => $usuarios,
            'alunos' => $alunos,
            'professores' => $professores,
            'pais' => $pais,
            'turmas' => $turmas,
            'materias' => $materias,
            'db' => $this->db
        ]);
    }

    public function createEscola()
    {
        $this->requireAuth();
        
        return $this->view('global_admin.escola-create', [
            'title' => 'Nova Escola - Educatudo'
        ]);
    }

    public function storeEscola()
    {
        $this->requireAuth();
        
        $data = $this->request->input();
        $errors = $this->validateEscola($data);
        
        if (!empty($errors)) {
            return $this->view('global_admin.escola-create', [
                'title' => 'Nova Escola - Educatudo',
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        
        // Preparar dados da escola
        $escolaData = [
            'nome' => $data['nome'],
            'subdominio' => $data['subdominio'],
            'cnpj' => $data['cnpj'] ?? null,
            'email' => $data['email'] ?? null,
            'telefone' => $data['telefone'] ?? null,
            'endereco' => $data['endereco'] ?? null,
            'plano' => $data['plano'],
            'ativa' => isset($data['ativa']) ? 1 : 0,
            'cor_primaria' => $data['cor_primaria'] ?? '#007bff',
            'cor_secundaria' => $data['cor_secundaria'] ?? '#6c757d',
            'configuracoes' => $data['configuracoes'] ?? null,
            'observacoes' => $data['observacoes'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Upload de arquivos
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $escolaData['logo'] = $this->uploadFile($_FILES['logo'], 'logos');
        }
        
        if (isset($_FILES['background']) && $_FILES['background']['error'] === UPLOAD_ERR_OK) {
            $escolaData['background'] = $this->uploadFile($_FILES['background'], 'backgrounds');
        }
        
        $escolaId = $escolaModel->create($escolaData);
        
        if ($escolaId) {
            // Criar admin da escola
            if (!empty($data['admin_nome']) && !empty($data['admin_email']) && !empty($data['admin_senha'])) {
                $this->createEscolaAdmin($escolaId, $data);
            }
            
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        return $this->view('global_admin.escola-create', [
            'title' => 'Nova Escola - Educatudo',
            'errors' => ['Erro ao criar escola. Tente novamente.'],
            'old' => $data
        ]);
    }

    public function editEscola(int $id)
    {
        $this->requireAuth();
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($id);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        return $this->view('global_admin.escola-edit', [
            'title' => 'Editar Escola - Educatudo',
            'escola' => $escola
        ]);
    }

    public function updateEscola(int $id)
    {
        $this->requireAuth();
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($id);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $data = $this->request->input();
        $errors = $this->validateEscola($data, $id);
        
        if (!empty($errors)) {
            return $this->view('global_admin.escola-edit', [
                'title' => 'Editar Escola - Educatudo',
                'escola' => $escola,
                'errors' => $errors,
                'old' => $data
            ]);
        }
        
        // Preparar dados da escola
        $escolaData = [
            'nome' => $data['nome'],
            'subdominio' => $data['subdominio'],
            'cnpj' => $data['cnpj'] ?? null,
            'email' => $data['email'] ?? null,
            'telefone' => $data['telefone'] ?? null,
            'endereco' => $data['endereco'] ?? null,
            'plano' => $data['plano'],
            'ativa' => isset($data['ativa']) ? 1 : 0,
            'cor_primaria' => $data['cor_primaria'] ?? '#007bff',
            'cor_secundaria' => $data['cor_secundaria'] ?? '#6c757d',
            'configuracoes' => $data['configuracoes'] ?? null,
            'observacoes' => $data['observacoes'] ?? null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Upload de arquivos (apenas se novos arquivos foram enviados)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $escolaData['logo'] = $this->uploadFile($_FILES['logo'], 'logos');
        }
        
        if (isset($_FILES['background']) && $_FILES['background']['error'] === UPLOAD_ERR_OK) {
            $escolaData['background'] = $this->uploadFile($_FILES['background'], 'backgrounds');
        }
        
        if ($escolaModel->update($id, $escolaData)) {
            return $this->redirect('/admin/escolas/' . $id);
        }
        
        return $this->view('global_admin.escola-edit', [
            'title' => 'Editar Escola - Educatudo',
            'escola' => $escola,
            'errors' => ['Erro ao atualizar escola. Tente novamente.'],
            'old' => $data
        ]);
    }

    public function toggleStatusEscola(int $id)
    {
        $this->requireAuth();
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($id);
        
        if (!$escola) {
            return $this->json(['success' => false, 'message' => 'Escola não encontrada']);
        }
        
        $novaStatus = !$escola['ativa'];
        
        if ($escolaModel->updateStatus($id, $novaStatus)) {
            return $this->json(['success' => true, 'message' => 'Status atualizado com sucesso']);
        }
        
        return $this->json(['success' => false, 'message' => 'Erro ao atualizar status']);
    }

    public function deleteEscola(int $id)
    {
        $this->requireAuth();
        
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($id);
        
        if (!$escola) {
            return $this->json(['success' => false, 'message' => 'Escola não encontrada']);
        }
        
        // Verificar se há usuários vinculados
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuarios = $usuarioModel->getByEscola($id);
        
        if (!empty($usuarios)) {
            return $this->json(['success' => false, 'message' => 'Não é possível excluir escola com usuários vinculados']);
        }
        
        if ($escolaModel->delete($id)) {
            return $this->json(['success' => true, 'message' => 'Escola excluída com sucesso']);
        }
        
        return $this->json(['success' => false, 'message' => 'Erro ao excluir escola']);
    }

    private function validateEscola(array $data, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome da escola é obrigatório';
        }
        
        if (empty($data['subdominio'])) {
            $errors['subdominio'] = 'Subdomínio é obrigatório';
        } else {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $existing = $escolaModel->findBySubdomain($data['subdominio']);
            if ($existing && (!$excludeId || $existing['id'] != $excludeId)) {
                $errors['subdominio'] = 'Este subdomínio já está em uso';
            }
        }
        
        if (empty($data['plano'])) {
            $errors['plano'] = 'Plano é obrigatório';
        } elseif (!in_array($data['plano'], ['basico', 'avancado', 'premium'])) {
            $errors['plano'] = 'Plano inválido';
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        
        if (!empty($data['cnpj']) && !$this->validateCNPJ($data['cnpj'])) {
            $errors['cnpj'] = 'CNPJ inválido';
        }
        
        if (!empty($data['configuracoes'])) {
            json_decode($data['configuracoes']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors['configuracoes'] = 'Configurações devem ser um JSON válido';
            }
        }
        
        return $errors;
    }

    private function validateCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        // Validação básica de CNPJ
        $sum = 0;
        $weight = 5;
        
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($cnpj[$i]) * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }
        
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        if (intval($cnpj[12]) != $digit1) {
            return false;
        }
        
        $sum = 0;
        $weight = 6;
        
        for ($i = 0; $i < 13; $i++) {
            $sum += intval($cnpj[$i]) * $weight;
            $weight = ($weight == 2) ? 9 : $weight - 1;
        }
        
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;
        
        return intval($cnpj[13]) == $digit2;
    }

    private function uploadFile(array $file, string $folder): string
    {
        $uploadDir = __DIR__ . '/../../public/uploads/' . $folder . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/' . $folder . '/' . $filename;
        }
        
        return '';
    }

    private function createEscolaAdmin(int $escolaId, array $data): void
    {
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        
        $usuarioData = [
            'escola_id' => $escolaId,
            'tipo' => 'admin_escola',
            'nome' => $data['admin_nome'],
            'email' => $data['admin_email'],
            'senha_hash' => password_hash($data['admin_senha'], PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $usuarioModel->create($usuarioData);
    }
    
    public function usuarios()
    {
        $this->requireAuth();
        
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuarios = $usuarioModel->getSuperAdmins();
        
        return $this->view('global_admin.usuarios', [
            'title' => 'Usuários - Admin Global',
            'usuarios' => $usuarios
        ]);
    }
    
    public function exercicios()
    {
        $this->requireAuth();
        
        return $this->view('global_admin.exercicios', [
            'title' => 'Exercícios - Admin Global'
        ]);
    }
    
    public function servidor()
    {
        $this->requireAuth();
        
        $serverInfo = [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'database_status' => $this->checkDatabaseStatus()
        ];
        
        return $this->view('global_admin.servidor', [
            'title' => 'Servidor - Admin Global',
            'serverInfo' => $serverInfo
        ]);
    }
    
    private function checkDatabaseStatus(): array
    {
        try {
            $result = $this->db->fetch("SELECT 1 as status");
            return [
                'status' => 'online',
                'message' => 'Conexão ativa'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'message' => $e->getMessage()
            ];
        }
    }

    // ==================== CRUD de Professores ====================
    
    public function createProfessor(int $escolaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        // Buscar matérias ativas da escola
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materias = $materiaModel->getAtivasByEscola($escolaId);
        
        return $this->view('global_admin.professor-create', [
            'title' => 'Novo Professor - Educatudo',
            'escola' => $escola,
            'materias' => $materias
        ]);
    }
    
    public function storeProfessor(int $escolaId)
    {
        $data = $this->request->input();
        $errors = $this->validateProfessor($data, $escolaId);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $materias = $materiaModel->getAtivasByEscola($escolaId);
            
            return $this->view('global_admin.professor-create', [
                'title' => 'Novo Professor - Educatudo',
                'escola' => $escola,
                'materias' => $materias,
                'errors' => $errors
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Criar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => $data['email'],
                'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
                'tipo' => 'professor',
                'escola_id' => $escolaId
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            if (!$usuarioId) {
                throw new \Exception('Erro ao criar usuário');
            }
            
            // Criar professor
            $professorModel = new \Educatudo\Models\Professor($this->db);
            
            // Processar matérias selecionadas
            $materiasSelecionadas = [];
            if (!empty($data['materias']) && is_array($data['materias'])) {
                $materiaModel = new \Educatudo\Models\Materia($this->db);
                foreach ($data['materias'] as $materiaId) {
                    $materia = $materiaModel->find($materiaId);
                    if ($materia) {
                        $materiasSelecionadas[] = $materia['nome'];
                    }
                }
            }
            
            $professorData = [
                'usuario_id' => $usuarioId,
                'codigo_prof' => $data['codigo_prof'],
                'materias' => !empty($materiasSelecionadas) ? json_encode($materiasSelecionadas) : null,
                'ativo' => 1
            ];
            
            $professorId = $professorModel->create($professorData);
            
            if (!$professorId) {
                throw new \Exception('Erro ao criar professor');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $materias = $materiaModel->getAtivasByEscola($escolaId);
            
            return $this->view('global_admin.professor-create', [
                'title' => 'Novo Professor - Educatudo',
                'escola' => $escola,
                'materias' => $materias,
                'errors' => ['geral' => 'Erro ao criar professor: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function editProfessor(int $escolaId, int $professorId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $professor = $professorModel->find($professorId);
        
        if (!$professor) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        // Buscar dados do usuário
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($professor['usuario_id']);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        // Buscar matérias ativas da escola
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materias = $materiaModel->getAtivasByEscola($escolaId);
        
        return $this->view('global_admin.professor-edit', [
            'title' => 'Editar Professor - Educatudo',
            'escola' => $escola,
            'professor' => $professor,
            'usuario' => $usuario,
            'materias' => $materias
        ]);
    }
    
    public function updateProfessor(int $escolaId, int $professorId)
    {
        $data = $this->request->input();
        
        $professorModel = new \Educatudo\Models\Professor($this->db);
        $professor = $professorModel->find($professorId);
        
        if (!$professor) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $errors = $this->validateProfessor($data, $escolaId, $professor['usuario_id']);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($professor['usuario_id']);
            
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $materias = $materiaModel->getAtivasByEscola($escolaId);
            
            return $this->view('global_admin.professor-edit', [
                'title' => 'Editar Professor - Educatudo',
                'escola' => $escola,
                'professor' => $professor,
                'usuario' => $usuario,
                'materias' => $materias,
                'errors' => $errors
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Atualizar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => $data['email']
            ];
            
            // Atualizar senha se fornecida
            if (!empty($data['senha'])) {
                $usuarioData['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            
            if (!$usuarioModel->update($professor['usuario_id'], $usuarioData)) {
                throw new \Exception('Erro ao atualizar usuário');
            }
            
            // Processar matérias selecionadas
            $materiasSelecionadas = [];
            if (!empty($data['materias']) && is_array($data['materias'])) {
                $materiaModel = new \Educatudo\Models\Materia($this->db);
                foreach ($data['materias'] as $materiaId) {
                    $materia = $materiaModel->find($materiaId);
                    if ($materia) {
                        $materiasSelecionadas[] = $materia['nome'];
                    }
                }
            }
            
            // Atualizar professor
            $professorData = [
                'codigo_prof' => $data['codigo_prof'],
                'materias' => !empty($materiasSelecionadas) ? json_encode($materiasSelecionadas) : null,
                'ativo' => isset($data['ativo']) ? 1 : 0
            ];
            
            if (!$professorModel->update($professorId, $professorData)) {
                throw new \Exception('Erro ao atualizar professor');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($professor['usuario_id']);
            
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $materias = $materiaModel->getAtivasByEscola($escolaId);
            
            return $this->view('global_admin.professor-edit', [
                'title' => 'Editar Professor - Educatudo',
                'escola' => $escola,
                'professor' => $professor,
                'usuario' => $usuario,
                'materias' => $materias,
                'errors' => ['geral' => 'Erro ao atualizar professor: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function deleteProfessor(int $escolaId, int $professorId)
    {
        try {
            $this->db->beginTransaction();
            
            $professorModel = new \Educatudo\Models\Professor($this->db);
            $professor = $professorModel->find($professorId);
            
            if (!$professor) {
                return $this->redirect('/admin/escolas/' . $escolaId);
            }
            
            // Deletar professor
            if (!$professorModel->delete($professorId)) {
                throw new \Exception('Erro ao excluir professor');
            }
            
            // Deletar usuário (cascade)
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            if (!$usuarioModel->delete($professor['usuario_id'])) {
                throw new \Exception('Erro ao excluir usuário');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
    }
    
    private function validateProfessor(array $data, int $escolaId, int $excludeUsuarioId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        } else {
            // Verificar se email já existe
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $sql = "SELECT id FROM usuarios WHERE email = :email AND escola_id = :escola_id";
            $params = ['email' => $data['email'], 'escola_id' => $escolaId];
            
            if ($excludeUsuarioId) {
                $sql .= " AND id != :exclude_id";
                $params['exclude_id'] = $excludeUsuarioId;
            }
            
            $existente = $this->db->fetch($sql, $params);
            if ($existente) {
                $errors['email'] = 'Email já cadastrado';
            }
        }
        
        if (empty($data['codigo_prof'])) {
            $errors['codigo_prof'] = 'Código do professor é obrigatório';
        } else {
            // Verificar se código já existe
            $professorModel = new \Educatudo\Models\Professor($this->db);
            $sql = "SELECT p.id FROM professores p 
                    INNER JOIN usuarios u ON p.usuario_id = u.id 
                    WHERE p.codigo_prof = :codigo AND u.escola_id = :escola_id";
            $params = ['codigo' => $data['codigo_prof'], 'escola_id' => $escolaId];
            
            if ($excludeUsuarioId) {
                $sql .= " AND p.usuario_id != :exclude_id";
                $params['exclude_id'] = $excludeUsuarioId;
            }
            
            $existente = $this->db->fetch($sql, $params);
            if ($existente) {
                $errors['codigo_prof'] = 'Código já cadastrado';
            }
        }
        
        if (empty($data['senha']) && !$excludeUsuarioId) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        return $errors;
    }

    // ==================== CRUD de Matérias ====================
    
    public function createMateria(int $escolaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        return $this->view('global_admin.materia-create', [
            'title' => 'Nova Matéria - Educatudo',
            'escola' => $escola
        ]);
    }
    
    public function storeMateria(int $escolaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $data = $this->request->input();
        $data['escola_id'] = $escolaId;
        
        $errors = $this->validateMateria($data, $escolaId);
        
        if (!empty($errors)) {
            return $this->view('global_admin.materia-create', [
                'title' => 'Nova Matéria - Educatudo',
                'escola' => $escola,
                'errors' => $errors
            ]);
        }
        
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        
        $materiaData = [
            'nome' => $data['nome'],
            'escola_id' => $escolaId
        ];
        
        $created = $materiaModel->create($materiaData);
        
        if ($created) {
            $_SESSION['success'] = 'Matéria criada com sucesso!';
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $errors[] = 'Erro ao criar matéria. Tente novamente.';
        return $this->view('global_admin.materia-create', [
            'title' => 'Nova Matéria - Educatudo',
            'escola' => $escola,
            'errors' => $errors
        ]);
    }
    
    public function editMateria(int $escolaId, int $materiaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materia = $materiaModel->find($materiaId);
        
        if (!$materia || $materia['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        return $this->view('global_admin.materia-edit', [
            'title' => 'Editar Matéria - Educatudo',
            'escola' => $escola,
            'materia' => $materia
        ]);
    }
    
    public function updateMateria(int $escolaId, int $materiaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materia = $materiaModel->find($materiaId);
        
        if (!$materia || $materia['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $data = $this->request->input();
        $data['escola_id'] = $escolaId;
        
        $errors = $this->validateMateria($data, $escolaId, $materiaId);
        
        if (!empty($errors)) {
            return $this->view('global_admin.materia-edit', [
                'title' => 'Editar Matéria - Educatudo',
                'escola' => $escola,
                'materia' => $materia,
                'errors' => $errors
            ]);
        }
        
        $materiaData = [
            'nome' => $data['nome']
        ];
        
        $updated = $materiaModel->update($materiaId, $materiaData);
        
        if ($updated) {
            $_SESSION['success'] = 'Matéria atualizada com sucesso!';
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $errors[] = 'Erro ao atualizar matéria. Tente novamente.';
        return $this->view('global_admin.materia-edit', [
            'title' => 'Editar Matéria - Educatudo',
            'escola' => $escola,
            'materia' => $materia,
            'errors' => $errors
        ]);
    }
    
    public function deleteMateria(int $escolaId, int $materiaId)
    {
        $materiaModel = new \Educatudo\Models\Materia($this->db);
        $materia = $materiaModel->find($materiaId);
        
        if (!$materia || $materia['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $deleted = $materiaModel->delete($materiaId);
        
        if ($deleted) {
            $_SESSION['success'] = 'Matéria excluída com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir matéria.';
        }
        
        return $this->redirect('/admin/escolas/' . $escolaId);
    }
    
    private function validateMateria(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome da matéria é obrigatório';
        } else {
            // Verificar se já existe matéria com mesmo nome na escola
            $materiaModel = new \Educatudo\Models\Materia($this->db);
            $existente = $materiaModel->findByNome($data['nome'], $escolaId);
            
            if ($existente && (!$excludeId || $existente['id'] != $excludeId)) {
                $errors['nome'] = 'Já existe uma matéria com este nome nesta escola';
            }
        }
        
        return $errors;
    }

    // ==================== CRUD de Turmas ====================
    
    public function createTurma(int $escolaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        return $this->view('global_admin.turma-create', [
            'title' => 'Nova Turma - Educatudo',
            'escola' => $escola
        ]);
    }
    
    public function storeTurma(int $escolaId)
    {
        $data = $this->request->input();
        $errors = $this->validateTurma($data, $escolaId);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            return $this->view('global_admin.turma-create', [
                'title' => 'Nova Turma - Educatudo',
                'escola' => $escola,
                'errors' => $errors
            ]);
        }
        
        try {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            
            $turmaData = [
                'escola_id' => $escolaId,
                'nome' => $data['nome'],
                'serie' => $data['serie'],
                'ano_letivo' => $data['ano_letivo'],
                'periodo' => $data['periodo'] ?? null,
                'ativo' => 1
            ];
            
            $turmaId = $turmaModel->create($turmaData);
            
            if (!$turmaId) {
                throw new \Exception('Erro ao criar turma');
            }
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            return $this->view('global_admin.turma-create', [
                'title' => 'Nova Turma - Educatudo',
                'escola' => $escola,
                'errors' => ['geral' => 'Erro ao criar turma: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function editTurma(int $escolaId, int $turmaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turma = $turmaModel->find($turmaId);
        
        if (!$turma || $turma['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        return $this->view('global_admin.turma-edit', [
            'title' => 'Editar Turma - Educatudo',
            'escola' => $escola,
            'turma' => $turma
        ]);
    }
    
    public function updateTurma(int $escolaId, int $turmaId)
    {
        $data = $this->request->input();
        $errors = $this->validateTurma($data, $escolaId, $turmaId);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turma = $turmaModel->find($turmaId);
            
            return $this->view('global_admin.turma-edit', [
                'title' => 'Editar Turma - Educatudo',
                'escola' => $escola,
                'turma' => $turma,
                'errors' => $errors
            ]);
        }
        
        try {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            
            $turmaData = [
                'nome' => $data['nome'],
                'serie' => $data['serie'],
                'ano_letivo' => $data['ano_letivo'],
                'periodo' => $data['periodo'] ?? null,
                'ativo' => isset($data['ativo']) ? 1 : 0
            ];
            
            if (!$turmaModel->update($turmaId, $turmaData)) {
                throw new \Exception('Erro ao atualizar turma');
            }
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turma = $turmaModel->find($turmaId);
            
            return $this->view('global_admin.turma-edit', [
                'title' => 'Editar Turma - Educatudo',
                'escola' => $escola,
                'turma' => $turma,
                'errors' => ['geral' => 'Erro ao atualizar turma: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function deleteTurma(int $escolaId, int $turmaId)
    {
        try {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turma = $turmaModel->find($turmaId);
            
            if (!$turma || $turma['escola_id'] != $escolaId) {
                return $this->redirect('/admin/escolas/' . $escolaId);
            }
            
            if (!$turmaModel->delete($turmaId)) {
                throw new \Exception('Erro ao excluir turma');
            }
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
    }
    
    private function validateTurma(array $data, int $escolaId, int $excludeId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome da turma é obrigatório';
        }
        
        if (empty($data['serie'])) {
            $errors['serie'] = 'Série é obrigatória';
        }
        
        if (empty($data['ano_letivo'])) {
            $errors['ano_letivo'] = 'Ano letivo é obrigatório';
        } elseif (!is_numeric($data['ano_letivo']) || $data['ano_letivo'] < 2000 || $data['ano_letivo'] > 2100) {
            $errors['ano_letivo'] = 'Ano letivo inválido';
        }
        
        // Verificar se já existe turma com mesmo nome, série e ano letivo na escola
        if (empty($errors)) {
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $sql = "SELECT id FROM turmas 
                    WHERE escola_id = :escola_id 
                    AND nome = :nome 
                    AND serie = :serie 
                    AND ano_letivo = :ano_letivo";
            $params = [
                'escola_id' => $escolaId,
                'nome' => $data['nome'],
                'serie' => $data['serie'],
                'ano_letivo' => $data['ano_letivo']
            ];
            
            if ($excludeId) {
                $sql .= " AND id != :exclude_id";
                $params['exclude_id'] = $excludeId;
            }
            
            $existente = $this->db->fetch($sql, $params);
            if ($existente) {
                $errors['nome'] = 'Já existe uma turma com este nome, série e ano letivo';
            }
        }
        
        return $errors;
    }

    // ==================== CRUD de Alunos ====================
    
    public function createAluno(int $escolaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        // Buscar turmas ativas da escola
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turmas = $turmaModel->getByEscola($escolaId);
        
        // Buscar pais da escola para vincular como responsável
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $pais = $paiModel->getByEscola($escolaId);
        
        return $this->view('global_admin.aluno-create', [
            'title' => 'Novo Aluno - Educatudo',
            'escola' => $escola,
            'turmas' => $turmas,
            'pais' => $pais
        ]);
    }
    
    public function storeAluno(int $escolaId)
    {
        $data = $this->request->input();
        $errors = $this->validateAluno($data, $escolaId);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turmas = $turmaModel->getByEscola($escolaId);
            
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $pais = $paiModel->getByEscola($escolaId);
            
            return $this->view('global_admin.aluno-create', [
                'title' => 'Novo Aluno - Educatudo',
                'escola' => $escola,
                'turmas' => $turmas,
                'pais' => $pais,
                'errors' => $errors
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Criar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => !empty($data['email']) ? $data['email'] : null,
                'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
                'tipo' => 'aluno',
                'escola_id' => $escolaId
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            if (!$usuarioId) {
                throw new \Exception('Erro ao criar usuário');
            }
            
            // Criar aluno
            $alunoModel = new \Educatudo\Models\Aluno($this->db);
            $alunoData = [
                'usuario_id' => $usuarioId,
                'ra' => $data['ra'],
                'turma_id' => !empty($data['turma_id']) ? $data['turma_id'] : null,
                'serie' => $data['serie'],
                'data_nasc' => !empty($data['data_nasc']) ? $data['data_nasc'] : null,
                'responsavel_id' => !empty($data['responsavel_id']) ? $data['responsavel_id'] : null,
                'ativo' => 1
            ];
            
            $alunoId = $alunoModel->create($alunoData);
            
            if (!$alunoId) {
                throw new \Exception('Erro ao criar aluno');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turmas = $turmaModel->getByEscola($escolaId);
            
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $pais = $paiModel->getByEscola($escolaId);
            
            return $this->view('global_admin.aluno-create', [
                'title' => 'Novo Aluno - Educatudo',
                'escola' => $escola,
                'turmas' => $turmas,
                'pais' => $pais,
                'errors' => ['geral' => 'Erro ao criar aluno: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function editAluno(int $escolaId, int $alunoId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $aluno = $alunoModel->find($alunoId);
        
        if (!$aluno) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        // Buscar dados do usuário
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($aluno['usuario_id']);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $turmaModel = new \Educatudo\Models\Turma($this->db);
        $turmas = $turmaModel->getByEscola($escolaId);
        
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $pais = $paiModel->getByEscola($escolaId);
        
        return $this->view('global_admin.aluno-edit', [
            'title' => 'Editar Aluno - Educatudo',
            'escola' => $escola,
            'aluno' => $aluno,
            'usuario' => $usuario,
            'turmas' => $turmas,
            'pais' => $pais
        ]);
    }
    
    public function updateAluno(int $escolaId, int $alunoId)
    {
        $data = $this->request->input();
        
        $alunoModel = new \Educatudo\Models\Aluno($this->db);
        $aluno = $alunoModel->find($alunoId);
        
        if (!$aluno) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $errors = $this->validateAluno($data, $escolaId, $aluno['usuario_id']);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($aluno['usuario_id']);
            
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turmas = $turmaModel->getByEscola($escolaId);
            
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $pais = $paiModel->getByEscola($escolaId);
            
            return $this->view('global_admin.aluno-edit', [
                'title' => 'Editar Aluno - Educatudo',
                'escola' => $escola,
                'aluno' => $aluno,
                'usuario' => $usuario,
                'turmas' => $turmas,
                'pais' => $pais,
                'errors' => $errors
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Atualizar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => !empty($data['email']) ? $data['email'] : null
            ];
            
            if (!empty($data['senha'])) {
                $usuarioData['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            
            if (!$usuarioModel->update($aluno['usuario_id'], $usuarioData)) {
                throw new \Exception('Erro ao atualizar usuário');
            }
            
            // Atualizar aluno
            $alunoData = [
                'ra' => $data['ra'],
                'turma_id' => !empty($data['turma_id']) ? $data['turma_id'] : null,
                'serie' => $data['serie'],
                'data_nasc' => !empty($data['data_nasc']) ? $data['data_nasc'] : null,
                'responsavel_id' => !empty($data['responsavel_id']) ? $data['responsavel_id'] : null,
                'ativo' => isset($data['ativo']) ? 1 : 0
            ];
            
            if (!$alunoModel->update($alunoId, $alunoData)) {
                throw new \Exception('Erro ao atualizar aluno');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($aluno['usuario_id']);
            
            $turmaModel = new \Educatudo\Models\Turma($this->db);
            $turmas = $turmaModel->getByEscola($escolaId);
            
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $pais = $paiModel->getByEscola($escolaId);
            
            return $this->view('global_admin.aluno-edit', [
                'title' => 'Editar Aluno - Educatudo',
                'escola' => $escola,
                'aluno' => $aluno,
                'usuario' => $usuario,
                'turmas' => $turmas,
                'pais' => $pais,
                'errors' => ['geral' => 'Erro ao atualizar aluno: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function deleteAluno(int $escolaId, int $alunoId)
    {
        try {
            $this->db->beginTransaction();
            
            $alunoModel = new \Educatudo\Models\Aluno($this->db);
            $aluno = $alunoModel->find($alunoId);
            
            if (!$aluno) {
                return $this->redirect('/admin/escolas/' . $escolaId);
            }
            
            if (!$alunoModel->delete($alunoId)) {
                throw new \Exception('Erro ao excluir aluno');
            }
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            if (!$usuarioModel->delete($aluno['usuario_id'])) {
                throw new \Exception('Erro ao excluir usuário');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
    }
    
    private function validateAluno(array $data, int $escolaId, int $excludeUsuarioId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (!empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email inválido';
            } else {
                $usuarioModel = new \Educatudo\Models\Usuario($this->db);
                $sql = "SELECT id FROM usuarios WHERE email = :email AND escola_id = :escola_id";
                $params = ['email' => $data['email'], 'escola_id' => $escolaId];
                
                if ($excludeUsuarioId) {
                    $sql .= " AND id != :exclude_id";
                    $params['exclude_id'] = $excludeUsuarioId;
                }
                
                $existente = $this->db->fetch($sql, $params);
                if ($existente) {
                    $errors['email'] = 'Email já cadastrado';
                }
            }
        }
        
        if (empty($data['ra'])) {
            $errors['ra'] = 'RA é obrigatório';
        } else {
            $alunoModel = new \Educatudo\Models\Aluno($this->db);
            $sql = "SELECT a.id FROM alunos a 
                    INNER JOIN usuarios u ON a.usuario_id = u.id 
                    WHERE a.ra = :ra AND u.escola_id = :escola_id";
            $params = ['ra' => $data['ra'], 'escola_id' => $escolaId];
            
            if ($excludeUsuarioId) {
                $sql .= " AND a.usuario_id != :exclude_id";
                $params['exclude_id'] = $excludeUsuarioId;
            }
            
            $existente = $this->db->fetch($sql, $params);
            if ($existente) {
                $errors['ra'] = 'RA já cadastrado';
            }
        }
        
        if (empty($data['serie'])) {
            $errors['serie'] = 'Série é obrigatória';
        }
        
        if (empty($data['senha']) && !$excludeUsuarioId) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        return $errors;
    }

    // ==================== CRUD de Pais ====================
    
    public function createPai(int $escolaId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        return $this->view('global_admin.pai-create', [
            'title' => 'Novo Pai/Responsável - Educatudo',
            'escola' => $escola
        ]);
    }
    
    public function storePai(int $escolaId)
    {
        $data = $this->request->input();
        $errors = $this->validatePai($data, $escolaId);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            return $this->view('global_admin.pai-create', [
                'title' => 'Novo Pai/Responsável - Educatudo',
                'escola' => $escola,
                'errors' => $errors
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            // Criar usuário
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => !empty($data['email']) ? $data['email'] : null,
                'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
                'tipo' => 'pai',
                'escola_id' => $escolaId
            ];
            
            $usuarioId = $usuarioModel->create($usuarioData);
            
            if (!$usuarioId) {
                throw new \Exception('Erro ao criar usuário');
            }
            
            // Criar pai
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $paiData = [
                'usuario_id' => $usuarioId,
                'cpf' => !empty($data['cpf']) ? $data['cpf'] : null,
                'telefone' => !empty($data['telefone']) ? $data['telefone'] : null,
                'ativo' => 1
            ];
            
            $paiId = $paiModel->create($paiData);
            
            if (!$paiId) {
                throw new \Exception('Erro ao criar pai/responsável');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            return $this->view('global_admin.pai-create', [
                'title' => 'Novo Pai/Responsável - Educatudo',
                'escola' => $escola,
                'errors' => ['geral' => 'Erro ao criar pai/responsável: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function editPai(int $escolaId, int $paiId)
    {
        $escolaModel = new \Educatudo\Models\Escola($this->db);
        $escola = $escolaModel->find($escolaId);
        
        if (!$escola) {
            return $this->redirect('/admin/escolas');
        }
        
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $pai = $paiModel->find($paiId);
        
        if (!$pai) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $usuarioModel = new \Educatudo\Models\Usuario($this->db);
        $usuario = $usuarioModel->find($pai['usuario_id']);
        
        if (!$usuario || $usuario['escola_id'] != $escolaId) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        return $this->view('global_admin.pai-edit', [
            'title' => 'Editar Pai/Responsável - Educatudo',
            'escola' => $escola,
            'pai' => $pai,
            'usuario' => $usuario
        ]);
    }
    
    public function updatePai(int $escolaId, int $paiId)
    {
        $data = $this->request->input();
        
        $paiModel = new \Educatudo\Models\Pai($this->db);
        $pai = $paiModel->find($paiId);
        
        if (!$pai) {
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
        
        $errors = $this->validatePai($data, $escolaId, $pai['usuario_id']);
        
        if (!empty($errors)) {
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($pai['usuario_id']);
            
            return $this->view('global_admin.pai-edit', [
                'title' => 'Editar Pai/Responsável - Educatudo',
                'escola' => $escola,
                'pai' => $pai,
                'usuario' => $usuario,
                'errors' => $errors
            ]);
        }
        
        try {
            $this->db->beginTransaction();
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuarioData = [
                'nome' => $data['nome'],
                'email' => !empty($data['email']) ? $data['email'] : null
            ];
            
            if (!empty($data['senha'])) {
                $usuarioData['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            
            if (!$usuarioModel->update($pai['usuario_id'], $usuarioData)) {
                throw new \Exception('Erro ao atualizar usuário');
            }
            
            $paiData = [
                'cpf' => !empty($data['cpf']) ? $data['cpf'] : null,
                'telefone' => !empty($data['telefone']) ? $data['telefone'] : null,
                'ativo' => isset($data['ativo']) ? 1 : 0
            ];
            
            if (!$paiModel->update($paiId, $paiData)) {
                throw new \Exception('Erro ao atualizar pai/responsável');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            
            $escolaModel = new \Educatudo\Models\Escola($this->db);
            $escola = $escolaModel->find($escolaId);
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            $usuario = $usuarioModel->find($pai['usuario_id']);
            
            return $this->view('global_admin.pai-edit', [
                'title' => 'Editar Pai/Responsável - Educatudo',
                'escola' => $escola,
                'pai' => $pai,
                'usuario' => $usuario,
                'errors' => ['geral' => 'Erro ao atualizar pai/responsável: ' . $e->getMessage()]
            ]);
        }
    }
    
    public function deletePai(int $escolaId, int $paiId)
    {
        try {
            $this->db->beginTransaction();
            
            $paiModel = new \Educatudo\Models\Pai($this->db);
            $pai = $paiModel->find($paiId);
            
            if (!$pai) {
                return $this->redirect('/admin/escolas/' . $escolaId);
            }
            
            if (!$paiModel->delete($paiId)) {
                throw new \Exception('Erro ao excluir pai/responsável');
            }
            
            $usuarioModel = new \Educatudo\Models\Usuario($this->db);
            if (!$usuarioModel->delete($pai['usuario_id'])) {
                throw new \Exception('Erro ao excluir usuário');
            }
            
            $this->db->commit();
            
            return $this->redirect('/admin/escolas/' . $escolaId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            return $this->redirect('/admin/escolas/' . $escolaId);
        }
    }
    
    private function validatePai(array $data, int $escolaId, int $excludeUsuarioId = null): array
    {
        $errors = [];
        
        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        }
        
        if (!empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email inválido';
            } else {
                $usuarioModel = new \Educatudo\Models\Usuario($this->db);
                $sql = "SELECT id FROM usuarios WHERE email = :email AND escola_id = :escola_id";
                $params = ['email' => $data['email'], 'escola_id' => $escolaId];
                
                if ($excludeUsuarioId) {
                    $sql .= " AND id != :exclude_id";
                    $params['exclude_id'] = $excludeUsuarioId;
                }
                
                $existente = $this->db->fetch($sql, $params);
                if ($existente) {
                    $errors['email'] = 'Email já cadastrado';
                }
            }
        }
        
        if (empty($data['senha']) && !$excludeUsuarioId) {
            $errors['senha'] = 'Senha é obrigatória';
        } elseif (!empty($data['senha']) && strlen($data['senha']) < 6) {
            $errors['senha'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        return $errors;
    }
}
