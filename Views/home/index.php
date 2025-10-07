<?php
$title = $title ?? 'Home - Educatudo';
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white rounded p-5 mb-4">
                <h1 class="display-4">Bem-vindo ao Educatudo!</h1>
                <p class="lead">A plataforma educacional completa para escolas, professores, alunos e pais.</p>
                <hr class="my-4">
                <p>Gerencie jornadas de aprendizado, exercícios, redações e muito mais.</p>
                <a class="btn btn-light btn-lg" href="<?php echo $basePath; ?>/login" role="button">Fazer Login</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-mortarboard text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Para Professores</h5>
                    <p class="card-text">Crie jornadas personalizadas, exercícios e acompanhe o progresso dos alunos.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Para Alunos</h5>
                    <p class="card-text">Acesse exercícios, converse com a IA Tudinha e pratique redações.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-building text-info" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-3">Para Escolas</h5>
                    <p class="card-text">Gerencie turmas, professores e acompanhe o desempenho geral.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recursos da Plataforma</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success"></i> Jornadas de Aprendizado Personalizadas</li>
                                <li><i class="bi bi-check-circle text-success"></i> Exercícios com IA</li>
                                <li><i class="bi bi-check-circle text-success"></i> Chat Tudinha (IA Educacional)</li>
                                <li><i class="bi bi-check-circle text-success"></i> Correção Automática de Redações</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success"></i> Relatórios Detalhados</li>
                                <li><i class="bi bi-check-circle text-success"></i> Acompanhamento de Progresso</li>
                                <li><i class="bi bi-check-circle text-success"></i> Jogos Educativos</li>
                                <li><i class="bi bi-check-circle text-success"></i> Preparação para Vestibulares</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>