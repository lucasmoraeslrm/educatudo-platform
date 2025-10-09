<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Novo Pai/Responsável - Educatudo'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <main>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Novo Pai/Responsável</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- Mensagens de erro -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro ao criar pai/responsável</h5>
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
                    <h5 class="card-title mb-0">Informações do Responsável</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/pais'); ?>">
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

                            <!-- CPF -->
                            <div class="col-md-4 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" 
                                       id="cpf" name="cpf"
                                       value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>"
                                       placeholder="000.000.000-00">
                                <small class="form-text text-muted">Opcional</small>
                            </div>

                            <!-- Telefone -->
                            <div class="col-md-4 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" 
                                       id="telefone" name="telefone"
                                       value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>"
                                       placeholder="(00) 00000-0000">
                                <small class="form-text text-muted">Opcional</small>
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
                        </div>

                        <!-- Alunos (Filhos) -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-people-fill"></i> Alunos Vinculados (Filhos)
                                </label>
                                <div class="card">
                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                        <?php if (empty($alunos)): ?>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-info-circle"></i> Nenhum aluno cadastrado nesta escola.
                                            </p>
                                        <?php else: ?>
                                            <small class="text-muted d-block mb-2">
                                                Selecione os alunos que este responsável irá acompanhar:
                                            </small>
                                            <div class="row">
                                                <?php foreach ($alunos as $aluno): ?>
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="alunos[]" value="<?php echo $aluno['id']; ?>" 
                                                                   id="aluno_<?php echo $aluno['id']; ?>"
                                                                   <?php echo in_array($aluno['id'], $_POST['alunos'] ?? []) ? 'checked' : ''; ?>>
                                                            <label class="form-check-label" for="aluno_<?php echo $aluno['id']; ?>">
                                                                <strong><?php echo htmlspecialchars($aluno['nome']); ?></strong>
                                                                <br>
                                                                <small class="text-muted">
                                                                    RA: <?php echo htmlspecialchars($aluno['ra']); ?>
                                                                    <?php if (!empty($aluno['serie'])): ?>
                                                                        | Série: <?php echo htmlspecialchars($aluno['serie']); ?>
                                                                    <?php endif; ?>
                                                                </small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="bi bi-lightbulb"></i> Você pode selecionar múltiplos alunos
                                </small>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Criar Responsável
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

