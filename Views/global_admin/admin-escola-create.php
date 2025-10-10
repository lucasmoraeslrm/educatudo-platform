<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Novo Administrador - Educatudo'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <main>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Novo Administrador da Escola</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $app->url('/admin'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $app->url('/admin/escolas'); ?>">Escolas</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>"><?php echo htmlspecialchars($escola['nome']); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Novo Administrador</li>
                </ol>
            </nav>

            <!-- Mensagens de erro -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro ao criar administrador</h5>
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
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check"></i> Informações do Administrador
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/usuarios'); ?>">
                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-6 mb-3">
                                <label for="nome" class="form-label">
                                    Nome Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" 
                                       name="nome" 
                                       required
                                       value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                                       placeholder="Ex: João Silva">
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" 
                                       name="email" 
                                       required
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       placeholder="admin@escola.com.br">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                                <small class="form-text text-muted">
                                    Este email será usado para fazer login no sistema
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Senha -->
                            <div class="col-md-6 mb-3">
                                <label for="senha" class="form-label">
                                    Senha <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control <?php echo isset($errors['senha']) ? 'is-invalid' : ''; ?>" 
                                       id="senha" 
                                       name="senha" 
                                       required
                                       minlength="6"
                                       placeholder="Mínimo 6 caracteres">
                                <?php if (isset($errors['senha'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['senha']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Confirmar Senha -->
                            <div class="col-md-6 mb-3">
                                <label for="senha_confirmacao" class="form-label">
                                    Confirmar Senha <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="senha_confirmacao" 
                                       name="senha_confirmacao" 
                                       required
                                       minlength="6"
                                       placeholder="Digite a senha novamente">
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Permissões do Administrador da Escola:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Gerenciar professores, alunos e pais da escola</li>
                                <li>Criar e editar turmas e matérias</li>
                                <li>Visualizar relatórios e estatísticas</li>
                                <li>Gerenciar jornadas e exercícios</li>
                            </ul>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-check-circle"></i> Criar Administrador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validar se as senhas são iguais
        document.querySelector('form').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const senhaConfirmacao = document.getElementById('senha_confirmacao').value;
            
            if (senha !== senhaConfirmacao) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                document.getElementById('senha_confirmacao').focus();
            }
        });
    </script>
</body>
</html>

