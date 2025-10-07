<?php
$title = $title ?? 'Detalhes da Escola - Admin Global';
$escola = $escola ?? null;
$estatisticas = $estatisticas ?? [];
$usuarios = $usuarios ?? [];
$alunos = $alunos ?? [];
$professores = $professores ?? [];
$pais = $pais ?? [];
$turmas = $turmas ?? [];
$materias = $materias ?? [];

if (!$escola) {
    header('Location: ' . $app->url('/admin/escolas'));
    exit;
}

ob_start();
?>

<div class="container-fluid">
    <!-- Header da Escola -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <?php if (!empty($escola['logo'])): ?>
                            <img src="<?php echo htmlspecialchars($escola['logo']); ?>" 
                                 alt="Logo" class="img-fluid rounded" style="max-height: 100px;">
                            <?php else: ?>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-building" style="font-size: 2.5rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h1 class="h2 mb-1"><?php echo htmlspecialchars($escola['nome']); ?></h1>
                            <p class="text-muted mb-2">
                                <i class="bi bi-globe me-1"></i>
                                <strong>Subdomínio:</strong> 
                                <code><?php echo htmlspecialchars($escola['subdominio']); ?>.educatudo.com</code>
                            </p>
                            <div class="d-flex gap-3">
                                <span class="badge bg-<?php 
                                    echo $escola['plano'] === 'premium' ? 'warning' : 
                                        ($escola['plano'] === 'avancado' ? 'info' : 'secondary'); 
                                ?> fs-6">
                                    <?php echo ucfirst($escola['plano'] ?? 'básico'); ?>
                                </span>
                                <span class="badge bg-<?php echo ($escola['ativa'] ?? true) ? 'success' : 'danger'; ?> fs-6">
                                    <?php echo ($escola['ativa'] ?? true) ? 'Ativa' : 'Inativa'; ?>
                                </span>
                                <span class="badge bg-secondary fs-6">
                                    ID: <?php echo $escola['id']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <div class="btn-group-vertical" role="group">
                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/edit'); ?>" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="<?php echo $app->url('/admin/escolas'); ?>" 
                                   class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['usuarios']['total'] ?? count($usuarios); ?></div>
                        <div class="label">Total de Usuários</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['alunos']['total'] ?? count($alunos); ?></div>
                        <div class="label">Alunos</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['professores']['total'] ?? count($professores); ?></div>
                        <div class="label">Professores</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['turmas']['total'] ?? count($turmas); ?></div>
                        <div class="label">Turmas</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-collection"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações da Escola -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações da Escola</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Dados Básicos</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nome:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['nome']); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>CNPJ:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['cnpj'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['email'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong></td>
                                    <td><?php echo htmlspecialchars($escola['telefone'] ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Configurações</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Plano:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $escola['plano'] === 'premium' ? 'warning' : 
                                                ($escola['plano'] === 'avancado' ? 'info' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($escola['plano'] ?? 'básico'); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($escola['ativa'] ?? true) ? 'success' : 'danger'; ?>">
                                            <?php echo ($escola['ativa'] ?? true) ? 'Ativa' : 'Inativa'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Criada em:</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($escola['created_at'] ?? 'now')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Última atualização:</strong></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($escola['updated_at'] ?? 'now')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($escola['endereco'])): ?>
                    <div class="mt-3">
                        <h6>Endereço</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($escola['endereco'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/edit'); ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Editar Escola
                        </a>
                        <button class="btn btn-outline-<?php echo ($escola['ativa'] ?? true) ? 'danger' : 'success'; ?>" 
                                onclick="toggleStatus(<?php echo $escola['id']; ?>, <?php echo ($escola['ativa'] ?? true) ? 'false' : 'true'; ?>)">
                            <i class="bi bi-<?php echo ($escola['ativa'] ?? true) ? 'ban' : 'check'; ?>"></i>
                            <?php echo ($escola['ativa'] ?? true) ? 'Desativar' : 'Ativar'; ?>
                        </button>
                        <a href="<?php echo $app->url('/admin/escolas'); ?>" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuários da Escola -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Usuários da Escola</h5>
                    <div class="d-flex gap-2">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary active" onclick="filtrarUsuarios('todos')">Todos</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="filtrarUsuarios('aluno')">Alunos</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="filtrarUsuarios('professor')">Professores</button>
                            <button class="btn btn-sm btn-outline-primary" onclick="filtrarUsuarios('pai')">Pais</button>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="<?php echo $app->url('/admin-escola/professores/create'); ?>" class="btn btn-sm btn-success" title="Novo Professor">
                                <i class="bi bi-person-badge"></i>
                            </a>
                            <a href="<?php echo $app->url('/admin-escola/alunos/create'); ?>" class="btn btn-sm btn-success" title="Novo Aluno">
                                <i class="bi bi-person-check"></i>
                            </a>
                            <a href="<?php echo $app->url('/admin-escola/pais/create'); ?>" class="btn btn-sm btn-success" title="Novo Pai">
                                <i class="bi bi-people"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuário</th>
                                    <th>Tipo</th>
                                    <th>Login</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Último Login</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($usuarios)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-people" style="font-size: 2rem; opacity: 0.3;"></i>
                                        <br><br>
                                        <h6>Nenhum usuário encontrado</h6>
                                        <p class="mb-0">Esta escola ainda não possui usuários cadastrados.</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($usuarios as $usuario): ?>
                                    <tr data-tipo="<?php echo $usuario['tipo']; ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-<?php 
                                                    echo $usuario['tipo'] === 'admin_escola' ? 'danger' : 
                                                        ($usuario['tipo'] === 'professor' ? 'info' : 
                                                            ($usuario['tipo'] === 'aluno' ? 'success' : 'warning')); 
                                                ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-<?php 
                                                        echo $usuario['tipo'] === 'admin_escola' ? 'shield-check' : 
                                                            ($usuario['tipo'] === 'professor' ? 'person-badge' : 
                                                                ($usuario['tipo'] === 'aluno' ? 'person-check' : 'people')); 
                                                    ?>"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>
                                                    <br><small class="text-muted">ID: <?php echo $usuario['id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $usuario['tipo'] === 'admin_escola' ? 'danger' : 
                                                    ($usuario['tipo'] === 'professor' ? 'info' : 
                                                        ($usuario['tipo'] === 'aluno' ? 'success' : 'warning')); 
                                            ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $usuario['tipo'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            // Mostrar o método de login baseado no tipo
                                            if ($usuario['tipo'] === 'professor') {
                                                // Buscar código do professor
                                                $professorModel = new \Educatudo\Models\Professor($db);
                                                $professor = $professorModel->findByUsuarioId($usuario['id']);
                                                echo '<code>' . htmlspecialchars($professor['codigo_prof'] ?? 'N/A') . '</code>';
                                            } elseif ($usuario['tipo'] === 'aluno') {
                                                // Buscar RA do aluno
                                                $alunoModel = new \Educatudo\Models\Aluno($db);
                                                $aluno = $alunoModel->findByUsuarioId($usuario['id']);
                                                echo '<code>' . htmlspecialchars($aluno['ra'] ?? 'N/A') . '</code>';
                                            } else {
                                                // Admin e Pais usam email
                                                echo '<code>' . htmlspecialchars($usuario['email'] ?? 'N/A') . '</code>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($usuario['email'] ?? 'N/A'); ?></td>
                                        <td><span class="badge bg-success">Ativo</span></td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo isset($usuario['ultimo_login']) ? date('d/m/Y H:i', strtotime($usuario['ultimo_login'])) : 'Nunca'; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if ($usuario['tipo'] === 'professor'): ?>
                                                    <a href="<?php echo $app->url('/admin-escola/professores/' . $usuario['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Editar Professor">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php elseif ($usuario['tipo'] === 'aluno'): ?>
                                                    <a href="<?php echo $app->url('/admin-escola/alunos/' . $usuario['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Editar Aluno">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php elseif ($usuario['tipo'] === 'pai'): ?>
                                                    <a href="<?php echo $app->url('/admin-escola/pais/' . $usuario['id'] . '/edit'); ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Editar Pai">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Admin não pode ser editado">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-sm btn-outline-info" title="Ver detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Botões de Criação -->
            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Adicionar Novos Usuários:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?php echo $app->url('/admin-escola/professores/create'); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-person-badge"></i> Novo Professor
                            </a>
                            <a href="<?php echo $app->url('/admin-escola/alunos/create'); ?>" class="btn btn-outline-success">
                                <i class="bi bi-person-check"></i> Novo Aluno
                            </a>
                            <a href="<?php echo $app->url('/admin-escola/pais/create'); ?>" class="btn btn-outline-warning">
                                <i class="bi bi-people"></i> Novo Pai/Responsável
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(escolaId, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    if (confirm(`Tem certeza que deseja ${acao} esta escola?`)) {
        fetch(`<?php echo $app->url('/admin/escolas/' . $escolaId . '/toggle-status'); ?>`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ativa: novoStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar solicitação');
        });
    }
}

function filtrarUsuarios(tipo) {
    // Atualizar botões ativos
    document.querySelectorAll('.btn-group button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Encontrar o botão correto e ativá-lo
    const botoes = document.querySelectorAll('.btn-group button');
    botoes.forEach(btn => {
        if (btn.textContent.trim().toLowerCase() === tipo.toLowerCase() || 
            (tipo === 'todos' && btn.textContent.trim().toLowerCase() === 'todos')) {
            btn.classList.add('active');
        }
    });
    
    // Filtrar linhas da tabela
    const linhas = document.querySelectorAll('tbody tr');
    
    linhas.forEach(linha => {
        if (linha.querySelector('td[colspan]')) return; // Pular linha de "nenhum usuário"
        
        const tipoUsuario = linha.getAttribute('data-tipo');
        
        if (tipo === 'todos' || tipoUsuario === tipo) {
            linha.style.display = '';
        } else {
            linha.style.display = 'none';
        }
    });
    
    // Mostrar/esconder mensagem de "nenhum usuário" se necessário
    const mensagemVazia = document.querySelector('tbody tr td[colspan]');
    if (mensagemVazia) {
        const linhasVisiveis = Array.from(linhas).filter(linha => 
            linha.style.display !== 'none' && !linha.querySelector('td[colspan]')
        );
        
        if (linhasVisiveis.length === 0 && tipo !== 'todos') {
            mensagemVazia.parentElement.style.display = '';
            mensagemVazia.textContent = `Nenhum ${tipo} encontrado`;
        } else if (linhasVisiveis.length === 0) {
            mensagemVazia.parentElement.style.display = '';
            mensagemVazia.textContent = 'Nenhum usuário encontrado';
        } else {
            mensagemVazia.parentElement.style.display = 'none';
        }
    }
}

// Adicionar tooltips para os botões de ação
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips do Bootstrap se disponível
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>