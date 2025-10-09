<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Editar Pai/Responsável - Educatudo'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <main>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Editar Pai/Responsável</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- Mensagens de erro -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro ao atualizar pai/responsável</h5>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informações do Responsável</h5>
                    <span class="badge bg-<?php echo $pai['ativo'] ? 'success' : 'danger'; ?>">
                        <?php echo $pai['ativo'] ? 'Ativo' : 'Inativo'; ?>
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/pais/' . $pai['id']); ?>">
                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" name="nome" required
                                       value="<?php echo htmlspecialchars($_POST['nome'] ?? $usuario['nome'] ?? ''); ?>">
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <small class="text-muted">(opcional)</small></label>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? $usuario['email'] ?? ''); ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- CPF -->
                            <div class="col-md-4 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" 
                                       id="cpf" name="cpf"
                                       value="<?php echo htmlspecialchars($_POST['cpf'] ?? $pai['cpf'] ?? ''); ?>"
                                       placeholder="000.000.000-00">
                                <small class="form-text text-muted">Opcional</small>
                            </div>

                            <!-- Telefone -->
                            <div class="col-md-4 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" 
                                       id="telefone" name="telefone"
                                       value="<?php echo htmlspecialchars($_POST['telefone'] ?? $pai['telefone'] ?? ''); ?>"
                                       placeholder="(00) 00000-0000">
                                <small class="form-text text-muted">Opcional</small>
                            </div>

                            <!-- Senha -->
                            <div class="col-md-4 mb-3">
                                <label for="senha" class="form-label">Nova Senha <small class="text-muted">(deixe em branco para manter)</small></label>
                                <input type="password" class="form-control <?php echo isset($errors['senha']) ? 'is-invalid' : ''; ?>" 
                                       id="senha" name="senha" minlength="6">
                                <small class="form-text text-muted">Mínimo de 6 caracteres</small>
                                <?php if (isset($errors['senha'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['senha']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Status Ativo -->
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                                           <?php echo (isset($_POST['ativo']) || (!isset($_POST['ativo']) && $pai['ativo'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="ativo">
                                        Responsável Ativo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-danger" onclick="confirmarExclusao()">
                                <i class="bi bi-trash"></i> Excluir Responsável
                            </button>
                            <div class="d-flex gap-2">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Form de exclusão (oculto) -->
                    <form id="formExcluir" method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/pais/' . $pai['id']); ?>" style="display: none;">
                        <input type="hidden" name="_method" value="DELETE">
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarExclusao() {
            if (confirm('Tem certeza que deseja excluir este responsável? Esta ação não pode ser desfeita.')) {
                document.getElementById('formExcluir').submit();
            }
        }
    </script>
</body>
</html>

