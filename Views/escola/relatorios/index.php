<?php
$title = $title ?? 'Relatórios - Escola';
$user = $user ?? null;
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2">Relatórios</h1>
                    <p class="text-muted">Acesse relatórios e análises da escola</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder para funcionalidade futura -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Relatórios da Escola</h4>
                    <p class="text-muted">Esta funcionalidade será implementada em breve.</p>
                    <p class="text-muted">Aqui você poderá gerar relatórios de desempenho, frequência e outras métricas importantes.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/app.php';
?>
