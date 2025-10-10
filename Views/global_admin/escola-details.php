<?php
$title = $title ?? 'Detalhes da Escola - Admin Global';
$escola = $escola ?? null;
$estatisticas = $estatisticas ?? [];
$usuarios = $usuarios ?? [];
$alunos = $alunos ?? [];
$professores = $professores ?? [];
$pais = $pais ?? [];
$turmas = $turmas ?? [];
$materias = $materias ?? [];

if (!$escola) {
    header('Location: ' . $app->url('/admin/escolas'));
    exit;
}

ob_start();
?>

<style>
/* Corrigir cores das Nav Tabs */
.nav-tabs .nav-link {
    color: #495057 !important;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.nav-tabs .nav-link:hover {
    color: #0d6efd !important;
    background-color: #e9ecef;
}

.nav-tabs .nav-link.active {
    color: #0d6efd !important;
    background-color: #fff !important;
    border-color: #dee2e6 #dee2e6 #fff;
    font-weight: 600;
}

.nav-tabs .nav-link .badge {
    opacity: 1 !important;
}

/* Garantir que o conteúdo das tabelas seja visível */
.tab-content {
    background-color: #fff;
    color: #212529;
}

.table {
    color: #212529 !important;
}

.table thead th {
    color: #495057 !important;
}

.table tbody td {
    color: #212529 !important;
}
</style>

