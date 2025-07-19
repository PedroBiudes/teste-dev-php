<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Fornecedor</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .details-card { border: 1px solid #ddd; padding: 20px; border-radius: 5px; max-width: 600px; margin-top: 20px; }
        .details-card p { margin-bottom: 10px; }
        .details-card strong { display: inline-block; width: 120px; }
        a { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>
    <h1>Detalhes do Fornecedor: {{ $fornecedor->nome }}</h1>

    <div class="details-card">
        <p><strong>ID:</strong> {{ $fornecedor->id }}</p>
        <p><strong>Nome/Razão Social:</strong> {{ $fornecedor->nome }}</p>
        {{-- Adicionado Nome Fantasia --}}
        @if($fornecedor->nome_fantasia)
            <p><strong>Nome Fantasia:</strong> {{ $fornecedor->nome_fantasia }}</p>
        @endif
        <p><strong>CPF/CNPJ:</strong> {{ $fornecedor->cpf_cnpj }}</p>
        <p><strong>Email:</strong> {{ $fornecedor->email ?? 'N/A' }}</p>
        <p><strong>Telefone:</strong> {{ $fornecedor->telefone ?? 'N/A' }}</p>

        @if($fornecedor->cep || $fornecedor->logradouro || $fornecedor->numero || $fornecedor->complemento || $fornecedor->bairro || $fornecedor->cidade || $fornecedor->estado)
            <hr style="margin: 20px 0;">
            <p style="font-weight: bold;">Informações de Endereço:</p>
            @if($fornecedor->logradouro || $fornecedor->numero)
                <p><strong>Endereço:</strong> {{ $fornecedor->logradouro ?? '' }}{{ $fornecedor->numero ? ', ' . $fornecedor->numero : '' }}</p>
            @endif
            @if($fornecedor->complemento)
                <p><strong>Complemento:</strong> {{ $fornecedor->complemento }}</p>
            @endif
            @if($fornecedor->bairro)
                <p><strong>Bairro:</strong> {{ $fornecedor->bairro }}</p>
            @endif
            @if($fornecedor->cidade || $fornecedor->estado)
                <p><strong>Cidade/UF:</strong> {{ $fornecedor->cidade ?? '' }}{{ ($fornecedor->cidade && $fornecedor->estado) ? ' - ' : '' }}{{ $fornecedor->estado ?? '' }}</p>
            @endif
            @if($fornecedor->cep)
                <p><strong>CEP:</strong> {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', $fornecedor->cep) }}</p>
            @endif
        @else
            @if($fornecedor->endereco)
                <p><strong>Endereço (Texto):</strong> {{ $fornecedor->endereco }}</p>
            @endif
        @endif

        <p><hr style="margin: 20px 0;"><strong>Criado em:</strong> {{ $fornecedor->created_at ? $fornecedor->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
        <p><strong>Atualizado em:</strong> {{ $fornecedor->updated_at ? $fornecedor->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
    </div>

    <a href="{{ route('index') }}">Voltar para a lista</a>
</body>
</html>