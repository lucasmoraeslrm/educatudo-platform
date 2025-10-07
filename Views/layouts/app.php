<?php
$title = $title ?? 'Educatudo Platform';
$currentSchool = $currentSchool ?? null;
$basePath = $basePath ?? $app->getBasePath();
ob_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $app->asset('css/style.css'); ?>" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
        }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0 !important;
            padding: 0 !important;
        }
        .navbar-brand { font-size: 1.5rem; font-weight: bold; }
        .card { transition: transform 0.2s ease-in-out; }
        .card:hover { transform: translateY(-2px); }
        .btn { border-radius: 0.375rem; font-weight: 500; }
        .error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-code { font-size: 8rem; font-weight: bold; color: var(--primary-color); line-height: 1; }
        
        /* SOLUÇÃO DEFINITIVA - Reset completo e padding uniforme */
        * {
            box-sizing: border-box;
        }
        
        .navbar,
        .navbar .container-fluid,
        footer,
        footer .container-fluid,
        main,
        main .container-fluid,
        .container-fluid {
            padding-left: 20px !important;
            padding-right: 20px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        /* Garantir que não há conflitos com Bootstrap */
        .navbar .container-fluid {
            max-width: none !important;
            width: 100% !important;
        }
        
        footer .container-fluid {
            max-width: none !important;
            width: 100% !important;
        }
        
        main .container-fluid {
            max-width: none !important;
            width: 100% !important;
        }
        
        /* Alinhamento específico para elementos flex */
        .d-flex.justify-content-between {
            align-items: center;
            width: 100%;
        }
        
        /* Garantir que cards tenham margem adequada */
        .card {
            margin-bottom: 1.5rem;
        }
        
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $app->url('/'); ?>">
                <?php echo $currentSchool ? $currentSchool . ' - ' : ''; ?>Educatudo
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($user) && $user): ?>
                <ul class="navbar-nav me-auto">
                    <?php if ($user['tipo'] === 'super_admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $app->url('/admin'); ?>">Admin Global</a>
                    </li>
                    <?php elseif ($user['tipo'] === 'admin_escola'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $app->url('/admin-escola'); ?>">Admin Escola</a>
                    </li>
                    <?php elseif ($user['tipo'] === 'professor'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $app->url('/professor'); ?>">Professor</a>
                    </li>
                    <?php elseif ($user['tipo'] === 'aluno'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $app->url('/aluno'); ?>">Aluno</a>
                    </li>
                    <?php elseif ($user['tipo'] === 'pai'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $app->url('/pais'); ?>">Pais</a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user['nome']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo $app->url('/logout'); ?>">Sair</a></li>
                        </ul>
                    </li>
                </ul>
                <?php else: ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $app->url('/login'); ?>">Login</a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5>Educatudo Platform</h5>
                    <p class="mb-0">Transformando a educação através da tecnologia.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Educatudo Platform. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
