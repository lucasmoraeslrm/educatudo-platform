<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Novo Aluno - Educatudo'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <main>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Novo Aluno</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- Mensagens de erro -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro ao criar aluno</h5>
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
                    <h5 class="card-title mb-0">Informações do Aluno</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/alunos'); ?>">
                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" name="nome" required
                                       value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <small class="text-muted">(opcional)</small></label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- RA -->
                            <div class="col-md-4 mb-3">
                                <label for="ra" class="form-label">RA (Registro do Aluno) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['ra']) ? 'is-invalid' : ''; ?>" 
                                       id="ra" name="ra" required
                                       value="<?php echo htmlspecialchars($_POST['ra'] ?? ''); ?>"
                                       placeholder="Ex: RA001">
                                <?php if (isset($errors['ra'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['ra']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Senha -->
                            <div class="col-md-4 mb-3">
                                <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?php echo isset($errors['senha']) ? 'is-invalid' : ''; ?>" 
                                       id="senha" name="senha" required minlength="6">
                                <small class="form-text text-muted">Mínimo de 6 caracteres</small>
                                <?php if (isset($errors['senha'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['senha']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Data de Nascimento -->
                            <div class="col-md-4 mb-3">
                                <label for="data_nasc" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" 
                                       id="data_nasc" name="data_nasc"
                                       value="<?php echo htmlspecialchars($_POST['data_nasc'] ?? ''); ?>">
                            </div>

                            <!-- Série -->
                            <div class="col-md-6 mb-3">
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

                            <!-- Turma -->
                            <div class="col-md-6 mb-3">
                                <label for="turma_id" class="form-label">Turma</label>
                                <select class="form-select" id="turma_id" name="turma_id">
                                    <option value="">Selecione...</option>
                                    <?php if (!empty($turmas)): ?>
                                        <?php foreach ($turmas as $turma): ?>
                                            <option value="<?php echo $turma['id']; ?>" 
                                                    <?php echo (isset($_POST['turma_id']) && $_POST['turma_id'] == $turma['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($turma['serie'] . ' - Turma ' . $turma['nome'] . ' (' . $turma['ano_letivo'] . ')'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted">Opcional - pode ser definido depois</small>
                            </div>

                            <!-- Responsável -->
                            <div class="col-md-12 mb-3">
                                <label for="responsavel_id" class="form-label">Responsável (Pai/Mãe)</label>
                                <select class="form-select" id="responsavel_id" name="responsavel_id">
                                    <option value="">Selecione...</option>
                                    <?php if (!empty($pais)): ?>
                                        <?php foreach ($pais as $responsavel): ?>
                                            <option value="<?php echo $responsavel['usuario_id']; ?>" 
                                                    <?php echo (isset($_POST['responsavel_id']) && $_POST['responsavel_id'] == $responsavel['usuario_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($responsavel['nome']); ?>
                                                <?php if (!empty($responsavel['telefone'])): ?>
                                                    - <?php echo htmlspecialchars($responsavel['telefone']); ?>
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Nenhum responsável cadastrado</option>
                                    <?php endif; ?>
                                </select>
                                <small class="form-text text-muted">Opcional - pode ser definido depois</small>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Criar Aluno
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

