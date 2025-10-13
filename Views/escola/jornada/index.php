<?php
$title = $title ?? 'Jornada do Aluno - Escola';
$user = $user ?? null;
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2">Jornada do Aluno</h1>
                    <p class="text-muted">Acompanhe as jornadas de aprendizado dos alunos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder para funcionalidade futura -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-journal-bookmark" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Jornadas dos Alunos</h4>
                    <p class="text-muted">Esta funcionalidade será implementada em breve.</p>
                    <p class="text-muted">Aqui você poderá acompanhar o progresso das jornadas de aprendizado de cada aluno.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/app.php';
?>
