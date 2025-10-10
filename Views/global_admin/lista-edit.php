<?php
$title = $title ?? 'Editar Lista - Admin Global';
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Editar Lista de Exercícios</h1>
                    <p class="text-muted mb-0">Modifique a lista de exercícios</p>
                </div>
                <div>
                    <a href="<?= url('admin/exercicios/listas/' . $lista['id']) ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form id="listaForm" method="POST" action="/admin/exercicios/listas/<?= $lista['id'] ?>">
        <input type="hidden" name="_method" value="PUT">
        
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
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($lista['titulo']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="materia" class="form-label">Matéria *</label>
                                <input type="text" class="form-control" id="materia" name="materia" value="<?= htmlspecialchars($lista['materia']) ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="serie" class="form-label">Série *</label>
                                <input type="text" class="form-control" id="serie" name="serie" value="<?= htmlspecialchars($lista['serie']) ?>" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="nivel_dificuldade" class="form-label">Nível de Dificuldade *</label>
                                <select class="form-select" id="nivel_dificuldade" name="nivel_dificuldade" required>
                                    <option value="Fácil" <?= $lista['nivel_dificuldade'] === 'Fácil' ? 'selected' : '' ?>>Fácil</option>
                                    <option value="Médio" <?= $lista['nivel_dificuldade'] === 'Médio' ? 'selected' : '' ?>>Médio</option>
                                    <option value="Difícil" <?= $lista['nivel_dificuldade'] === 'Difícil' ? 'selected' : '' ?>>Difícil</option>
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
                            <?php if (empty($lista['questoes'])): ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> Nenhuma questão adicionada ainda. Clique em "Adicionar Questão" para começar.
                                </div>
                            <?php endif; ?>
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
                            <i class="bi bi-check-circle"></i> Salvar Alterações
                        </button>
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="window.location.href='<?= url('admin/exercicios/listas/' . $lista['id']) ?>'">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let questaoCounter = <?= count($lista['questoes'] ?? []) ?>;
const questoesExistentes = <?= json_encode($lista['questoes'] ?? []) ?>;

// Carregar questões existentes
window.addEventListener('DOMContentLoaded', function() {
    questoesExistentes.forEach((questao, index) => {
        addQuestao(index + 1, questao);
    });
    updateTotalQuestoes();
});

document.getElementById('addQuestao').addEventListener('click', function() {
    questaoCounter++;
    addQuestao(questaoCounter);
    updateTotalQuestoes();
});

function addQuestao(numero, data = null) {
    const container = document.getElementById('questoesContainer');
    
    // Remover alert se existir
    const alert = container.querySelector('.alert-info');
    if (alert) alert.remove();
    
    const questaoHTML = `
        <div class="card mb-3 questao-item" data-questao-id="${numero}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Questão ${numero}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestao(${numero})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="card-body">
                ${data && data.id ? `<input type="hidden" name="questoes[${numero}][id]" value="${data.id}">` : ''}
                
                <div class="mb-3">
                    <label class="form-label">Pergunta *</label>
                    <textarea class="form-control" name="questoes[${numero}][pergunta]" rows="3" required>${data ? data.pergunta : ''}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Questão *</label>
                    <select class="form-select tipo-questao" name="questoes[${numero}][tipo]" onchange="toggleAlternativas(${numero})" required>
                        <option value="multipla_escolha" ${data && data.tipo === 'multipla_escolha' ? 'selected' : ''}>Múltipla Escolha</option>
                        <option value="dissertativa" ${data && data.tipo === 'dissertativa' ? 'selected' : ''}>Dissertativa</option>
                    </select>
                </div>

                <div class="alternativas-section" id="alternativas-${numero}" style="display: ${data && data.tipo === 'dissertativa' ? 'none' : 'block'}">
                    <label class="form-label">Alternativas *</label>
                    <div class="mb-2">
                        ${['A', 'B', 'C', 'D'].map(letra => {
                            const alt = data && data.alternativas ? data.alternativas.find(a => a.letra === letra) : null;
                            const isCorreta = data && data.resposta_correta === letra;
                            return `
                                <div class="input-group mb-2">
                                    <span class="input-group-text">${letra}</span>
                                    <input type="text" class="form-control" name="questoes[${numero}][alternativas][${letra}]" placeholder="Alternativa ${letra}" value="${alt ? alt.texto : ''}" ${data && data.tipo !== 'dissertativa' ? 'required' : ''}>
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="radio" name="questoes[${numero}][resposta_correta]" value="${letra}" ${isCorreta ? 'checked' : ''}>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                    <small class="text-muted">Marque o círculo da alternativa correta</small>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Explicação (opcional)</label>
                    <textarea class="form-control" name="questoes[${numero}][explicacao]" rows="2">${data && data.explicacao ? data.explicacao : ''}</textarea>
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
        alternativasSection.querySelectorAll('input[type="text"]').forEach(input => {
            input.removeAttribute('required');
        });
    } else {
        alternativasSection.style.display = 'block';
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
    
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('questoes[')) {
            if (!data.questoes) data.questoes = [];
            
            const match = key.match(/questoes\[(\d+)\]\[(\w+)\](?:\[(\w+)\])?/);
            if (match) {
                const [, questaoId, campo, subcampo] = match;
                let questao = data.questoes.find(q => q._id === questaoId);
                
                if (!questao) {
                    questao = { _id: questaoId };
                    data.questoes.push(questao);
                }
                
                if (subcampo) {
                    if (!questao[campo]) questao[campo] = {};
                    questao[campo][subcampo] = value;
                } else {
                    questao[campo] = value;
                }
            }
        } else if (key !== '_method') {
            data[key] = value;
        }
    }
    
    if (data.questoes) {
        data.questoes.forEach(q => delete q._id);
    }
    
    try {
        const response = await fetch('<?= url("admin/exercicios/listas/" . $lista["id"]) ?>', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = '<?= url("admin/exercicios/listas/" . $lista["id"]) ?>';
        } else {
            alert('Erro ao atualizar lista: ' + (result.message || 'Erro desconhecido'));
        }
    } catch (error) {
        alert('Erro ao atualizar lista: ' + error.message);
        console.error(error);
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>

