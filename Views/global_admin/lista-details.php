<?php
$title = $title ?? 'Detalhes da Lista - Admin Global';
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1"><?= htmlspecialchars($lista['titulo']) ?></h1>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($lista['materia']) ?> - <?= htmlspecialchars($lista['serie']) ?>
                        <span class="badge <?= $lista['nivel_dificuldade'] === 'Fácil' ? 'bg-success' : ($lista['nivel_dificuldade'] === 'Médio' ? 'bg-warning' : 'bg-danger') ?> ms-2">
                            <?= htmlspecialchars($lista['nivel_dificuldade']) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <a href="<?= url('admin/exercicios/listas/' . $lista['id'] . '/edit') ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="<?= url('admin/exercicios') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Questões -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Questões (<?= count($lista['questoes']) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($lista['questoes'])): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Nenhuma questão nesta lista.
                        </div>
                    <?php else: ?>
                        <?php foreach ($lista['questoes'] as $index => $questao): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Questão <?= $index + 1 ?></h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3"><strong><?= nl2br(htmlspecialchars($questao['pergunta'])) ?></strong></p>
                                    
                                    <?php if ($questao['tipo'] === 'multipla_escolha'): ?>
                                        <div class="mb-3">
                                            <?php foreach ($questao['alternativas'] as $alt): ?>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" disabled <?= $alt['letra'] === $questao['resposta_correta'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label <?= $alt['letra'] === $questao['resposta_correta'] ? 'text-success fw-bold' : '' ?>">
                                                        <?= htmlspecialchars($alt['letra']) ?>. <?= htmlspecialchars($alt['texto']) ?>
                                                        <?php if ($alt['letra'] === $questao['resposta_correta']): ?>
                                                            <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-secondary">
                                            <i class="bi bi-pencil"></i> Questão dissertativa
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($questao['explicacao'])): ?>
                                        <div class="alert alert-info mb-0">
                                            <strong>Explicação:</strong><br>
                                            <?= nl2br(htmlspecialchars($questao['explicacao'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informações -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Informações</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total de Questões</small>
                        <h4 class="mb-0"><?= $lista['total_questoes'] ?></h4>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">Criado em</small><br>
                        <strong><?= date('d/m/Y \à\s H:i', strtotime($lista['created_at'])) ?></strong>
                    </div>
                    <div>
                        <small class="text-muted">Atualizado em</small><br>
                        <strong><?= date('d/m/Y \à\s H:i', strtotime($lista['updated_at'])) ?></strong>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ações</h5>
                </div>
                <div class="card-body">
                    <a href="<?= url('admin/exercicios/listas/' . $lista['id'] . '/edit') ?>" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-pencil"></i> Editar Lista
                    </a>
                    <button class="btn btn-danger w-100" onclick="confirmarExclusao(<?= $lista['id'] ?>)">
                        <i class="bi bi-trash"></i> Excluir Lista
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir esta lista de exercícios? Todas as questões serão removidas e esta ação não pode ser desfeita.')) {
        fetch('<?= url("admin/exercicios/listas/") ?>' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= url("admin/exercicios") ?>';
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

