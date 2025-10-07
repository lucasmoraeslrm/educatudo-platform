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
                            <h2 class="mb-0">1</h2>
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
                            <h2 class="mb-0">1</h2>
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
                            <h5 class="card-title">Turmas</h5>
                            <h2 class="mb-0">1</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-building" style="font-size: 2rem;"></i>
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
                            <h5 class="card-title">Matérias</h5>
                            <h2 class="mb-0">1</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-book" style="font-size: 2rem;"></i>
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
                            <a href="<?php echo $basePath; ?>/admin-escola/alunos" class="btn btn-primary w-100">
                                <i class="bi bi-people"></i> Gerenciar Alunos
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/admin-escola/professores" class="btn btn-success w-100">
                                <i class="bi bi-mortarboard"></i> Gerenciar Professores
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/admin-escola/turmas" class="btn btn-info w-100">
                                <i class="bi bi-building"></i> Gerenciar Turmas
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo $basePath; ?>/admin-escola/materias" class="btn btn-warning w-100">
                                <i class="bi bi-book"></i> Gerenciar Matérias
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
