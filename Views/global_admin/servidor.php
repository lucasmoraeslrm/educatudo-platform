<?php
$title = $title ?? 'Servidor - Admin Global';
$serverInfo = $serverInfo ?? [];
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Informações do Servidor</h1>
                    <p class="text-muted mb-0">Monitoramento e configurações do servidor</p>
                </div>
                <div>
                    <button class="btn btn-primary">
                        <i class="bi bi-arrow-clockwise"></i> Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number">Online</div>
                        <div class="label">Status do Servidor</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $serverInfo['php_version'] ?? 'N/A'; ?></div>
                        <div class="label">Versão PHP</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-code-slash"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $serverInfo['database_status']['status'] === 'online' ? 'Online' : 'Offline'; ?></div>
                        <div class="label">Banco de Dados</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-database"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="number"><?php echo $serverInfo['memory_limit'] ?? 'N/A'; ?></div>
                        <div class="label">Limite de Memória</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-memory"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Information -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <strong>Servidor:</strong><br>
                            <small class="text-muted"><?php echo $serverInfo['server_software'] ?? 'N/A'; ?></small>
                        </div>
                        <div class="col-6">
                            <strong>PHP Version:</strong><br>
                            <small class="text-muted"><?php echo $serverInfo['php_version'] ?? 'N/A'; ?></small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Memory Limit:</strong><br>
                            <small class="text-muted"><?php echo $serverInfo['memory_limit'] ?? 'N/A'; ?></small>
                        </div>
                        <div class="col-6">
                            <strong>Max Execution Time:</strong><br>
                            <small class="text-muted"><?php echo $serverInfo['max_execution_time'] ?? 'N/A'; ?>s</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Upload Max Filesize:</strong><br>
                            <small class="text-muted"><?php echo $serverInfo['upload_max_filesize'] ?? 'N/A'; ?></small>
                        </div>
                        <div class="col-6">
                            <strong>Post Max Size:</strong><br>
                            <small class="text-muted"><?php echo $serverInfo['post_max_size'] ?? 'N/A'; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status do Banco de Dados</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-<?php echo $serverInfo['database_status']['status'] === 'online' ? 'success' : 'danger'; ?> text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-database"></i>
                        </div>
                        <div>
                            <h6 class="mb-0"><?php echo ucfirst($serverInfo['database_status']['status']); ?></h6>
                            <small class="text-muted"><?php echo $serverInfo['database_status']['message']; ?></small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Configurações:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check text-success"></i> Conexão ativa</li>
                            <li><i class="bi bi-check text-success"></i> Charset UTF-8</li>
                            <li><i class="bi bi-check text-success"></i> Timezone configurado</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Ações do Servidor</h4>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-arrow-clockwise text-primary"></i>
                <h6 class="mb-1">Reiniciar Serviços</h6>
                <small class="text-muted">Reiniciar serviços do servidor</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-download text-success"></i>
                <h6 class="mb-1">Backup</h6>
                <small class="text-muted">Criar backup do sistema</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-graph-up text-info"></i>
                <h6 class="mb-1">Logs</h6>
                <small class="text-muted">Visualizar logs do sistema</small>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <a href="#" class="quick-action-btn">
                <i class="bi bi-gear text-warning"></i>
                <h6 class="mb-1">Configurações</h6>
                <small class="text-muted">Alterar configurações</small>
            </a>
        </div>
    </div>

    <!-- System Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Logs do Sistema</h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary active">Todos</button>
                        <button class="btn btn-sm btn-outline-primary">Erros</button>
                        <button class="btn btn-sm btn-outline-primary">Avisos</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Nível</th>
                                    <th>Mensagem</th>
                                    <th>Arquivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><small class="text-muted">15/01/2024 14:30:25</small></td>
                                    <td><span class="badge bg-success">INFO</span></td>
                                    <td>Sistema iniciado com sucesso</td>
                                    <td><code>index.php</code></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">15/01/2024 14:25:10</small></td>
                                    <td><span class="badge bg-info">DEBUG</span></td>
                                    <td>Conexão com banco de dados estabelecida</td>
                                    <td><code>Database.php</code></td>
                                </tr>
                                <tr>
                                    <td><small class="text-muted">15/01/2024 14:20:05</small></td>
                                    <td><span class="badge bg-warning">WARNING</span></td>
                                    <td>Cache expirado, recriando...</td>
                                    <td><code>Cache.php</code></td>
                                </tr>
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


