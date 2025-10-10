<?php
$title = $title ?? 'Criar Lista de Exercícios - Admin Global';
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Criar Lista de Exercícios</h1>
                    <p class="text-muted mb-0">Adicione uma nova lista de exercícios manualmente</p>
                </div>
                <div>
                    <a href="<?= url('admin/exercicios') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form id="listaForm" method="POST" action="/admin/exercicios/listas">
        <div class="row">
            <div class="col-lg-8">
                <!-- Informações da Lista -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informações da Lista</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título da Lista *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="materia" class="form-label">Matéria *</label>
                                <input type="text" class="form-control" id="materia" name="materia" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="serie" class="form-label">Série *</label>
                                <input type="text" class="form-control" id="serie" name="serie" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="nivel_dificuldade" class="form-label">Nível de Dificuldade *</label>
                                <select class="form-select" id="nivel_dificuldade" name="nivel_dificuldade" required>
                                    <option value="">Selecione...</option>
                                    <option value="Fácil">Fácil</option>
                                    <option value="Médio">Médio</option>
                                    <option value="Difícil">Difícil</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questões -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Questões</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addQuestao">
                            <i class="bi bi-plus-circle"></i> Adicionar Questão
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questoesContainer">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Nenhuma questão adicionada ainda. Clique em "Adicionar Questão" para começar.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Resumo -->
                <div class="card mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0">Resumo</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Total de Questões</small>
                            <h3 class="mb-0" id="totalQuestoes">0</h3>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Criar Lista
                        </button>
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.href='<?= url('admin/exercicios') ?>'">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let questaoCounter = 0;

document.getElementById('addQuestao').addEventListener('click', function() {
    questaoCounter++;
    addQuestao(questaoCounter);
    updateTotalQuestoes();
});

function addQuestao(numero) {
    const container = document.getElementById('questoesContainer');
    
    // Remover alert se for a primeira questão
    if (questaoCounter === 1) {
        container.innerHTML = '';
    }
    
    const questaoHTML = `
        <div class="card mb-3 questao-item" data-questao-id="${numero}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Questão ${numero}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestao(${numero})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Pergunta *</label>
                    <textarea class="form-control" name="questoes[${numero}][pergunta]" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Questão *</label>
                    <select class="form-select tipo-questao" name="questoes[${numero}][tipo]" onchange="toggleAlternativas(${numero})" required>
                        <option value="multipla_escolha">Múltipla Escolha</option>
                        <option value="dissertativa">Dissertativa</option>
                    </select>
                </div>

                <div class="alternativas-section" id="alternativas-${numero}">
                    <label class="form-label">Alternativas *</label>
                    <div class="mb-2">
                        <div class="input-group mb-2">
                            <span class="input-group-text">A</span>
                            <input type="text" class="form-control" name="questoes[${numero}][alternativas][A]" placeholder="Alternativa A">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="radio" name="questoes[${numero}][resposta_correta]" value="A">
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text">B</span>
                            <input type="text" class="form-control" name="questoes[${numero}][alternativas][B]" placeholder="Alternativa B">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="radio" name="questoes[${numero}][resposta_correta]" value="B">
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text">C</span>
                            <input type="text" class="form-control" name="questoes[${numero}][alternativas][C]" placeholder="Alternativa C">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="radio" name="questoes[${numero}][resposta_correta]" value="C">
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text">D</span>
                            <input type="text" class="form-control" name="questoes[${numero}][alternativas][D]" placeholder="Alternativa D">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="radio" name="questoes[${numero}][resposta_correta]" value="D">
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Marque o círculo da alternativa correta</small>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Explicação (opcional)</label>
                    <textarea class="form-control" name="questoes[${numero}][explicacao]" rows="2"></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questaoHTML);
}

function removeQuestao(numero) {
    const questao = document.querySelector(`[data-questao-id="${numero}"]`);
    if (questao) {
        questao.remove();
        updateTotalQuestoes();
        
        // Se não houver mais questões, mostrar alert
        if (document.querySelectorAll('.questao-item').length === 0) {
            document.getElementById('questoesContainer').innerHTML = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Nenhuma questão adicionada ainda. Clique em "Adicionar Questão" para começar.
                </div>
            `;
        }
    }
}

function toggleAlternativas(numero) {
    const select = document.querySelector(`[data-questao-id="${numero}"] .tipo-questao`);
    const alternativasSection = document.getElementById(`alternativas-${numero}`);
    
    if (select.value === 'dissertativa') {
        alternativasSection.style.display = 'none';
        // Remover required das alternativas
        alternativasSection.querySelectorAll('input[type="text"]').forEach(input => {
            input.removeAttribute('required');
        });
    } else {
        alternativasSection.style.display = 'block';
        // Adicionar required nas alternativas
        alternativasSection.querySelectorAll('input[type="text"]').forEach(input => {
            input.setAttribute('required', 'required');
        });
    }
}

function updateTotalQuestoes() {
    const total = document.querySelectorAll('.questao-item').length;
    document.getElementById('totalQuestoes').textContent = total;
}

document.getElementById('listaForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {};
    
    // Converter FormData para objeto
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('questoes[')) {
            // Processar questões
            if (!data.questoes) data.questoes = [];
            
            const match = key.match(/questoes\[(\d+)\]\[(\w+)\](?:\[(\w+)\])?/);
            if (match) {
                const [, questaoId, campo, subcampo] = match;
                const questaoIndex = data.questoes.findIndex(q => q._id === questaoId);
                
                if (questaoIndex === -1) {
                    data.questoes.push({ _id: questaoId });
                }
                
                const questao = data.questoes.find(q => q._id === questaoId);
                
                if (subcampo) {
                    if (!questao[campo]) questao[campo] = {};
                    questao[campo][subcampo] = value;
                } else {
                    questao[campo] = value;
                }
            }
        } else {
            data[key] = value;
        }
    }
    
    // Limpar _id temporário
    if (data.questoes) {
        data.questoes.forEach(q => delete q._id);
    }
    
    try {
        const response = await fetch('<?= url("admin/exercicios/listas") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = '<?= url("admin/exercicios") ?>';
        } else {
            alert('Erro ao criar lista: ' + (result.message || 'Erro desconhecido'));
        }
    } catch (error) {
        alert('Erro ao criar lista: ' + error.message);
        console.error(error);
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>

