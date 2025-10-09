<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Nova Matéria - Educatudo'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-4">
        <main>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Nova Matéria</h1>
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
                    <li class="breadcrumb-item active" aria-current="page">Nova Matéria</li>
                </ol>
            </nav>

            <!-- Mensagens de erro -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro ao criar matéria</h5>
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
                    <h5 class="card-title mb-0">Informações da Matéria</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/materias'); ?>">
                        <div class="row">
                            <!-- Nome -->
                            <div class="col-md-8 mb-3">
                                <label for="nome" class="form-label">Nome da Matéria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['nome']) ? 'is-invalid' : ''; ?>" 
                                       id="nome" name="nome" required
                                       value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>"
                                       placeholder="Ex: Matemática, Português, História...">
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Professor -->
                            <div class="col-md-4 mb-3">
                                <label for="professor_id" class="form-label">Professor Responsável</label>
                                <select class="form-select" id="professor_id" name="professor_id">
                                    <option value="">Selecione...</option>
                                    <?php if (!empty($professores)): ?>
                                        <?php foreach ($professores as $prof): ?>
                                            <option value="<?php echo $prof['id']; ?>" 
                                                    <?php echo (isset($_POST['professor_id']) && $_POST['professor_id'] == $prof['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($prof['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
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
                                <i class="bi bi-check-circle"></i> Criar Matéria
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

