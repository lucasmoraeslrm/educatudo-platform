<?php include __DIR__ . '/../layouts/app.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-1 text-danger">403</h1>
                    <h2 class="mb-4">Acesso Negado</h2>
                    <p class="lead">Você não tem permissão para acessar esta página.</p>
                    <p class="text-muted">Entre em contato com o administrador se você acredita que isso é um erro.</p>
                    
                    <div class="mt-4">
                        <a href="/login" class="btn btn-primary me-2">Voltar ao Login</a>
                        <a href="/" class="btn btn-outline-secondary">Página Inicial</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

