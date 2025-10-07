<?php
$title = $title ?? 'Escolas - Admin Global';
$escolas = $escolas ?? [];
$estatisticas = $estatisticas ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Gerenciar Escolas</h1>
                    <p class="text-muted mb-0">Administre todas as escolas da plataforma Educatudo</p>
                </div>
                <div>
                    <a href="<?php echo $app->url('/admin/escolas/create'); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nova Escola
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['total'] ?? count($escolas); ?></div>
                        <div class="label">Total de Escolas</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['ativas'] ?? count(array_filter($escolas, fn($e) => $e['ativa'] ?? true)); ?></div>
                        <div class="label">Escolas Ativas</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['premium'] ?? count(array_filter($escolas, fn($e) => ($e['plano'] ?? '') === 'premium')); ?></div>
                        <div class="label">Planos Premium</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $estatisticas['usuarios_total'] ?? '0'; ?></div>
                        <div class="label">Total de Usuários</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Filtros e Busca</h5>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="limparFiltros()">
                        <i class="bi bi-arrow-clockwise"></i> Limpar
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="filtro-plano" class="form-label">Plano</label>
                            <select class="form-select" id="filtro-plano">
                                <option value="">Todos os planos</option>
                                <option value="basico">Básico</option>
                                <option value="avancado">Avançado</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="filtro-status" class="form-label">Status</label>
                            <select class="form-select" id="filtro-status">
                                <option value="">Todos</option>
                                <option value="1">Ativas</option>
                                <option value="0">Inativas</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-8">
                            <label for="filtro-busca" class="form-label">Buscar</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="filtro-busca" placeholder="Nome, CNPJ ou subdomínio...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" onclick="aplicarFiltros()">
                                <i class="bi bi-funnel"></i> Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Escolas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Escolas</h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary active">Todas</button>
                        <button class="btn btn-sm btn-outline-primary">Ativas</button>
                        <button class="btn btn-sm btn-outline-primary">Premium</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="tabelaEscolas">
                            <thead class="table-light">
                                <tr>
                                    <th>Escola</th>
                                    <th>Subdomínio</th>
                                    <th>CNPJ</th>
                                    <th>Plano</th>
                                    <th>Status</th>
                                    <th>Usuários</th>
                                    <th>Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($escolas)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <div class="mb-3">
                                            <i class="bi bi-building" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                        <h5>Nenhuma escola cadastrada</h5>
                                        <p class="mb-3">Comece criando sua primeira escola na plataforma.</p>
                                        <a href="<?php echo $app->url('/admin/escolas/create'); ?>" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Criar Primeira Escola
                                        </a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($escolas as $escola): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($escola['logo'])): ?>
                                                <img src="<?php echo htmlspecialchars($escola['logo']); ?>" 
                                                     alt="Logo" class="rounded me-3" width="40" height="40">
                                                <?php else: ?>
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($escola['nome']); ?></strong>
                                                    <br><small class="text-muted">ID: <?php echo $escola['id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code><?php echo htmlspecialchars($escola['subdominio']); ?>.educatudo.com</code>
                                        </td>
                                        <td><?php echo htmlspecialchars($escola['cnpj'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $escola['plano'] === 'premium' ? 'warning' : 
                                                    ($escola['plano'] === 'avancado' ? 'info' : 'secondary'); 
                                            ?>">
                                                <?php echo ucfirst($escola['plano'] ?? 'básico'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($escola['ativa'] ?? true): ?>
                                                <span class="badge bg-success">Ativa</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inativa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="text-primary"><?php echo $escola['total_usuarios'] ?? '0'; ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($escola['created_at'] ?? $escola['data_cadastro'] ?? 'now')); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id']); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo $app->url('/admin/escolas/' . $escola['id'] . '/edit'); ?>" 
                                                   class="btn btn-sm btn-outline-secondary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-<?php echo ($escola['ativa'] ?? true) ? 'danger' : 'success'; ?>" 
                                                        onclick="toggleStatus(<?php echo $escola['id']; ?>, <?php echo ($escola['ativa'] ?? true) ? 'false' : 'true'; ?>)" 
                                                        title="<?php echo ($escola['ativa'] ?? true) ? 'Desativar' : 'Ativar'; ?>">
                                                    <i class="bi bi-<?php echo ($escola['ativa'] ?? true) ? 'ban' : 'check'; ?>"></i>
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
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="modalConfirmacao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalMensagem"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(escolaId, novoStatus) {
    const acao = novoStatus ? 'ativar' : 'desativar';
    const modal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
    document.getElementById('modalMensagem').textContent = 
        `Tem certeza que deseja ${acao} esta escola?`;
    
    document.getElementById('btnConfirmar').onclick = function() {
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
        
        modal.hide();
    };
    
    modal.show();
}

function limparFiltros() {
    document.getElementById('filtro-plano').value = '';
    document.getElementById('filtro-status').value = '';
    document.getElementById('filtro-busca').value = '';
    aplicarFiltros();
}

function aplicarFiltros() {
    const plano = document.getElementById('filtro-plano').value;
    const status = document.getElementById('filtro-status').value;
    const busca = document.getElementById('filtro-busca').value.toLowerCase();
    
    const linhas = document.querySelectorAll('#tabelaEscolas tbody tr');
    
    linhas.forEach(linha => {
        if (linha.querySelector('td[colspan]')) return; // Pular linha de "nenhuma escola"
        
        const textoLinha = linha.textContent.toLowerCase();
        const badgePlano = linha.querySelector('.badge').textContent.toLowerCase();
        const badgeStatus = linha.querySelectorAll('.badge')[1].textContent.toLowerCase();
        
        let mostrar = true;
        
        if (plano && !badgePlano.includes(plano)) mostrar = false;
        if (status && !badgeStatus.includes(status === '1' ? 'ativa' : 'inativa')) mostrar = false;
        if (busca && !textoLinha.includes(busca)) mostrar = false;
        
        linha.style.display = mostrar ? '' : 'none';
    });
}

// Aplicar filtros em tempo real
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('filtro-busca').addEventListener('input', aplicarFiltros);
    document.getElementById('filtro-plano').addEventListener('change', aplicarFiltros);
    document.getElementById('filtro-status').addEventListener('change', aplicarFiltros);
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>
