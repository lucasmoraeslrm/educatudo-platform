<?php
$title = $title ?? 'Usuários - Admin Global';
$usuarios = $usuarios ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Usuários Globais</h1>
                    <p class="text-muted mb-0">Gerencie usuários com acesso global à plataforma</p>
                </div>
                <div>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Usuário
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuários Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lista de Usuários</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuário</th>
                                    <th>Email</th>
                                    <th>Tipo</th>
                                    <th>Escola</th>
                                    <th>Status</th>
                                    <th>Último Login</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>
                                                <br><small class="text-muted">ID: <?php echo $usuario['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['email'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $usuario['tipo'] === 'super_admin' ? 'danger' : 'secondary'; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $usuario['tipo'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($usuario['escola_id']): ?>
                                            <span class="text-primary">Escola #<?php echo $usuario['escola_id']; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Global</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-success">Ativo</span></td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo isset($usuario['ultimo_login']) ? date('d/m/Y H:i', strtotime($usuario['ultimo_login'])) : 'Nunca'; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>


