<?php
$title = $title ?? 'Editar Escola - Admin Global';
$escola = $escola ?? null;
$errors = $errors ?? [];
$old = $old ?? [];

if (!$escola) {
    header('Location: ' . $app->url('/admin/escolas'));
    exit;
}

// Usar dados da escola se não houver dados do formulário
$data = array_merge($escola, $old);
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Editar Escola</h1>
                    <p class="text-muted mb-0">Modifique as informações de: <strong><?php echo htmlspecialchars($escola['nome']); ?></strong></p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-outline-info me-2">
                        <i class="bi bi-eye"></i> Ver Detalhes
                    </a>
                    <a href="<?php echo $app->url('/admin/escolas'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT">
        
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
                                <label for="nome" class="form-label">Nome da Escola *</label>
                                <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" name="nome" value="<?php echo htmlspecialchars($data['nome']); ?>" required>
                                <?php if (isset($errors['nome'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="subdominio" class="form-label">Subdomínio *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php echo isset($errors['subdominio']) ? 'is-invalid' : ''; ?>" 
                                           id="subdominio" name="subdominio" value="<?php echo htmlspecialchars($data['subdominio']); ?>" required>
                                    <span class="input-group-text">.educatudo.com</span>
                                </div>
                                <?php if (isset($errors['subdominio'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['subdominio']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Ex: colag → colag.educatudo.com</small>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <input type="text" class="form-control <?php echo isset($errors['cnpj']) ? 'is-invalid' : ''; ?>" 
                                       id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($data['cnpj'] ?? ''); ?>">
                                <?php if (isset($errors['cnpj'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['cnpj']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="plano" class="form-label">Plano *</label>
                                <select class="form-select <?php echo isset($errors['plano']) ? 'is-invalid' : ''; ?>" 
                                        id="plano" name="plano" required>
                                    <option value="">Selecione um plano</option>
                                    <option value="basico" <?php echo ($data['plano'] ?? '') === 'basico' ? 'selected' : ''; ?>>Básico</option>
                                    <option value="avancado" <?php echo ($data['plano'] ?? '') === 'avancado' ? 'selected' : ''; ?>>Avançado</option>
                                    <option value="premium" <?php echo ($data['plano'] ?? '') === 'premium' ? 'selected' : ''; ?>>Premium</option>
                                </select>
                                <?php if (isset($errors['plano'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['plano']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">
                                <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control <?php echo isset($errors['telefone']) ? 'is-invalid' : ''; ?>" 
                                       id="telefone" name="telefone" value="<?php echo htmlspecialchars($data['telefone'] ?? ''); ?>">
                                <?php if (isset($errors['telefone'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['telefone']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <textarea class="form-control <?php echo isset($errors['endereco']) ? 'is-invalid' : ''; ?>" 
                                      id="endereco" name="endereco" rows="3"><?php echo htmlspecialchars($data['endereco'] ?? ''); ?></textarea>
                            <?php if (isset($errors['endereco'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['endereco']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Configurações Visuais -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Configurações Visuais</h5>
                        <span class="badge bg-info">Opcional</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="cor_primaria" class="form-label">Cor Primária</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" 
                                           id="cor_primaria" name="cor_primaria" 
                                           value="<?php echo htmlspecialchars($data['cor_primaria'] ?? '#007bff'); ?>">
                                    <input type="text" class="form-control" 
                                           id="cor_primaria_text" 
                                           value="<?php echo htmlspecialchars($data['cor_primaria'] ?? '#007bff'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="cor_secundaria" class="form-label">Cor Secundária</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" 
                                           id="cor_secundaria" name="cor_secundaria" 
                                           value="<?php echo htmlspecialchars($data['cor_secundaria'] ?? '#6c757d'); ?>">
                                    <input type="text" class="form-control" 
                                           id="cor_secundaria_text" 
                                           value="<?php echo htmlspecialchars($data['cor_secundaria'] ?? '#6c757d'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="logo" class="form-label">Logo da Escola</label>
                                <?php if (!empty($data['logo'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars($data['logo']); ?>" 
                                         alt="Logo atual" class="img-thumbnail" style="max-height: 60px;">
                                    <small class="text-muted d-block">Logo atual</small>
                                </div>
                                <?php endif; ?>
                                <input type="file" class="form-control <?php echo isset($errors['logo']) ? 'is-invalid' : ''; ?>" 
                                       id="logo" name="logo" accept="image/*">
                                <?php if (isset($errors['logo'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['logo']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
                            </div>
                            <div class="col-md-6">
                                <label for="background" class="form-label">Imagem de Fundo</label>
                                <?php if (!empty($data['background'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo htmlspecialchars($data['background']); ?>" 
                                         alt="Background atual" class="img-thumbnail" style="max-height: 60px;">
                                    <small class="text-muted d-block">Background atual</small>
                                </div>
                                <?php endif; ?>
                                <input type="file" class="form-control <?php echo isset($errors['background']) ? 'is-invalid' : ''; ?>" 
                                       id="background" name="background" accept="image/*">
                                <?php if (isset($errors['background'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['background']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">Imagem de fundo personalizada para a escola</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações Adicionais -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Configurações</h5>
                        <span class="badge bg-secondary">Avançado</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ativa" name="ativa" value="1" 
                                       <?php echo ($data['ativa'] ?? '1') === '1' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="ativa">
                                    Escola Ativa
                                </label>
                            </div>
                            <small class="form-text text-muted">Escolas inativas não podem ser acessadas</small>
                        </div>

                        <div class="mb-3">
                            <label for="configuracoes" class="form-label">Configurações Adicionais</label>
                            <textarea class="form-control" id="configuracoes" name="configuracoes" rows="4" 
                                      placeholder='{"modulos": ["exercicios", "redacoes"], "limite_alunos": 500}'><?php echo htmlspecialchars($data['configuracoes'] ?? ''); ?></textarea>
                            <small class="form-text text-muted">JSON com configurações específicas da escola</small>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                                      placeholder="Observações internas sobre a escola..."><?php echo htmlspecialchars($data['observacoes'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar Alterações
                            </button>
                            <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i> Ver Detalhes
                            </a>
                            <a href="<?php echo $app->url('/admin/escolas'); ?>" class="btn btn-outline-secondary">
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
// Sincronizar campos de cor
document.getElementById('cor_primaria').addEventListener('input', function() {
    document.getElementById('cor_primaria_text').value = this.value;
});

document.getElementById('cor_primaria_text').addEventListener('input', function() {
    document.getElementById('cor_primaria').value = this.value;
});

document.getElementById('cor_secundaria').addEventListener('input', function() {
    document.getElementById('cor_secundaria_text').value = this.value;
});

document.getElementById('cor_secundaria_text').addEventListener('input', function() {
    document.getElementById('cor_secundaria').value = this.value;
});

// Validação de CNPJ
document.getElementById('cnpj').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 14) {
        value = value.substring(0, 14);
    }
    this.value = value;
});

// Validação de telefone
document.getElementById('telefone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    this.value = value;
});

// Validação de subdomínio
document.getElementById('subdominio').addEventListener('input', function() {
    this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
});

// Preview das imagens
document.getElementById('logo').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Nova logo selecionada:', file.name);
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('background').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Novo background selecionado:', file.name);
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>