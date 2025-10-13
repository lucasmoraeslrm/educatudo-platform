<?php
$title = $title ?? 'Configurações - Escola';
$user = $user ?? null;
$basePath = $basePath ?? '';
$materias = $materias ?? [];
$turmas = $turmas ?? [];
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2">Configurações</h1>
                    <p class="text-muted">Gerencie matérias e séries da escola</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Seção Matérias -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Matérias</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalMateria">
                        <i class="bi bi-plus"></i> Nova Matéria
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($materias)): ?>
                        <div class="list-group">
                            <?php foreach ($materias as $materia): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?php echo htmlspecialchars($materia['nome']); ?></span>
                                    <div>
                                        <small class="text-muted">Professor: <?php echo htmlspecialchars($materia['professor_nome'] ?? 'Não atribuído'); ?></small>
                                        <button type="button" class="btn btn-danger btn-sm ms-2" onclick="deleteMateria(<?php echo $materia['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">Nenhuma matéria cadastrada</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Seção Séries/Turmas -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Séries/Turmas</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTurma">
                        <i class="bi bi-plus"></i> Nova Série
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($turmas)): ?>
                        <div class="list-group">
                            <?php foreach ($turmas as $turma): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($turma['nome']); ?></strong>
                                        <br><small class="text-muted">Série: <?php echo htmlspecialchars($turma['serie']); ?> | Ano: <?php echo $turma['ano_letivo']; ?></small>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteTurma(<?php echo $turma['id']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">Nenhuma série/turma cadastrada</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Matéria -->
<div class="modal fade" id="modalMateria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo $basePath; ?>/escola/configuracoes/materias">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Matéria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome_materia" class="form-label">Nome da Matéria</label>
                        <input type="text" class="form-control" id="nome_materia" name="nome" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nova Série -->
<div class="modal fade" id="modalTurma" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo $basePath; ?>/escola/configuracoes/series">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Série/Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nome_turma" class="form-label">Nome da Turma</label>
                        <input type="text" class="form-control" id="nome_turma" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="serie" class="form-label">Série</label>
                        <input type="text" class="form-control" id="serie" name="serie" required>
                    </div>
                    <div class="mb-3">
                        <label for="ano_letivo" class="form-label">Ano Letivo</label>
                        <input type="number" class="form-control" id="ano_letivo" name="ano_letivo" value="<?php echo date('Y'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function deleteMateria(id) {
    if (confirm('Tem certeza que deseja excluir esta matéria?')) {
        fetch('<?php echo $basePath; ?>/escola/configuracoes/materias/' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        }).then(() => {
            location.reload();
        });
    }
}

function deleteTurma(id) {
    if (confirm('Tem certeza que deseja excluir esta série/turma?')) {
        fetch('<?php echo $basePath; ?>/escola/configuracoes/series/' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        }).then(() => {
            location.reload();
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/app.php';
?>