<div class="container-fluid">
    <!-- Header da Escola -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <?php if (!empty($escola['logo'])): ?>
                            <img src="<?php echo htmlspecialchars($escola['logo']); ?>" 
                                 alt="Logo" class="img-fluid rounded" style="max-height: 100px;">
                            <?php else: ?>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-building" style="font-size: 2.5rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h1 class="h2 mb-1"><?php echo htmlspecialchars($escola['nome']); ?></h1>
                            <p class="text-muted mb-2">
                                <i class="bi bi-globe me-1"></i>
                                <strong>Subdomínio:</strong> 
                                <code><?php echo htmlspecialchars($escola['subdominio']); ?>.educatudo.com</code>
                            </p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-<?php 
                                    echo $escola['plano'] === 'premium' ? 'warning' : 
                                        ($escola['plano'] === 'avancado' ? 'info' : 'secondary'); 
                                ?> fs-6">
                                    <?php echo ucfirst($escola['plano'] ?? 'básico'); ?>
                                </span>
                                <span class="badge bg-<?php echo ($escola['ativa'] ?? true) ? 'success' : 'danger'; ?> fs-6">
                                    <?php echo ($escola['ativa'] ?? true) ? 'Ativa' : 'Inativa'; ?>
                                </span>
                                <span class="badge bg-secondary fs-6">
                                    ID: <?php echo $escola['id']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <div class="btn-group-vertical" role="group">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/edit'); ?>" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="<?php echo $app->url('/admin/escolas'); ?>" 
                                   class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['usuarios']['total'] ?? count($usuarios); ?></div>
                        <div class="label">Total de Usuários</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['alunos']['total'] ?? count($alunos); ?></div>
                        <div class="label">Alunos</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['professores']['total'] ?? count($professores); ?></div>
                        <div class="label">Professores</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['turmas']['total'] ?? count($turmas); ?></div>
                        <div class="label">Turmas</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-collection"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Escola -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações da Escola</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Dados Básicos</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nome:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['nome']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>CNPJ:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['cnpj'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['email'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['telefone'] ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Configurações</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Plano:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $escola['plano'] === 'premium' ? 'warning' : 
                                                ($escola['plano'] === 'avancado' ? 'info' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($escola['plano'] ?? 'básico'); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($escola['ativa'] ?? true) ? 'success' : 'danger'; ?>">
                                            <?php echo ($escola['ativa'] ?? true) ? 'Ativa' : 'Inativa'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Criada em:</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($escola['created_at'] ?? 'now')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Última atualização:</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($escola['updated_at'] ?? 'now')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($escola['endereco'])): ?>
                    <div class="mt-3">
                        <h6>Endereço</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($escola['endereco'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/edit'); ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar Escola
                        </a>
                        <button class="btn btn-outline-<?php echo ($escola['ativa'] ?? true) ? 'danger' : 'success'; ?>" 
                                onclick="toggleStatus(<?php echo $escola['id']; ?>, <?php echo ($escola['ativa'] ?? true) ? 'false' : 'true'; ?>)">
                            <i class="bi bi-<?php echo ($escola['ativa'] ?? true) ? 'ban' : 'check'; ?>"></i>
                            <?php echo ($escola['ativa'] ?? true) ? 'Desativar' : 'Ativar'; ?>
                        </button>
                        <a href="<?php echo $app->url('/admin/escolas'); ?>" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
     </div>

     <!-- Adicionar Novos Usuários -->
     <div class="row mb-4">
         <div class="col-md-12 mb-3">
             <div class="card">
                 <div class="card-header">
                     <h5 class="mb-0">Adicionar Recursos Acadêmicos</h5>
                 </div>
                 <div class="card-body">
                     <div class="d-flex gap-2 flex-wrap">
                         <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/materias/create'); ?>" class="btn btn-outline-info">
                             <i class="bi bi-book"></i> Nova Matéria
                         </a>
                         <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/turmas/create'); ?>" class="btn btn-outline-secondary">
                             <i class="bi bi-collection"></i> Nova Turma
                         </a>
                     </div>
                 </div>
             </div>
         </div>
     </div>

    <!-- Usuários da Escola com Nav Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Gestão de Usuários</h5>
                </div>
                <div class="card-body">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs mb-3" id="usuariosTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button" role="tab">
                                <i class="bi bi-shield-check"></i> Usuários da Escola
                                <span class="badge bg-danger ms-1">
                                    <?php 
                                    $adminsCount = count(array_filter($usuarios, function($u) { 
                                        return $u['tipo'] === 'admin_escola' || $u['tipo'] === 'diretor' || $u['tipo'] === 'coordenador'; 
                                    }));
                                    echo $adminsCount;
                                    ?>
                                </span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="professores-tab" data-bs-toggle="tab" data-bs-target="#professores" type="button" role="tab">
                                <i class="bi bi-person-badge"></i> Professores
                                <span class="badge bg-info ms-1"><?php echo count($professores); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="alunos-tab" data-bs-toggle="tab" data-bs-target="#alunos" type="button" role="tab">
                                <i class="bi bi-person-check"></i> Alunos
                                <span class="badge bg-success ms-1"><?php echo count($alunos); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pais-tab" data-bs-toggle="tab" data-bs-target="#pais" type="button" role="tab">
                                <i class="bi bi-people"></i> Pais/Responsáveis
                                <span class="badge bg-warning ms-1"><?php echo count($pais); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="materias-tab" data-bs-toggle="tab" data-bs-target="#materias" type="button" role="tab">
                                <i class="bi bi-book"></i> Matérias
                                <span class="badge bg-primary ms-1"><?php echo count($materias); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="turmas-tab" data-bs-toggle="tab" data-bs-target="#turmas" type="button" role="tab">
                                <i class="bi bi-collection"></i> Turmas
                                <span class="badge bg-secondary ms-1"><?php echo count($turmas); ?></span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="usuariosTabContent">
                        
                        <!-- Tab: Usuários da Escola (Diretores, Coordenadores) -->
                        <div class="tab-pane fade show active" id="admins" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/usuarios/create'); ?>" class="btn btn-danger">
                                    <i class="bi bi-plus-circle"></i> Novo Administrador
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Cargo</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $admins = array_filter($usuarios, function($u) { 
                                            return $u['tipo'] === 'admin_escola' || $u['tipo'] === 'diretor' || $u['tipo'] === 'coordenador'; 
                                        });
                                        
                                        if (empty($admins)): 
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-shield-check" style="font-size: 2rem; opacity: 0.3;"></i>
                                                <br><br>
                                                <h6>Nenhum usuário administrativo cadastrado</h6>
                                                <p class="mb-0">Clique em "Novo Usuário" para adicionar um diretor ou coordenador.</p>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($admins as $admin): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-shield-check"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($admin['nome']); ?></strong>
                                                            <br><small class="text-muted">ID: <?php echo $admin['id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        <?php 
                                                        $cargo = ucfirst(str_replace('_', ' ', $admin['tipo']));
                                                        echo htmlspecialchars($cargo);
                                                        ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($admin['email'] ?? '-'); ?></td>
                                                <td>
                                                    <?php 
                                                    // Você pode adicionar campo telefone na tabela usuarios se necessário
                                                    echo '<span class="text-muted">-</span>'; 
                                                    ?>
                                                </td>
                                                <td><span class="badge bg-success">Ativo</span></td>
                                                <td>
                                                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/usuarios/' . $admin['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: Professores -->
                        <div class="tab-pane fade" id="professores" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/professores/create'); ?>" class="btn btn-info">
                                    <i class="bi bi-plus-circle"></i> Novo Professor
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Professor</th>
                                            <th>Código</th>
                                            <th>Email</th>
                                            <th>Matérias</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($professores)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-person-badge" style="font-size: 2rem; opacity: 0.3;"></i>
                                                <br><br>
                                                <h6>Nenhum professor cadastrado</h6>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($professores as $prof): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-person-badge"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($prof['nome']); ?></strong>
                                                            <br><small class="text-muted">ID: <?php echo $prof['usuario_id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><code><?php echo htmlspecialchars($prof['codigo_prof']); ?></code></td>
                                                <td><?php echo htmlspecialchars($prof['email'] ?? '-'); ?></td>
                                                <td>
                                                    <?php 
                                                    $materiasProf = !empty($prof['materias']) ? json_decode($prof['materias'], true) : [];
                                                    if (!empty($materiasProf)) {
                                                        echo '<small>' . htmlspecialchars(implode(', ', array_slice($materiasProf, 0, 2))) . '</small>';
                                                        if (count($materiasProf) > 2) {
                                                            echo ' <span class="badge bg-secondary">+' . (count($materiasProf) - 2) . '</span>';
                                                        }
                                                    } else {
                                                        echo '<span class="text-muted">-</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $prof['ativo'] ? 'success' : 'danger'; ?>">
                                                        <?php echo $prof['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/professores/' . $prof['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: Alunos -->
                        <div class="tab-pane fade" id="alunos" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/alunos/create'); ?>" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Novo Aluno
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Aluno</th>
                                            <th>RA</th>
                                            <th>Email</th>
                                            <th>Série</th>
                                            <th>Turma</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($alunos)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bi bi-person-check" style="font-size: 2rem; opacity: 0.3;"></i>
                                                <br><br>
                                                <h6>Nenhum aluno cadastrado</h6>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($alunos as $aluno): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-person-check"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($aluno['nome']); ?></strong>
                                                            <br><small class="text-muted">ID: <?php echo $aluno['usuario_id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><code><?php echo htmlspecialchars($aluno['ra']); ?></code></td>
                                                <td><?php echo htmlspecialchars($aluno['email'] ?? '-'); ?></td>
                                                <td><small><?php echo htmlspecialchars($aluno['serie'] ?? '-'); ?></small></td>
                                                <td>
                                                    <?php 
                                                    if (!empty($aluno['turma_nome'])) {
                                                        echo '<span class="badge bg-secondary">' . htmlspecialchars($aluno['turma_nome']) . '</span>';
                                                    } else {
                                                        echo '<span class="text-muted">-</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $aluno['ativo'] ? 'success' : 'danger'; ?>">
                                                        <?php echo $aluno['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/alunos/' . $aluno['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: Pais/Responsáveis -->
                        <div class="tab-pane fade" id="pais" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/pais/create'); ?>" class="btn btn-warning">
                                    <i class="bi bi-plus-circle"></i> Novo Responsável
                                </a>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width: 180px;">Responsável</th>
                                            <th style="min-width: 200px;">Email</th>
                                            <th style="min-width: 120px;">Telefone</th>
                                            <th style="min-width: 120px;">CPF</th>
                                            <th style="min-width: 250px;">Alunos (Filhos)</th>
                                            <th style="min-width: 80px;">Status</th>
                                            <th style="min-width: 100px;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($pais)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bi bi-people" style="font-size: 2rem; opacity: 0.3;"></i>
                                                <br><br>
                                                <h6>Nenhum responsável cadastrado</h6>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($pais as $pai): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-people"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($pai['nome']); ?></strong>
                                                            <br><small class="text-muted">ID: <?php echo $pai['usuario_id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($pai['email'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($pai['telefone'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($pai['cpf'] ?? '-'); ?></td>
                                                <td style="max-width: 300px;">
                                                    <?php if (!empty($pai['alunos_vinculados'])): ?>
                                                        <div class="d-flex flex-wrap gap-1" style="max-height: 80px; overflow-y: auto;">
                                                            <?php foreach ($pai['alunos_vinculados'] as $aluno): ?>
                                                                <span class="badge bg-info text-dark" 
                                                                      style="white-space: nowrap;"
                                                                      title="RA: <?php echo htmlspecialchars($aluno['ra']); ?><?php echo !empty($aluno['serie']) ? ' | Série: ' . htmlspecialchars($aluno['serie']) : ''; ?>">
                                                                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($aluno['nome']); ?>
                                                                </span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <small class="text-muted mt-1 d-block">
                                                            <i class="bi bi-info-circle"></i> <?php echo count($pai['alunos_vinculados']); ?> 
                                                            <?php echo count($pai['alunos_vinculados']) === 1 ? 'aluno' : 'alunos'; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">
                                                            <i class="bi bi-dash-circle"></i> Nenhum aluno vinculado
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $pai['ativo'] ? 'success' : 'danger'; ?>">
                                                        <?php echo $pai['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/pais/' . $pai['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: Matérias -->
                        <div class="tab-pane fade" id="materias" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/materias/create'); ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Nova Matéria
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Matéria</th>
                                            <th>Professor Responsável</th>
                                            <th>Criada em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($materias)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="bi bi-book" style="font-size: 2rem; opacity: 0.3;"></i>
                                                <br><br>
                                                <h6>Nenhuma matéria cadastrada</h6>
                                                <p class="mb-0">Clique em "Nova Matéria" para começar.</p>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($materias as $materia): ?>
                                                <?php 
                                                // Proteção: verificar se $materia é um array
                                                if (!is_array($materia)) {
                                                    continue;
                                                }
                                                ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-book"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($materia['nome'] ?? 'Sem nome'); ?></strong>
                                                            <br><small class="text-muted">ID: <?php echo $materia['id'] ?? '-'; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if (!empty($materia['professor_nome'])) {
                                                        echo '<span class="badge bg-info">' . htmlspecialchars($materia['professor_nome']) . '</span>';
                                                    } else {
                                                        echo '<span class="text-muted">Sem professor</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo isset($materia['created_at']) ? date('d/m/Y', strtotime($materia['created_at'])) : '-'; ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/materias/' . ($materia['id'] ?? 0) . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab: Turmas -->
                        <div class="tab-pane fade" id="turmas" role="tabpanel">
                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/turmas/create'); ?>" class="btn btn-secondary">
                                    <i class="bi bi-plus-circle"></i> Nova Turma
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Turma</th>
                                            <th>Série</th>
                                            <th>Ano Letivo</th>
                                            <th>Período</th>
                                            <th>Alunos</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($turmas)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bi bi-collection" style="font-size: 2rem; opacity: 0.3;"></i>
                                                <br><br>
                                                <h6>Nenhuma turma cadastrada</h6>
                                                <p class="mb-0">Clique em "Nova Turma" para começar.</p>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($turmas as $turma): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                            <i class="bi bi-collection"></i>
                                                        </div>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($turma['nome']); ?></strong>
                                                            <br><small class="text-muted">ID: <?php echo $turma['id']; ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($turma['serie'] ?? '-'); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark"><?php echo htmlspecialchars($turma['ano_letivo'] ?? '-'); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php echo htmlspecialchars($turma['periodo'] ?? 'Integral'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php 
                                                    // Contar alunos da turma
                                                    $alunosDaTurma = array_filter($alunos, function($a) use ($turma) {
                                                        return isset($a['turma_id']) && $a['turma_id'] == $turma['id'];
                                                    });
                                                    $qtdAlunos = count($alunosDaTurma);
                                                    ?>
                                                    <span class="badge bg-success"><?php echo $qtdAlunos; ?> aluno<?php echo $qtdAlunos != 1 ? 's' : ''; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $turma['ativo'] ? 'success' : 'danger'; ?>">
                                                        <?php echo $turma['ativo'] ? 'Ativa' : 'Inativa'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/turmas/' . $turma['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(escolaId, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    if (confirm(`Tem certeza que deseja ${acao} esta escola?`)) {
        fetch(`<?php echo $app->url('/admin/escolas/' . $escolaId . '/toggle-status'); ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ativa: novoStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar solicitação');
        });
    }
}

// Adicionar tooltips para os botões de ação
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips do Bootstrap se disponível
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>