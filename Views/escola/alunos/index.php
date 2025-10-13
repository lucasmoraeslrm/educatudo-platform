<?php
$title = $title ?? 'Alunos - Admin Escola';
$alunos = $alunos ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Alunos</h1>
                    <p class="text-muted mb-0">Gerencie os alunos da escola</p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/escola/alunos/create'); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Aluno
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Alunos -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Alunos</h5>
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
                                    <th>Aluno</th>
                                    <th>RA</th>
                                    <th>Turma</th>
                                    <th>Série</th>
                                    <th>Data Nasc.</th>
                                    <th>Responsável</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($alunos)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-person-check" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                        <h5>Nenhum aluno cadastrado</h5>
                                        <p class="mb-3">Comece adicionando alunos à escola.</p>
                                        <a href="<?php echo $app->url('/escola/alunos/create'); ?>" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Adicionar Primeiro Aluno
                                        </a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($alunos as $aluno): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person-check"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($aluno['nome']); ?></strong>
                                                    <br><small class="text-muted">ID: <?php echo $aluno['usuario_id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code><?php echo htmlspecialchars($aluno['ra']); ?></code>
                                        </td>
                                        <td>
                                            <?php if (!empty($aluno['turma_nome'])): ?>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($aluno['turma_nome']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Não definida</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($aluno['serie'])): ?>
                                                <?php echo htmlspecialchars($aluno['serie']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($aluno['data_nasc'])): ?>
                                                <?php echo date('d/m/Y', strtotime($aluno['data_nasc'])); ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($aluno['responsavel_id'])): ?>
                                                <span class="text-muted">ID: <?php echo $aluno['responsavel_id']; ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Não definido</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($aluno['ativo']): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo $app->url('/escola/alunos/' . $aluno['usuario_id'] . '/edit'); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-<?php echo $aluno['ativo'] ? 'danger' : 'success'; ?>" 
                                                        onclick="toggleStatus(<?php echo $aluno['usuario_id']; ?>, <?php echo $aluno['ativo'] ? 'false' : 'true'; ?>)" 
                                                        title="<?php echo $aluno['ativo'] ? 'Desativar' : 'Ativar'; ?>">
                                                    <i class="bi bi-<?php echo $aluno['ativo'] ? 'ban' : 'check'; ?>"></i>
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
function toggleStatus(alunoId, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    if (confirm(`Tem certeza que deseja ${acao} este aluno?`)) {
        fetch(`<?php echo $app->url('/escola/alunos/' . $aluno['id'] . '/delete'); ?>`, {
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
