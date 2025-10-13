<?php
$title = $title ?? 'Usuários/Adm - Escola';
$user = $user ?? null;
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2">Usuários/Adm</h1>
                    <p class="text-muted">Gerencie usuários e administradores da escola</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder para funcionalidade futura -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-gear" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Gestão de Usuários</h4>
                    <p class="text-muted">Esta funcionalidade será implementada em breve.</p>
                    <p class="text-muted">Aqui você poderá gerenciar todos os usuários da escola e suas permissões.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/app.php';
?>
