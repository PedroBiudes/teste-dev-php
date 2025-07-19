{{-- resources/views/fornecedores/create_cpf.blade.php --}}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Fornecedor (Pessoa Física - CPF)</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { background-color: #f9f9f9; padding: 20px; border-radius: 8px; max-width: 500px; margin: 20px auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; }
        button[type="submit"] { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        button[type="submit"]:hover { background-color: #218838; }
        .error { color: red; font-size: 0.9em; margin-top: -10px; margin-bottom: 10px; }
        .back-link { display: block; text-align: center; margin-top: 20px; }

        .cnpj-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .cnpj-input-group input { flex-grow: 1; margin-bottom: 0 !important; }
        .cnpj-input-group button { flex-shrink: 0; }
    </style>
</head>
<body>
    <h1>Cadastrar Fornecedor (Pessoa Física - CPF)</h1>

    <form action="{{ route('store') }}" method="POST">
        @csrf

        <div>
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required>
            @error('nome') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="cpf_cnpj">CPF:</label> {{-- Mudado para CPF --}}
            <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" maxlength="14" placeholder="000.000.000-00" required>
            @error('cpf_cnpj') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div>
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}" maxlength="15" placeholder="(XX) XXXXX-XXXX">
            @error('telefone') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div>
            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" value="{{ old('cep', $fornecedor->cep ?? '') }}" maxlength="9" placeholder="00000-000">
            <span id="cepLoading" style="display: none; margin-left: 10px; color: #007bff;">Carregando...</span>
            <span id="cepError" style="display: none; margin-left: 10px; color: red;"></span>
            @error('cep') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div><label for="logradouro">Logradouro:</label><input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro') }}"></div>
        <div><label for="numero">Número:</label><input type="text" id="numero" name="numero" value="{{ old('numero') }}"></div>
        <div><label for="complemento">Complemento:</label><input type="text" id="complemento" name="complemento" value="{{ old('complemento') }}"></div>
        <div><label for="bairro">Bairro:</label><input type="text" id="bairro" name="bairro" value="{{ old('bairro') }}"></div>
        <div><label for="cidade">Cidade:</label><input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}"></div>
        <div><label for="estado">Estado (UF):</label><input type="text" id="estado" name="estado" value="{{ old('estado') }}" maxlength="2" placeholder="UF"></div>


        <button type="submit">Cadastrar Fornecedor</button>
    </form>

    <div class="back-link">
        <a href="{{ route('index') }}">Voltar para a lista</a>
    </div>
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        const cpfCnpjInput = document.getElementById('cpf_cnpj');

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
        const telefoneInput = document.getElementById('telefone');

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
        }
        const buscarCnpjBtn = document.getElementById('buscarCnpjBtn');
        const cnpjLoading = document.getElementById('cnpjLoading');
        const cnpjError = document.getElementById('cnpjError');

        function preencherCampos(data) {
            document.getElementById('nome').value = data.razao_social || '';
            document.getElementById('email').value = data.email || '';

            const telefoneLimpo = data.telefone ? data.telefone.replace(/\D/g, '') : '';
            let maskedTelefone = '';
            if (telefoneLimpo.length === 10) {
                maskedTelefone = telefoneLimpo.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else if (telefoneLimpo.length === 11) {
                maskedTelefone = telefoneLimpo.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            document.getElementById('telefone').value = maskedTelefone;

            document.getElementById('cep').value = data.cep || '';
            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('numero').value = data.numero || '';
            document.getElementById('complemento').value = data.complemento || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.cidade || '';
            document.getElementById('estado').value = data.estado || '';
        }

        function clearMessages() {
            cnpjLoading.style.display = 'none';
            cnpjError.style.display = 'none';
            cnpjError.textContent = '';
        }

        async function buscarCnpj() {
            clearMessages();
            const cnpj = cpfCnpjInput.value.replace(/\D/g, '');

            if (cnpj.length !== 14) {
                cnpjError.textContent = 'CNPJ deve conter 14 dígitos.';
                cnpjError.style.display = 'inline';
                return;
            }

            cnpjLoading.style.display = 'inline';

            try {
                const response = await fetch(`/api/cnpj/${cnpj}`);
                const data = await response.json();

                if (response.ok) {
                    preencherCampos(data);
                } else {
                    cnpjError.textContent = data.error || 'Erro desconhecido ao buscar CNPJ.';
                    cnpjError.style.display = 'inline';
                }
            } catch (error) {
                cnpjError.textContent = 'Erro de rede ou servidor ao buscar CNPJ. Tente novamente.';
                cnpjError.style.display = 'inline';
                console.error('Erro ao buscar CNPJ:', error);
            } finally {
                cnpjLoading.style.display = 'none';
            }
        }

        if (buscarCnpjBtn) {
            buscarCnpjBtn.addEventListener('click', buscarCnpj);
        }
        const cepInput = document.getElementById('cep');
        const logradouroInput = document.getElementById('logradouro');
        const bairroInput = document.getElementById('bairro');
        const cidadeInput = document.getElementById('cidade');
        const estadoInput = document.getElementById('estado');
        const numeroInput = document.getElementById('numero');
        const complementoInput = document.getElementById('complemento');

        const cepLoading = document.getElementById('cepLoading');
        const cepError = document.getElementById('cepError');

        function clearCepMessages() {
            if (cepLoading) cepLoading.style.display = 'none';
            if (cepError) {
                cepError.style.display = 'none';
                cepError.textContent = '';
            }
        }

        function preencherCamposEndereco(data) {
            if (logradouroInput) logradouroInput.value = data.logradouro || '';
            if (bairroInput) bairroInput.value = data.bairro || '';
            if (cidadeInput) cidadeInput.value = data.cidade || '';
            if (estadoInput) estadoInput.value = data.estado || '';
        }

        async function buscarCep() {
            clearCepMessages();
            const cep = cepInput.value.replace(/\D/g, '');

            if (cep.length !== 8) {
                preencherCamposEndereco({
                    logradouro: '',
                    bairro: '',
                    cidade: '',
                    estado: ''
                });
                return;
            }

            if (cepLoading) cepLoading.style.display = 'inline';

            try {
                const response = await fetch(`/api/cep/${cep}`);
                const data = await response.json();

                if (response.ok) {
                    preencherCamposEndereco(data);
                } else {
                    if (cepError) {
                        cepError.textContent = data.error || 'Erro desconhecido ao buscar CEP.';
                        cepError.style.display = 'inline';
                    }
                    preencherCamposEndereco({
                        logradouro: '',
                        bairro: '',
                        cidade: '',
                        estado: ''
                    });
                }
            } catch (error) {
                if (cepError) {
                    cepError.textContent = 'Erro de rede ou servidor ao buscar CEP. Tente novamente.';
                    cepError.style.display = 'inline';
                }
                console.error('Erro ao buscar CEP:', error);
                preencherCamposEndereco({
                    logradouro: '',
                    bairro: '',
                    cidade: '',
                    estado: ''
                });
            } finally {
                if (cepLoading) cepLoading.style.display = 'none';
            }
        }

        if (cepInput) {
            cepInput.addEventListener('blur', buscarCep);
        }
    });
</script>

</body>
</html>