<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Fornecedor</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { background-color: #f9f9f9; padding: 20px; border-radius: 8px; max-width: 500px; margin: 20px auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .error { color: red; font-size: 0.9em; margin-top: -10px; margin-bottom: 10px; }
        .back-link { display: block; text-align: center; margin-top: 20px; }
        .input-group { display: flex; align-items: flex-end; gap: 10px; margin-bottom: 15px; }
        .input-group input { flex-grow: 1; margin-bottom: 0; }
        .input-group button { white-space: nowrap; }
        .loading-message, .error-message {
            margin-top: 5px;
            font-size: 0.85em;
            padding: 5px 0;
            display: none;
        }
        .loading-message { color: blue; }
        .error-message { color: red; }
    </style>
</head>
<body>
    <h1>Editar Fornecedor: {{ $fornecedor->nome }}</h1>

    <form action="{{ route('update', $fornecedor) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" id="fornecedor_tipo" name="tipo" value="{{ old('tipo', $fornecedor->tipo) }}">

        <div>
            <label for="nome">Nome/Razão Social:</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome', $fornecedor->nome) }}" required>
            @error('nome')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="nome_fantasia">Nome Fantasia (Opcional para PJ):</label>
            <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $fornecedor->nome_fantasia) }}">
            @error('nome_fantasia')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="cpf_cnpj">CPF/CNPJ:</label>
            <div class="input-group">
                <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $fornecedor->cpf_cnpj) }}" required>
                <button type="button" id="buscarCnpjBtn">Buscar CNPJ</button>
            </div>
            <span id="cnpjLoading" class="loading-message">Buscando CNPJ...</span>
            <span id="cnpjError" class="error-message"></span>
            @error('cpf_cnpj')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $fornecedor->email) }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" value="{{ old('telefone', $fornecedor->telefone) }}">
            @error('telefone')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <hr style="margin: 20px 0;">
        <h3>Endereço</h3>

        <div>
            <label for="cep">CEP:</label>
            <div class="input-group">
                <input type="text" id="cep" name="cep" value="{{ old('cep', $fornecedor->cep) }}" maxlength="9">
                <button type="button" id="buscarCepBtn">Buscar CEP</button>
            </div>
            <span id="cepLoading" class="loading-message">Buscando CEP...</span>
            <span id="cepError" class="error-message"></span>
            @error('cep')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="logradouro">Logradouro:</label>
            <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro', $fornecedor->logradouro) }}">
            @error('logradouro')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero" value="{{ old('numero', $fornecedor->numero) }}">
            @error('numero')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="complemento">Complemento (Opcional):</label>
            <input type="text" id="complemento" name="complemento" value="{{ old('complemento', $fornecedor->complemento) }}">
            @error('complemento')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $fornecedor->bairro) }}">
            @error('bairro')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="{{ old('cidade', $fornecedor->cidade) }}">
            @error('cidade')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="estado">Estado (UF):</label>
            <input type="text" id="estado" name="estado" value="{{ old('estado', $fornecedor->estado) }}" maxlength="2">
            @error('estado')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <input type="hidden" id="endereco" name="endereco" value="{{ old('endereco', $fornecedor->endereco) }}">

        <button type="submit">Atualizar Fornecedor</button>
    </form>

    <a href="{{ route('index') }}" class="back-link">Voltar para a lista</a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cpfCnpjInput = document.getElementById('cpf_cnpj');
            const telefoneInput = document.getElementById('telefone');
            const cepInput = document.getElementById('cep');

            if (cpfCnpjInput) {
                cpfCnpjInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let maskedValue = '';

                    if (value.length <= 11) {
                        maskedValue = value.replace(/(\d{3})(\d)/, '$1.$2');
                        maskedValue = maskedValue.replace(/(\d{3})(\d)/, '$1.$2');
                        maskedValue = maskedValue.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    } else {
                        maskedValue = value.replace(/^(\d{2})(\d)/, '$1.$2');
                        maskedValue = maskedValue.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                        maskedValue = maskedValue.replace(/\.(\d{3})(\d)/, '.$1/$2');
                        maskedValue = maskedValue.replace(/(\d{4})(\d)/, '$1-$2');
                    }
                    e.target.value = maskedValue;
                });
                cpfCnpjInput.value = cpfCnpjInput.value.replace(/\D/g, '');
                if (cpfCnpjInput.value.length === 11 || cpfCnpjInput.value.length === 14) {
                    cpfCnpjInput.value = applyCpfCnpjMask(cpfCnpjInput.value);
                }
            }

            if (telefoneInput) {
                telefoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let maskedValue = '';

                    if (value.length > 10) {
                        maskedValue = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                    } else if (value.length > 6) {
                        maskedValue = value.replace(/^(\d{2})(\d{4})(\d{4}).*/, '($1) $2-$3');
                    } else if (value.length > 2) {
                        maskedValue = value.replace(/^(\d{2})(\d+)/, '($1) $2');
                    } else {
                        maskedValue = value;
                    }
                    e.target.value = maskedValue;
                });
                if (telefoneInput.value) {
                    telefoneInput.value = applyTelefoneMask(telefoneInput.value.replace(/\D/g, ''));
                }
            }

            if (cepInput) {
                cepInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let maskedValue = value;
                    if (value.length > 5) {
                        maskedValue = value.replace(/^(\d{5})(\d)/, '$1-$2');
                    }
                    e.target.value = maskedValue;
                });
                if (cepInput.value) {
                    cepInput.value = cepInput.value.replace(/\D/g, '');
                    if (cepInput.value.length === 8) {
                        cepInput.value = cepInput.value.replace(/^(\d{5})(\d{3})$/, '$1-$2');
                    }
                }
            }

            function applyCpfCnpjMask(value) {
                let maskedValue = value.replace(/\D/g, '');
                if (maskedValue.length <= 11) {
                    maskedValue = maskedValue.replace(/(\d{3})(\d)/, '$1.$2');
                    maskedValue = maskedValue.replace(/(\d{3})(\d)/, '$1.$2');
                    maskedValue = maskedValue.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                } else {
                    maskedValue = maskedValue.replace(/^(\d{2})(\d)/, '$1.$2');
                    maskedValue = maskedValue.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    maskedValue = maskedValue.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    maskedValue = maskedValue.replace(/(\d{4})(\d)/, '$1-$2');
                }
                return maskedValue;
            }

            function applyTelefoneMask(value) {
                let maskedValue = value.replace(/\D/g, '');
                if (maskedValue.length > 10) {
                    maskedValue = maskedValue.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                } else if (maskedValue.length > 6) {
                    maskedValue = maskedValue.replace(/^(\d{2})(\d{4})(\d{4}).*/, '($1) $2-$3');
                } else if (maskedValue.length > 2) {
                    maskedValue = maskedValue.replace(/^(\d{2})(\d+)/, '($1) $2');
                }
                return maskedValue;
            }

            const nomeInput = document.getElementById('nome');
            const nomeFantasiaInput = document.getElementById('nome_fantasia');
            const emailInput = document.getElementById('email');

            const logradouroInput = document.getElementById('logradouro');
            const numeroInput = document.getElementById('numero');
            const complementoInput = document.getElementById('complemento');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const estadoInput = document.getElementById('estado');
            const enderecoAntigoHiddenInput = document.getElementById('endereco');

            const cnpjLoading = document.getElementById('cnpjLoading');
            const cnpjError = document.getElementById('cnpjError');
            const cepLoading = document.getElementById('cepLoading');
            const cepError = document.getElementById('cepError');

            const buscarCnpjBtn = document.getElementById('buscarCnpjBtn');
            const buscarCepBtn = document.getElementById('buscarCepBtn');

            function clearMessages(type) {
                if (type === 'cnpj') {
                    if (cnpjLoading) cnpjLoading.style.display = 'none';
                    if (cnpjError) {
                        cnpjError.style.display = 'none';
                        cnpjError.textContent = '';
                    }
                } else if (type === 'cep') {
                    if (cepLoading) cepLoading.style.display = 'none';
                    if (cepError) {
                        cepError.style.display = 'none';
                        cepError.textContent = '';
                    }
                }
            }

            function preencherCamposCnpj(data) {
                if (nomeInput) nomeInput.value = data.razao_social || '';
                if (nomeFantasiaInput) nomeFantasiaInput.value = data.nome_fantasia || '';
                if (emailInput) emailInput.value = data.email || '';
                if (telefoneInput) telefoneInput.value = applyTelefoneMask(data.telefone || '');
                if (cepInput) cepInput.value = applyCepMask(data.cep || '');
                if (logradouroInput) logradouroInput.value = data.logradouro || '';
                if (numeroInput) numeroInput.value = data.numero || '';
                if (complementoInput) complementoInput.value = data.complemento || '';
                if (bairroInput) bairroInput.value = data.bairro || '';
                if (cidadeInput) cidadeInput.value = data.cidade || '';
                if (estadoInput) estadoInput.value = data.estado || '';
                updateEnderecoAntigoField();
            }

            function preencherCamposEndereco(data) {
                if (logradouroInput) logradouroInput.value = data.logradouro || '';
                if (bairroInput) bairroInput.value = data.bairro || '';
                if (cidadeInput) cidadeInput.value = data.cidade || '';
                if (estadoInput) estadoInput.value = data.estado || '';
                updateEnderecoAntigoField();
            }

            function updateEnderecoAntigoField() {
                if (enderecoAntigoHiddenInput) {
                    const parts = [];
                    if (logradouroInput && logradouroInput.value) parts.push(logradouroInput.value);
                    if (numeroInput && numeroInput.value) parts.push(numeroInput.value);
                    if (complementoInput && complementoInput.value) parts.push('(' + complementoInput.value + ')');
                    if (bairroInput && bairroInput.value) parts.push(bairroInput.value);
                    if (cidadeInput && cidadeInput.value) parts.push(cidadeInput.value);
                    if (estadoInput && estadoInput.value) parts.push(estadoInput.value);
                    if (cepInput && cepInput.value) parts.push(cepInput.value);

                    enderecoAntigoHiddenInput.value = parts.join(' - ');
                }
            }
            updateEnderecoAntigoField();


            async function buscarCnpj() {
                clearMessages('cnpj');
                const cnpj = cpfCnpjInput.value.replace(/\D/g, '');

                if (cnpj.length !== 14) {
                    if (cnpjError) {
                        cnpjError.textContent = 'CNPJ inválido para busca. Digite 14 dígitos.';
                        cnpjError.style.display = 'inline';
                    }
                    preencherCamposCnpj({});
                    return;
                }

                if (cnpjLoading) cnpjLoading.style.display = 'inline';

                try {
                    const response = await fetch(`/api/cnpj/${cnpj}`);
                    const data = await response.json();

                    if (response.ok) {
                        preencherCamposCnpj(data);
                    } else {
                        if (cnpjError) {
                            cnpjError.textContent = data.error || 'Erro desconhecido ao buscar CNPJ.';
                            cnpjError.style.display = 'inline';
                        }
                        preencherCamposCnpj({});
                    }
                } catch (error) {
                    if (cnpjError) {
                        cnpjError.textContent = 'Erro de rede ou servidor ao buscar CNPJ. Tente novamente.';
                        cnpjError.style.display = 'inline';
                    }
                    console.error('Erro ao buscar CNPJ:', error);
                    preencherCamposCnpj({});
                } finally {
                    if (cnpjLoading) cnpjLoading.style.display = 'none';
                }
            }

            async function buscarCep() {
                clearMessages('cep');
                const cep = cepInput.value.replace(/\D/g, '');

                if (cep.length !== 8) {
                    preencherCamposEndereco({});
                    return;
                }

                if (cepLoading) cepLoading.style.display = 'inline';

                try {
                    const response = await fetch(`/api/cep/${cep}`);
                    const data = await response.json();

                    if (response.ok) {
                        preencherCamposEndereco(data);
                        if (!numeroInput.value && numeroInput) {
                            numeroInput.focus();
                        }
                    } else {
                        if (cepError) {
                            cepError.textContent = data.error || 'Erro desconhecido ao buscar CEP.';
                            cepError.style.display = 'inline';
                        }
                        preencherCamposEndereco({});
                    }
                } catch (error) {
                    if (cepError) {
                        cepError.textContent = 'Erro de rede ou servidor ao buscar CEP. Tente novamente.';
                        cepError.style.display = 'inline';
                    }
                    console.error('Erro ao buscar CEP:', error);
                    preencherCamposEndereco({});
                } finally {
                    if (cepLoading) cepLoading.style.display = 'none';
                }
            }

            if (buscarCnpjBtn) {
                buscarCnpjBtn.addEventListener('click', buscarCnpj);
            }
            if (cpfCnpjInput) {
                cpfCnpjInput.addEventListener('blur', function() {
                    const cleanValue = this.value.replace(/\D/g, '');
                    if (cleanValue.length === 14) {
                        buscarCnpj();
                    }
                });
            }

            if (buscarCepBtn) {
                buscarCepBtn.addEventListener('click', buscarCep);
            }
            if (cepInput) {
                cepInput.addEventListener('blur', buscarCep);
            }

            const fornecedorTipoInput = document.getElementById('fornecedor_tipo');
            const nomeFantasiaDiv = document.getElementById('nome_fantasia').parentNode;
            const cnpjSearchButton = document.getElementById('buscarCnpjBtn');

            function toggleFieldsVisibilityByFornecedorType() {
                if (fornecedorTipoInput.value === 'PF') {
                    if (nomeFantasiaDiv) nomeFantasiaDiv.style.display = 'none';
                    if (cnpjSearchButton) cnpjSearchButton.style.display = 'none';
                } else {
                    if (nomeFantasiaDiv) nomeFantasiaDiv.style.display = 'block';
                    if (cnpjSearchButton) cnpjSearchButton.style.display = 'inline-block';
                }
            }

            toggleFieldsVisibilityByFornecedorType();

            if (cpfCnpjInput) {
                cpfCnpjInput.addEventListener('input', function() {
                    let value = this.value.replace(/\D/g, '');
                    if (value.length > 11 && fornecedorTipoInput.value !== 'PJ') {
                        fornecedorTipoInput.value = 'PJ';
                        toggleFieldsVisibilityByFornecedorType();
                    } else if (value.length <= 11 && fornecedorTipoInput.value !== 'PF') {
                        fornecedorTipoInput.value = 'PF';
                        toggleFieldsVisibilityByFornecedorType();
                    }
                });
            }

            if (logradouroInput) logradouroInput.addEventListener('input', updateEnderecoAntigoField);
            if (numeroInput) numeroInput.addEventListener('input', updateEnderecoAntigoField);
            if (complementoInput) complementoInput.addEventListener('input', updateEnderecoAntigoField);
            if (bairroInput) bairroInput.addEventListener('input', updateEnderecoAntigoField);
            if (cidadeInput) cidadeInput.addEventListener('input', updateEnderecoAntigoField);
            if (estadoInput) estadoInput.addEventListener('input', updateEnderecoAntigoField);
        });
    </script>
</body>
</html>