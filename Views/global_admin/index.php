<?php
$title = $title ?? 'Dashboard - Admin Global';
$user = $user ?? null;
$estatisticas = $estatisticas ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Bem-vindo, <?php echo htmlspecialchars($user['nome'] ?? 'Admin'); ?>!</h1>
                    <p class="text-muted mb-0">Gerencie todas as escolas da plataforma Educatudo</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6">Super Admin</span>
                    <p class="text-muted small mb-0 mt-1"><?php echo date('d/m/Y H:i'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['total'] ?? '2'; ?></div>
                        <div class="label">Total de Escolas</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['escolas_ativas'] ?? '2'; ?></div>
                        <div class="label">Escolas Ativas</div>
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
                        <div class="number"><?php echo $estatisticas['total_usuarios'] ?? '5'; ?></div>
                        <div class="label">Total de Usuários</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['planos_premium'] ?? '1'; ?></div>
                        <div class="label">Planos Premium</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Ações Rápidas</h4>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="<?php echo $app->url('/admin/escolas'); ?>" class="quick-action-btn">
                <i class="bi bi-building text-primary"></i>
                <h6 class="mb-1">Gerenciar Escolas</h6>
                <small class="text-muted">Visualizar e editar escolas</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="<?php echo $app->url('/admin/escolas/create'); ?>" class="quick-action-btn">
                <i class="bi bi-plus-circle text-success"></i>
                <h6 class="mb-1">Criar Nova Escola</h6>
                <small class="text-muted">Adicionar nova escola</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="<?php echo $app->url('/admin/usuarios'); ?>" class="quick-action-btn">
                <i class="bi bi-people text-info"></i>
                <h6 class="mb-1">Gerenciar Usuários</h6>
                <small class="text-muted">Administrar usuários globais</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="<?php echo $app->url('/admin/exercicios'); ?>" class="quick-action-btn">
                <i class="bi bi-journal-text text-warning"></i>
                <h6 class="mb-1">Exercícios</h6>
                <small class="text-muted">Gerenciar exercícios</small>
            </a>
        </div>
    </div>

    <!-- Escolas Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Escolas Recentes</h5>
                    <a href="<?php echo $app->url('/admin/escolas'); ?>" class="btn btn-sm btn-outline-primary">
                        Ver Todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Subdomínio</th>
                                    <th>Plano</th>
                                    <th>Status</th>
                                    <th>Usuários</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($escolas)): ?>
                                    <?php 
                                    $cores = ['bg-primary', 'bg-info', 'bg-success', 'bg-warning', 'bg-danger'];
                                    $planoColors = [
                                        'basico' => 'warning',
                                        'avancado' => 'info',
                                        'premium' => 'success'
                                    ];
                                    foreach ($escolas as $index => $escola): 
                                        $cor = $cores[$index % count($cores)];
                                        $planoColor = $planoColors[$escola['plano']] ?? 'secondary';
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="<?php echo $cor; ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($escola['nome']); ?></strong>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($escola['subdominio']); ?>.educatudo.com</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code><?php echo htmlspecialchars($escola['subdominio']); ?></code></td>
                                        <td><span class="badge bg-<?php echo $planoColor; ?>"><?php echo ucfirst($escola['plano']); ?></span></td>
                                        <td><span class="badge bg-<?php echo $escola['ativa'] ? 'success' : 'danger'; ?>"><?php echo $escola['ativa'] ? 'Ativa' : 'Inativa'; ?></span></td>
                                        <td>
                                            <?php 
                                            $totalUsuarios = $escola['total_usuarios'] ?? 0;
                                            echo $totalUsuarios . ' ' . ($totalUsuarios === 1 ? 'usuário' : 'usuários'); 
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/edit'); ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-building" style="font-size: 2rem; opacity: 0.3;"></i>
                                            <br><br>
                                            Nenhuma escola cadastrada
                                        </td>
                                    </tr>
                                <?php endif; ?>
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
