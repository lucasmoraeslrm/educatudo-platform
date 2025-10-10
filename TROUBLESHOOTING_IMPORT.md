# Troubleshooting - Importação JSON

## Erro: "Unexpected token 'T', "There is n"... is not valid JSON"

### Possíveis Causas:

1. **O servidor está retornando HTML ao invés de JSON**
   - Erro de autenticação
   - Erro PHP não capturado
   - Warning ou Notice do PHP

2. **Formato do JSON está incorreto**
   - JSON mal formatado
   - Campos obrigatórios faltando

3. **Problema com a requisição**
   - Headers incorretos
   - Rota não encontrada

### Como Diagnosticar:

#### 1. Verificar Logs do PHP

**Windows (XAMPP):**
```
C:\xampp\php\logs\php_error_log
```

**Linux:**
```
/var/log/apache2/error.log
```

#### 2. Verificar JSON no Console do Navegador

1. Abra o DevTools (F12)
2. Vá para a aba "Network"
3. Faça a importação
4. Clique na requisição `import`
5. Veja a aba "Response"

**O que procurar:**
- Se a resposta começa com `{` → JSON válido
- Se começa com `<!DOCTYPE` ou `<br` → HTML (erro PHP)

#### 3. Validar o JSON

Use um validador online:
- https://jsonlint.com/
- Copie e cole seu JSON
- Verifique se está válido

### Correções Aplicadas:

1. ✅ Adicionado `ob_clean()` para limpar output buffer
2. ✅ Melhor tratamento de erros com stack trace
3. ✅ Logs detalhados de debug
4. ✅ Verificação se está em transação antes de fazer rollback
5. ✅ Retorno de informações detalhadas em caso de erro

### Teste Rápido:

1. **Verifique se o arquivo JSON está válido:**
   ```json
   {
     "exercicios": [
       {
         "materia": "Teste",
         "serie": "1º ano",
         "nivel_dificuldade": "Fácil",
         "titulo_lista": "Lista de Teste",
         "questoes": [
           {
             "id": 1,
             "pergunta": "Pergunta teste?",
             "alternativas": {
               "A": "Resposta A",
               "B": "Resposta B",
               "C": "Resposta C",
               "D": "Resposta D"
             },
             "resposta_correta": "A",
             "explicacao": "Explicação teste"
           }
         ]
       }
     ]
   }
   ```

2. **Teste com curl (PowerShell):**
   ```powershell
   $json = Get-Content exemplo_exercicios.json -Raw
   $headers = @{
       "Content-Type" = "application/json"
   }
   
   Invoke-WebRequest -Uri "http://localhost/educatudo/admin/exercicios/import" `
       -Method POST `
       -Body $json `
       -Headers $headers `
       -UseBasicParsing
   ```

### Próximos Passos:

1. **Verifique o log de erro do PHP**
2. **Abra o DevTools e veja a resposta completa**
3. **Me envie:**
   - O conteúdo do log de erro
   - O response da requisição (aba Network)
   - O JSON que você está tentando importar

Com essas informações, poderei identificar o problema exato!

