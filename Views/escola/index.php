<?php
$title = $title ?? 'Admin da Escola - Educatudo';
$user = $user ?? null;
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2">Admin da Escola</h1>
                    <p class="text-muted">Gerencie sua instituição de ensino</p>
                </div>
                <div>
                    <span class="badge bg-info">Admin Escola</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total de Alunos</h5>
                            <h2 class="mb-0"><?php echo $estatisticas['alunos']['total'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Professores</h5>
                            <h2 class="mb-0"><?php echo $estatisticas['professores']['total'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-mortarboard" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Pais</h5>
                            <h2 class="mb-0"><?php echo $estatisticas['pais']['total'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-heart" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Interações Tudinha</h5>
                            <h2 class="mb-0"><?php echo $estatisticas['alunos']['total_interacoes_tudinha'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-chat-dots" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda linha de estatísticas -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Assertividade dos Exercícios</h5>
                            <h2 class="mb-0"><?php echo $estatisticas['alunos']['assertividade_exercicios'] ?? 0; ?>%</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Turmas Ativas</h5>
                            <h2 class="mb-0"><?php echo $estatisticas['turmas']['ativas'] ?? 0; ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-building" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/escola/alunos" class="btn btn-primary w-100">
                                <i class="bi bi-people"></i> Gerenciar Alunos
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/escola/professores" class="btn btn-success w-100">
                                <i class="bi bi-mortarboard"></i> Gerenciar Professores
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/escola/configuracoes" class="btn btn-info w-100">
                                <i class="bi bi-gear"></i> Configurações
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/escola/relatorios" class="btn btn-warning w-100">
                                <i class="bi bi-graph-up"></i> Relatórios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
