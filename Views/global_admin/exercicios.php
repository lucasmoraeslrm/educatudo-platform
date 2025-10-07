<?php
$title = $title ?? 'Exercícios - Admin Global';
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Exercícios Globais</h1>
                    <p class="text-muted mb-0">Gerencie exercícios e conteúdo educacional da plataforma</p>
                </div>
                <div>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Exercício
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">156</div>
                        <div class="label">Total de Exercícios</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">89</div>
                        <div class="label">Exercícios Ativos</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">23</div>
                        <div class="label">Gerados por IA</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-robot"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">12</div>
                        <div class="label">Categorias</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-tags"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Ações Rápidas</h4>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-plus-circle text-primary"></i>
                <h6 class="mb-1">Criar Exercício</h6>
                <small class="text-muted">Adicionar novo exercício</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-robot text-info"></i>
                <h6 class="mb-1">Gerar com IA</h6>
                <small class="text-muted">Criar exercícios automaticamente</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-tags text-success"></i>
                <h6 class="mb-1">Categorias</h6>
                <small class="text-muted">Gerenciar categorias</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-graph-up text-warning"></i>
                <h6 class="mb-1">Relatórios</h6>
                <small class="text-muted">Ver estatísticas</small>
            </a>
        </div>
    </div>

    <!-- Exercícios Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Exercícios Recentes</h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary active">Todos</button>
                        <button class="btn btn-sm btn-outline-primary">Ativos</button>
                        <button class="btn btn-sm btn-outline-primary">IA</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Exercício</th>
                                    <th>Categoria</th>
                                    <th>Escola</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Criado</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="bi bi-journal-text"></i>
                                            </div>
                                            <div>
                                                <strong>Exercício de Matemática</strong>
                                                <br><small class="text-muted">Soma e subtração básica</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Matemática</span></td>
                                    <td>Escola Demo</td>
                                    <td><span class="badge bg-info">Múltipla Escolha</span></td>
                                    <td><span class="badge bg-success">Ativo</span></td>
                                    <td><small class="text-muted">15/01/2024</small></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="bi bi-robot"></i>
                                            </div>
                                            <div>
                                                <strong>Exercício de Português</strong>
                                                <br><small class="text-muted">Interpretação de texto</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Português</span></td>
                                    <td>Colégio Exemplo</td>
                                    <td><span class="badge bg-warning">Dissertativa</span></td>
                                    <td><span class="badge bg-success">Ativo</span></td>
                                    <td><small class="text-muted">14/01/2024</small></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>


