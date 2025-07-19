{{-- resources/views/fornecedores/index.blade.php --}}

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Fornecedores</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .alert { padding: 10px; background-color: #f44336; color: white; margin-bottom: 15px; }

        .action-buttons a, .action-buttons button {
            margin-right: 5px;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9em;
            display: inline-block;
        }
        .action-buttons a.edit { background-color: #007bff; }
        .action-buttons a.show { background-color: #FF8C00; }
        .action-buttons button.delete { background-color: #dc3545; border: none; cursor: pointer; }
        
        /* Manter os estilos para os dois botões de criação */
        .create-link { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .create-link:hover { background-color: #218838; }
        .create-link-cpf { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .create-link-cpf:hover { background-color: #007bff; }

        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }

        .filter-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }
        .filter-form input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex-grow: 1;
        }
        .filter-form button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .filter-form button:hover {
            background-color: #0056b3;
        }
        .filter-form a.clear-filter {
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
        .filter-form a.clear-filter:hover {
            background-color: #5a6268;
        }
        .sort-icon {
            margin-left: 5px;
            font-size: 0.8em;
            color: #666;
        }
        .sort-icon.active {
            color: #007bff;
        }
        .sort-icon::before {
            content: '\25B2';
            display: inline-block;
            transform: rotate(0deg);
            transition: transform 0.2s;
        }
        .sort-icon.asc::before {
            transform: rotate(0deg);
        }
        .sort-icon.desc::before {
            content: '\25BC';
            transform: rotate(0deg);
        }
        .sort-icon.inactive::before {
            content: '\2195';
            font-size: 1.2em;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <h1>Lista de Fornecedores</h1>

    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Mantendo os dois botões de cadastro originais --}}
    <a href="{{ route('create_cnpj') }}" class="create-link">Cadastrar Novo Fornecedor CNPJ</a>
    <a href="{{ route('create_cpf') }}" class="create-link-cpf">Cadastrar Novo Fornecedor CPF</a>
    <br>

    {{-- Formulário de Filtro --}}
    <form action="{{ route('index') }}" method="GET" class="filter-form">
        <input type="text" name="search" placeholder="Buscar por nome, CPF/CNPJ, email, endereço..." value="{{ request('search') }}">
        <button type="submit">Buscar</button>
        @if(request('search'))
            <a href="{{ route('index', ['sort_by' => $sortBy ?? 'id', 'sort_order' => $sortOrder ?? 'asc']) }}" class="clear-filter">Limpar Busca</a>
        @endif
    </form>

    @if ($fornecedores->isEmpty())
        <p>Nenhum fornecedor encontrado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'id', 'sort_order' => ($sortBy == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                            <span class="sort-icon {{ $sortBy == 'id' ? 'active ' . $sortOrder : 'inactive' }}"></span>
                        </a>
                    </th>
                    <th>
                        Nome/Razão Social
                        <a href="{{ route('index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'nome', 'sort_order' => ($sortBy == 'nome' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                             <span class="sort-icon {{ $sortBy == 'nome' ? 'active ' . $sortOrder : 'inactive' }}"></span>
                        </a>
                    </th>
                    <th>
                        Nome Fantasia
                        <a href="{{ route('index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'nome_fantasia', 'sort_order' => ($sortBy == 'nome_fantasia' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                             <span class="sort-icon {{ $sortBy == 'nome_fantasia' ? 'active ' . $sortOrder : 'inactive' }}"></span>
                        </a>
                    </th>
                    <th>
                        CPF/CNPJ
                        <a href="{{ route('index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'cpf_cnpj', 'sort_order' => ($sortBy == 'cpf_cnpj' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                             <span class="sort-icon {{ $sortBy == 'cpf_cnpj' ? 'active ' . $sortOrder : 'inactive' }}"></span>
                        </a>
                    </th>
                    <th>
                        Email
                        <a href="{{ route('index', array_merge(request()->except(['sort_by', 'sort_order', 'page']), ['sort_by' => 'email', 'sort_order' => ($sortBy == 'email' && $sortOrder == 'asc' ? 'desc' : 'asc')])) }}">
                            <span class="sort-icon {{ $sortBy == 'email' ? 'active ' . $sortOrder : 'inactive' }}"></span>
                        </a>
                    </th>
                    <th>Telefone</th>
                    <th>Endereço Completo</th> {{-- Nova coluna para endereço combinado --}}
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fornecedores as $fornecedor)
                    <tr>
                        <td>{{ $fornecedor->id }}</td>
                        <td>{{ $fornecedor->nome }}</td>
                        <td>{{ $fornecedor->nome_fantasia ?? 'N/A' }}</td> {{-- Novo campo --}}
                        <td>{{ $fornecedor->cpf_cnpj }}</td>
                        <td>{{ $fornecedor->email ?? 'N/A' }}</td>
                        <td>{{ $fornecedor->telefone ?? 'N/A' }}</td>
                        <td>
                            @if($fornecedor->logradouro || $fornecedor->numero || $fornecedor->bairro || $fornecedor->cidade || $fornecedor->estado || $fornecedor->cep)
                                {{ $fornecedor->logradouro ?? '' }}{{ $fornecedor->numero ? ', ' . $fornecedor->numero : '' }}
                                {{ $fornecedor->complemento ? ' (' . $fornecedor->complemento . ')' : '' }}
                                {{ $fornecedor->bairro ? ' - ' . $fornecedor->bairro : '' }}
                                {{ ($fornecedor->cidade || $fornecedor->estado) ? ' - ' . ($fornecedor->cidade ?? '') . ($fornecedor->cidade && $fornecedor->estado ? '/' : '') . ($fornecedor->estado ?? '') : '' }}
                                {{ $fornecedor->cep ? ' - ' . preg_replace('/(\d{5})(\d{3})/', '$1-$2', $fornecedor->cep) : '' }}
                            @elseif($fornecedor->endereco)
                                {{ $fornecedor->endereco }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="action-buttons">
                            <a href="{{ route('show', $fornecedor->id) }}" class="show">Ver</a>
                            <a href="{{ route('edit', $fornecedor) }}" class="edit">Editar</a>
                            <form action="{{ route('destroy', $fornecedor) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?');">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: center;">
            {{ $fornecedores->appends(request()->except('page'))->links() }}
        </div>
    @endif
</body>
</html>