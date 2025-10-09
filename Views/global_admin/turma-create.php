<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Nova Turma - Educatudo'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <main>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Nova Turma</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- Mensagens de erro -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro ao criar turma</h5>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações da Turma</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/turmas'); ?>">
                        <div class="row">
                            <!-- Nome da Turma -->
                            <div class="col-md-3 mb-3">
                                <label for="nome" class="form-label">Turma <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" name="nome" required
                                       value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                                       placeholder="Ex: A, B, C...">
                                <small class="form-text text-muted">Identificador da turma</small>
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Série -->
                            <div class="col-md-5 mb-3">
                                <label for="serie" class="form-label">Série <span class="text-danger">*</span></label>
                                <select class="form-select <?php echo isset($errors['serie']) ? 'is-invalid' : ''; ?>" 
                                        id="serie" name="serie" required>
                                    <option value="">Selecione...</option>
                                    <option value="1º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '1º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>1º Ano do Ensino Fundamental</option>
                                    <option value="2º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '2º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>2º Ano do Ensino Fundamental</option>
                                    <option value="3º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '3º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>3º Ano do Ensino Fundamental</option>
                                    <option value="4º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '4º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>4º Ano do Ensino Fundamental</option>
                                    <option value="5º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '5º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>5º Ano do Ensino Fundamental</option>
                                    <option value="6º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '6º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>6º Ano do Ensino Fundamental</option>
                                    <option value="7º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '7º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>7º Ano do Ensino Fundamental</option>
                                    <option value="8º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '8º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>8º Ano do Ensino Fundamental</option>
                                    <option value="9º Ano do Ensino Fundamental" <?php echo ($_POST['serie'] ?? '') == '9º Ano do Ensino Fundamental' ? 'selected' : ''; ?>>9º Ano do Ensino Fundamental</option>
                                    <option value="1º Ano do Ensino Médio" <?php echo ($_POST['serie'] ?? '') == '1º Ano do Ensino Médio' ? 'selected' : ''; ?>>1º Ano do Ensino Médio</option>
                                    <option value="2º Ano do Ensino Médio" <?php echo ($_POST['serie'] ?? '') == '2º Ano do Ensino Médio' ? 'selected' : ''; ?>>2º Ano do Ensino Médio</option>
                                    <option value="3º Ano do Ensino Médio" <?php echo ($_POST['serie'] ?? '') == '3º Ano do Ensino Médio' ? 'selected' : ''; ?>>3º Ano do Ensino Médio</option>
                                </select>
                                <?php if (isset($errors['serie'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['serie']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Ano Letivo -->
                            <div class="col-md-2 mb-3">
                                <label for="ano_letivo" class="form-label">Ano Letivo <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php echo isset($errors['ano_letivo']) ? 'is-invalid' : ''; ?>" 
                                       id="ano_letivo" name="ano_letivo" required
                                       value="<?php echo htmlspecialchars($_POST['ano_letivo'] ?? date('Y')); ?>"
                                       min="2000" max="2100">
                                <?php if (isset($errors['ano_letivo'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['ano_letivo']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Período -->
                            <div class="col-md-2 mb-3">
                                <label for="periodo" class="form-label">Período</label>
                                <select class="form-select" id="periodo" name="periodo">
                                    <option value="">Selecione...</option>
                                    <option value="Matutino" <?php echo ($_POST['periodo'] ?? '') == 'Matutino' ? 'selected' : ''; ?>>Matutino</option>
                                    <option value="Vespertino" <?php echo ($_POST['periodo'] ?? '') == 'Vespertino' ? 'selected' : ''; ?>>Vespertino</option>
                                    <option value="Noturno" <?php echo ($_POST['periodo'] ?? '') == 'Noturno' ? 'selected' : ''; ?>>Noturno</option>
                                    <option value="Integral" <?php echo ($_POST['periodo'] ?? '') == 'Integral' ? 'selected' : ''; ?>>Integral</option>
                                </select>
                                <small class="form-text text-muted">Opcional</small>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Criar Turma
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

