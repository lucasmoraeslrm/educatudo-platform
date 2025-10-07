<?php
$title = $title ?? 'Novo Aluno - Admin Escola';
$turmas = $turmas ?? [];
$pais = $pais ?? [];
$errors = $errors ?? [];
$old = $old ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Novo Aluno</h1>
                    <p class="text-muted mb-0">Adicione um novo aluno à escola</p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/admin-escola/alunos'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo $app->url('/admin-escola/alunos'); ?>">
        <div class="row">
            <!-- Informações Básicas -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informações Básicas</h5>
                        <span class="badge bg-primary">Obrigatório</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" name="nome" value="<?php echo htmlspecialchars($old['nome'] ?? ''); ?>" required>
                                <?php if (isset($errors['nome'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="ra" class="form-label">RA (Registro Acadêmico) *</label>
                                <input type="text" class="form-control <?php echo isset($errors['ra']) ? 'is-invalid' : ''; ?>" 
                                       id="ra" name="ra" value="<?php echo htmlspecialchars($old['ra'] ?? ''); ?>" required>
                                <?php if (isset($errors['ra'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['ra']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Código único para login (ex: RA001)</small>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="senha" class="form-label">Senha *</label>
                                <input type="password" class="form-control <?php echo isset($errors['senha']) ? 'is-invalid' : ''; ?>" 
                                       id="senha" name="senha" required>
                                <?php if (isset($errors['senha'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['senha']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>
                            <div class="col-md-6">
                                <label for="data_nasc" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nasc" name="data_nasc" 
                                       value="<?php echo htmlspecialchars($old['data_nasc'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="turma_id" class="form-label">Turma</label>
                                <select class="form-select" id="turma_id" name="turma_id">
                                    <option value="">Selecione uma turma</option>
                                    <?php foreach ($turmas as $turma): ?>
                                    <option value="<?php echo $turma['id']; ?>" 
                                            <?php echo ($old['turma_id'] ?? '') == $turma['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($turma['nome']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="serie" class="form-label">Série</label>
                                <input type="text" class="form-control" id="serie" name="serie" 
                                       value="<?php echo htmlspecialchars($old['serie'] ?? ''); ?>" 
                                       placeholder="Ex: 1º Ano, 2º Ano">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="responsavel_id" class="form-label">Responsável</label>
                            <select class="form-select" id="responsavel_id" name="responsavel_id">
                                <option value="">Selecione um responsável</option>
                                <?php foreach ($pais as $pai): ?>
                                <option value="<?php echo $pai['usuario_id']; ?>" 
                                        <?php echo ($old['responsavel_id'] ?? '') == $pai['usuario_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($pai['nome']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text text-muted">Opcional - Vincular a um pai/responsável</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informações do Login</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Como o aluno fará login:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>RA:</strong> <?php echo htmlspecialchars($old['ra'] ?? 'RA001'); ?></li>
                                <li><strong>Senha:</strong> A senha definida acima</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <small>
                                <strong>Importante:</strong> O aluno usará o RA para fazer login, não email.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Criar Aluno
                            </button>
                            <a href="<?php echo $app->url('/admin-escola/alunos'); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Validação de RA
document.getElementById('ra').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
});

// Validação de senha
document.getElementById('senha').addEventListener('input', function() {
    const senha = this.value;
    const feedback = this.nextElementSibling;
    
    if (senha.length > 0 && senha.length < 6) {
        feedback.textContent = 'Senha deve ter pelo menos 6 caracteres';
        feedback.className = 'invalid-feedback';
        this.classList.add('is-invalid');
    } else {
        feedback.textContent = '';
        feedback.className = 'form-text text-muted';
        this.classList.remove('is-invalid');
    }
});

// Atualizar preview do RA
document.getElementById('ra').addEventListener('input', function() {
    const preview = document.querySelector('.alert-info li:first-child strong');
    if (preview) {
        preview.textContent = this.value || 'RA001';
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>
