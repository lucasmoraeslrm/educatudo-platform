<?php
$title = $title ?? 'Novo Professor - Admin Escola';
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
                    <h1 class="h2 mb-1">Novo Professor</h1>
                    <p class="text-muted mb-0">Adicione um novo professor à escola</p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/admin-escola/professores'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo $app->url('/admin-escola/professores'); ?>">
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
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Usado para login e comunicação</small>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="codigo_prof" class="form-label">Código do Professor *</label>
                                <input type="text" class="form-control <?php echo isset($errors['codigo_prof']) ? 'is-invalid' : ''; ?>" 
                                       id="codigo_prof" name="codigo_prof" value="<?php echo htmlspecialchars($old['codigo_prof'] ?? ''); ?>" required>
                                <?php if (isset($errors['codigo_prof'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['codigo_prof']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Código único para login (ex: PROF001)</small>
                            </div>
                            <div class="col-md-6">
                                <label for="senha" class="form-label">Senha *</label>
                                <input type="password" class="form-control <?php echo isset($errors['senha']) ? 'is-invalid' : ''; ?>" 
                                       id="senha" name="senha" required>
                                <?php if (isset($errors['senha'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['senha']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="materias" class="form-label">Matérias que Leciona</label>
                            <textarea class="form-control" id="materias" name="materias" rows="3" 
                                      placeholder="Ex: Matemática, Física, Química"><?php echo htmlspecialchars($old['materias'] ?? ''); ?></textarea>
                            <small class="form-text text-muted">Liste as matérias separadas por vírgula</small>
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
                            <strong>Como o professor fará login:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Código:</strong> <?php echo htmlspecialchars($old['codigo_prof'] ?? 'PROF001'); ?></li>
                                <li><strong>Senha:</strong> A senha definida acima</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <small>
                                <strong>Importante:</strong> O professor usará o código para fazer login, não o email.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Criar Professor
                            </button>
                            <a href="<?php echo $app->url('/admin-escola/professores'); ?>" class="btn btn-outline-secondary">
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
// Validação de código do professor
document.getElementById('codigo_prof').addEventListener('input', function() {
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

// Atualizar preview do código
document.getElementById('codigo_prof').addEventListener('input', function() {
    const preview = document.querySelector('.alert-info li:first-child strong');
    if (preview) {
        preview.textContent = this.value || 'PROF001';
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>
