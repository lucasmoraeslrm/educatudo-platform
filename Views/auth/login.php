<?php
$title = $title ?? 'Login - Educatudo';
$currentSchool = $currentSchool ?? null;
$basePath = $basePath ?? '';
ob_start();
?>

<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">Educatudo</h2>
                        <?php if ($currentSchool): ?>
                        <p class="text-muted"><?php echo ucfirst($currentSchool); ?></p>
                        <?php endif; ?>
                        <p class="text-muted">Faça login em sua conta</p>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo $app->url('/login'); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="mb-3">
                            <label for="login" class="form-label">Login</label>
                            <input type="text" class="form-control" id="login" name="login" 
                                   value="<?php echo htmlspecialchars($login ?? ''); ?>" 
                                   placeholder="Email, RA ou Código do Professor" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <?php if ($currentSchool): ?>
                                Login específico para <?php echo ucfirst($currentSchool); ?>
                            <?php else: ?>
                                Sistema Educatudo Platform
                            <?php endif; ?>
                        </small>
                        <br>
                        <a href="<?php echo $app->url('/'); ?>" class="text-decoration-none">
                            <small>Ver página inicial</small>
                        </a>
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
