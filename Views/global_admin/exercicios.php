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
                    <a href="<?= url('admin/exercicios/listas/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nova Lista
                    </a>
                    <a href="<?= url('admin/exercicios/import') ?>" class="btn btn-success ms-2">
                        <i class="bi bi-file-earmark-arrow-up"></i> Importar JSON
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?= $stats['total_listas'] ?? 0 ?></div>
                        <div class="label">Listas de Exercícios</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-collection"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?= $stats['total_questoes'] ?? 0 ?></div>
                        <div class="label">Total de Questões</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-question-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?= $stats['total_materias'] ?? 0 ?></div>
                        <div class="label">Matérias</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-book"></i>
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
        <div class="col-lg-4 col-md-6 mb-3">
            <a href="<?= url('admin/exercicios/listas/create') ?>" class="quick-action-btn">
                <i class="bi bi-plus-circle text-primary"></i>
                <h6 class="mb-1">Criar Lista Manual</h6>
                <small class="text-muted">Adicionar lista de exercícios manualmente</small>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <a href="<?= url('admin/exercicios/import') ?>" class="quick-action-btn">
                <i class="bi bi-file-earmark-arrow-up text-success"></i>
                <h6 class="mb-1">Importar JSON</h6>
                <small class="text-muted">Importar listas de exercícios de arquivo</small>
            </a>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <a href="<?= url('admin/exercicios') ?>" class="quick-action-btn">
                <i class="bi bi-search text-info"></i>
                <h6 class="mb-1">Banco de Questões</h6>
                <small class="text-muted">Buscar e visualizar todas as questões</small>
            </a>
        </div>
    </div>

    <!-- Listas de Exercícios Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Listas de Exercícios</h5>
                    <div>
                        <input type="text" class="form-control form-control-sm" placeholder="Buscar..." style="width: 200px; display: inline-block;">
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($listas)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                            <p class="text-muted mt-3">Nenhuma lista de exercícios cadastrada ainda.</p>
                            <a href="<?= url('admin/exercicios/listas/create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Criar Primeira Lista
                            </a>
                            <a href="<?= url('admin/exercicios/import') ?>" class="btn btn-success ms-2">
                                <i class="bi bi-file-earmark-arrow-up"></i> Importar JSON
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Título da Lista</th>
                                        <th>Matéria</th>
                                        <th>Série</th>
                                        <th>Nível</th>
                                        <th>Questões</th>
                                        <th>Criado</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listas as $lista): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-collection"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($lista['titulo']) ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary"><?= htmlspecialchars($lista['materia']) ?></span></td>
                                            <td><?= htmlspecialchars($lista['serie']) ?></td>
                                            <td>
                                                <?php
                                                $badgeClass = match($lista['nivel_dificuldade']) {
                                                    'Fácil' => 'bg-success',
                                                    'Médio' => 'bg-warning',
                                                    'Difícil' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($lista['nivel_dificuldade']) ?></span>
                                            </td>
                                            <td><span class="badge bg-info"><?= $lista['total_questoes'] ?> questões</span></td>
                                            <td><small class="text-muted"><?= date('d/m/Y', strtotime($lista['created_at'])) ?></small></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= url('admin/exercicios/listas/' . $lista['id']) ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= url('admin/exercicios/listas/' . $lista['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmarExclusao(<?= $lista['id'] ?>)" title="Excluir">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta lista de exercícios? Todas as questões serão removidas.')) {
        fetch('<?= url("admin/exercicios/listas/") ?>' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Erro ao excluir lista: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            alert('Erro ao excluir lista');
            console.error(error);
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>
