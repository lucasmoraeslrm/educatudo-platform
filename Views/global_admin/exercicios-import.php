<?php
$title = $title ?? 'Importar Exercícios - Admin Global';
ob_start();
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Importar Listas de Exercícios</h1>
                    <p class="text-muted mb-0">Importar listas de exercícios a partir de arquivo JSON</p>
                </div>
                <div>
                    <a href="<?= url('admin/exercicios') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Upload Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Upload de Arquivo JSON</h5>
                </div>
                <div class="card-body">
                    <form id="importForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="jsonFile" class="form-label">Selecione o arquivo JSON</label>
                            <input type="file" class="form-control" id="jsonFile" name="jsonFile" accept=".json" required>
                            <div class="form-text">Apenas arquivos .json são aceitos</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-arrow-up"></i> Preview do Arquivo
                        </button>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div id="previewSection" class="card d-none">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Preview das Listas</h5>
                    <button id="confirmImport" class="btn btn-success" disabled>
                        <i class="bi bi-check-circle"></i> Confirmar Importação
                    </button>
                </div>
                <div class="card-body">
                    <div id="previewContent"></div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="resultsSection" class="card d-none">
                <div class="card-header">
                    <h5 class="mb-0">Resultado da Importação</h5>
                </div>
                <div class="card-body">
                    <div id="resultsContent"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Instructions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Formato JSON Esperado</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">O arquivo JSON deve seguir o seguinte formato:</p>
                    <pre class="bg-light p-3 rounded" style="font-size: 0.75rem;"><code>{
  "exercicios": [
    {
      "materia": "Matemática",
      "serie": "1º ano do Ensino Médio",
      "nivel_dificuldade": "Fácil",
      "titulo_lista": "Equações do 1º Grau",
      "questoes": [
        {
          "id": 1,
          "pergunta": "Qual é o valor de x?",
          "alternativas": {
            "A": "x = 3",
            "B": "x = 4",
            "C": "x = 5",
            "D": "x = 6"
          },
          "resposta_correta": "B",
          "explicacao": "Explicação aqui..."
        }
      ]
    }
  ]
}</code></pre>
                    <div class="alert alert-info small mb-0 mt-3">
                        <strong>Dica:</strong> O campo <code>id</code> nas questões é apenas para organização no JSON e será ignorado na importação.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let jsonData = null;

document.getElementById('importForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('jsonFile');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Por favor, selecione um arquivo.');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(event) {
        try {
            jsonData = JSON.parse(event.target.result);
            
            if (!jsonData.exercicios || !Array.isArray(jsonData.exercicios)) {
                throw new Error('Formato inválido: campo "exercicios" não encontrado ou não é um array.');
            }
            
            // Validar estrutura
            validateJSON(jsonData);
            
            // Mostrar preview
            showPreview(jsonData);
            
        } catch (error) {
            alert('Erro ao processar arquivo JSON: ' + error.message);
            console.error(error);
        }
    };
    reader.readAsText(file);
});

function validateJSON(data) {
    const requiredFields = ['materia', 'serie', 'nivel_dificuldade', 'titulo_lista', 'questoes'];
    
    data.exercicios.forEach((lista, index) => {
        requiredFields.forEach(field => {
            if (!lista[field]) {
                throw new Error(`Lista ${index + 1}: campo "${field}" é obrigatório.`);
            }
        });
        
        if (!Array.isArray(lista.questoes) || lista.questoes.length === 0) {
            throw new Error(`Lista ${index + 1}: deve conter ao menos uma questão.`);
        }
        
        lista.questoes.forEach((questao, qIndex) => {
            if (!questao.pergunta) {
                throw new Error(`Lista ${index + 1}, Questão ${qIndex + 1}: campo "pergunta" é obrigatório.`);
            }
            if (!questao.alternativas || Object.keys(questao.alternativas).length === 0) {
                throw new Error(`Lista ${index + 1}, Questão ${qIndex + 1}: campo "alternativas" é obrigatório.`);
            }
            if (!questao.resposta_correta) {
                throw new Error(`Lista ${index + 1}, Questão ${qIndex + 1}: campo "resposta_correta" é obrigatório.`);
            }
        });
    });
}

function showPreview(data) {
    const previewSection = document.getElementById('previewSection');
    const previewContent = document.getElementById('previewContent');
    
    let html = `<div class="alert alert-success">
        <i class="bi bi-check-circle"></i> Arquivo válido! ${data.exercicios.length} lista(s) encontrada(s).
    </div>`;
    
    data.exercicios.forEach((lista, index) => {
        const nivelBadge = lista.nivel_dificuldade === 'Fácil' ? 'bg-success' : 
                          lista.nivel_dificuldade === 'Médio' ? 'bg-warning' : 'bg-danger';
        
        html += `
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        ${index + 1}. ${lista.titulo_lista}
                        <span class="badge ${nivelBadge} ms-2">${lista.nivel_dificuldade}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <strong>Matéria:</strong> ${lista.materia}
                        </div>
                        <div class="col-md-6">
                            <strong>Série:</strong> ${lista.serie}
                        </div>
                    </div>
                    <div class="alert alert-info small mb-0">
                        <i class="bi bi-info-circle"></i> ${lista.questoes.length} questão(ões) nesta lista
                    </div>
                </div>
            </div>
        `;
    });
    
    previewContent.innerHTML = html;
    previewSection.classList.remove('d-none');
    document.getElementById('confirmImport').disabled = false;
}

document.getElementById('confirmImport').addEventListener('click', async function() {
    if (!jsonData) {
        alert('Nenhum arquivo carregado.');
        return;
    }
    
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Importando...';
    
    try {
        const response = await fetch('<?= url("admin/exercicios/import") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showResults(result);
        } else {
            alert('Erro ao importar: ' + (result.message || 'Erro desconhecido'));
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Importação';
        }
        
    } catch (error) {
        alert('Erro ao importar listas: ' + error.message);
        console.error(error);
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-check-circle"></i> Confirmar Importação';
    }
});

function showResults(result) {
    const resultsSection = document.getElementById('resultsSection');
    const resultsContent = document.getElementById('resultsContent');
    
    let html = `
        <div class="alert alert-success">
            <h5><i class="bi bi-check-circle"></i> Importação Concluída com Sucesso!</h5>
            <hr>
            <p class="mb-0">
                <strong>${result.imported_listas || 0}</strong> lista(s) importada(s)<br>
                <strong>${result.imported_questoes || 0}</strong> questão(ões) importada(s)
            </p>
        </div>
        <a href="<?= url('admin/exercicios') ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Voltar para Exercícios
        </a>
    `;
    
    resultsContent.innerHTML = html;
    resultsSection.classList.remove('d-none');
    
    // Esconder preview
    document.getElementById('previewSection').classList.add('d-none');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin-global.php';
?>

