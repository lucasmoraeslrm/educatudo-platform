<?php
$title = $title ?? 'Professores - Admin Escola';
$professores = $professores ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Professores</h1>
                    <p class="text-muted mb-0">Gerencie os professores da escola</p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/escola/professores/create'); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Professor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Professores -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Professores</h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary active">Todos</button>
                        <button class="btn btn-sm btn-outline-primary">Ativos</button>
                        <button class="btn btn-sm btn-outline-primary">Inativos</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
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
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-person-badge" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                        <h5>Nenhum professor cadastrado</h5>
                                        <p class="mb-3">Comece adicionando professores à escola.</p>
                                        <a href="<?php echo $app->url('/escola/professores/create'); ?>" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Adicionar Primeiro Professor
                                        </a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($professores as $professor): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($professor['nome']); ?></strong>
                                                    <br><small class="text-muted">ID: <?php echo $professor['usuario_id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code><?php echo htmlspecialchars($professor['codigo_prof']); ?></code>
                                        </td>
                                        <td><?php echo htmlspecialchars($professor['email'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if (!empty($professor['materias'])): ?>
                                                <?php 
                                                $materias = is_string($professor['materias']) ? json_decode($professor['materias'], true) : $professor['materias'];
                                                if (is_array($materias)): 
                                                ?>
                                                    <?php foreach (array_slice($materias, 0, 2) as $materia): ?>
                                                        <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($materia); ?></span>
                                                    <?php endforeach; ?>
                                                    <?php if (count($materias) > 2): ?>
                                                        <span class="text-muted">+<?php echo count($materias) - 2; ?> mais</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted"><?php echo htmlspecialchars($professor['materias']); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">Não definido</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($professor['ativo']): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo $app->url('/escola/professores/' . $professor['usuario_id'] . '/edit'); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-<?php echo $professor['ativo'] ? 'danger' : 'success'; ?>" 
                                                        onclick="toggleStatus(<?php echo $professor['usuario_id']; ?>, <?php echo $professor['ativo'] ? 'false' : 'true'; ?>)" 
                                                        title="<?php echo $professor['ativo'] ? 'Desativar' : 'Ativar'; ?>">
                                                    <i class="bi bi-<?php echo $professor['ativo'] ? 'ban' : 'check'; ?>"></i>
                                                </button>
                                            </div>
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

<script>
function toggleStatus(professorId, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    if (confirm(`Tem certeza que deseja ${acao} este professor?`)) {
        fetch(`<?php echo $app->url('/escola/professores/' . $professor['id'] . '/delete'); ?>`, {
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
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
