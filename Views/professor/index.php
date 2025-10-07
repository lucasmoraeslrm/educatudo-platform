<?php
$title = $title ?? 'Painel do Professor - Educatudo';
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2">Painel do Professor</h1>
                    <p class="text-muted">Gerencie suas jornadas e acompanhe seus alunos</p>
                </div>
                <div>
                    <span class="badge bg-warning">Professor</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Jornadas Criadas</h5>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Exercícios</h5>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-pencil-square" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Alunos Ativos</h5>
                            <h2 class="mb-0">1</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
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
                            <a href="<?php echo $basePath; ?>/professor/jornadas" class="btn btn-primary w-100">
                                <i class="bi bi-journal-text"></i> Jornadas do Aluno
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/professor/jornadas/create" class="btn btn-success w-100">
                                <i class="bi bi-plus-circle"></i> Criar Jornada
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/professor/exercicios" class="btn btn-info w-100">
                                <i class="bi bi-pencil-square"></i> Exercícios
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/professor/relatorios" class="btn btn-warning w-100">
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
