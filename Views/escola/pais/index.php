<?php
$title = $title ?? 'Pais - Admin Escola';
$pais = $pais ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Pais e Responsáveis</h1>
                    <p class="text-muted mb-0">Gerencie os pais e responsáveis da escola</p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/escola/pais/create'); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Pai
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Pais -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Pais e Responsáveis</h5>
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
                                    <th>Responsável</th>
                                    <th>Email</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pais)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                        <h5>Nenhum pai/responsável cadastrado</h5>
                                        <p class="mb-3">Comece adicionando pais à escola.</p>
                                        <a href="<?php echo $app->url('/escola/pais/create'); ?>" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Adicionar Primeiro Pai
                                        </a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($pais as $pai): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($pai['nome']); ?></strong>
                                                    <br><small class="text-muted">ID: <?php echo $pai['usuario_id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($pai['email'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if (!empty($pai['cpf'])): ?>
                                                <?php echo htmlspecialchars($pai['cpf']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($pai['telefone'])): ?>
                                                <?php echo htmlspecialchars($pai['telefone']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($pai['ativo']): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo $app->url('/escola/pais/' . $pai['usuario_id'] . '/edit'); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-<?php echo $pai['ativo'] ? 'danger' : 'success'; ?>" 
                                                        onclick="toggleStatus(<?php echo $pai['usuario_id']; ?>, <?php echo $pai['ativo'] ? 'false' : 'true'; ?>)" 
                                                        title="<?php echo $pai['ativo'] ? 'Desativar' : 'Ativar'; ?>">
                                                    <i class="bi bi-<?php echo $pai['ativo'] ? 'ban' : 'check'; ?>"></i>
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
function toggleStatus(paiId, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    if (confirm(`Tem certeza que deseja ${acao} este pai/responsável?`)) {
        fetch(`<?php echo $app->url('/escola/pais/' . $pai['id'] . '/delete'); ?>`, {
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
