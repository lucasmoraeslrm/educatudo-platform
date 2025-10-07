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
        $materias = $materiaModel->getByEscola($id);
        
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
            'materias' => $materias
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
        
        $data = $this->request->all();
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
        
        $data = $this->request->all();
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
        } catch (Exception $e) {
            return [
                'status' => 'offline',
                'message' => $e->getMessage()
            ];
        }
    }
}
