<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fornecedor; // Importe o modelo Fornecedor
use Illuminate\Support\Facades\Hash; // Se for usar senhas, mas não é o caso aqui
use Illuminate\Support\Str; // Para strings aleatórias, se precisar

class FornecedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fornecedor PJ Completo
        Fornecedor::create([
            'nome' => 'Empresa Teste S.A.',
            'nome_fantasia' => 'Teste Fornecedor LTDA',
            'cpf_cnpj' => '12.345.678/0001-90',
            'email' => 'contato@empresateste.com.br',
            'telefone' => '(11) 98765-4321',
            'cep' => '01001000',
            'logradouro' => 'Praça da Sé',
            'numero' => '1',
            'complemento' => 'Sala 101',
            'bairro' => 'Sé',
            'cidade' => 'São Paulo',
            'estado' => 'SP'
        ]);

        // Fornecedor PJ Sem Nome Fantasia
        Fornecedor::create([
            'nome' => 'Comércio Digital Ltda.',
            'nome_fantasia' => null,
            'cpf_cnpj' => '98.765.432/0001-10',
            'email' => 'vendas@comerciodigital.com',
            'telefone' => '(21) 12345-6789',
            'cep' => '20040007',
            'logradouro' => 'Rua Sete de Setembro',
            'numero' => '100',
            'complemento' => null,
            'bairro' => 'Centro',
            'cidade' => 'Rio de Janeiro',
            'estado' => 'RJ'
        ]);

        // Fornecedor PF Completo
        Fornecedor::create([
            'nome' => 'João da Silva',
            'nome_fantasia' => null,
            'cpf_cnpj' => '123.456.789-00',
            'email' => 'joao.silva@example.com',
            'telefone' => '(31) 99887-7665',
            'cep' => '30160031',
            'logradouro' => 'Rua Santa Rita Durão',
            'numero' => '500',
            'complemento' => 'Apto 10',
            'bairro' => 'Funcionários',
            'cidade' => 'Belo Horizonte',
            'estado' => 'MG'
        ]);

        // Fornecedor PF Básico
        Fornecedor::create([
            'nome' => 'Maria Oliveira',
            'nome_fantasia' => null,
            'cpf_cnpj' => '000.111.222-33',
            'email' => 'maria.o@example.com',
            'telefone' => '(41) 3030-4040',
            'cep' => '80060010',
            'logradouro' => 'Rua XV de Novembro',
            'numero' => '1000',
            'complemento' => null,
            'bairro' => 'Centro',
            'cidade' => 'Curitiba',
            'estado' => 'PR'
        ]);

        // Fornecedor com o campo 'endereco' antigo preenchido (se ainda usar)
        // Isso é útil se você tem registros mais antigos que só usavam esse campo.
        Fornecedor::create([
            'nome' => 'Fornecedor Antigo Ltda.',
            'nome_fantasia' => 'Legado Solutions',
            'cpf_cnpj' => '11.222.333/0001-44',
            'email' => 'antigo@legado.com',
            'telefone' => '(51) 5555-4444',
            'endereco' => 'Avenida Principal, 123 - Centro - Porto Alegre - RS', // Campo antigo
            'cep' => null, // Novos campos nulos
            'logradouro' => null,
            'numero' => null,
            'complemento' => null,
            'bairro' => null,
            'cidade' => null,
            'estado' => null
        ]);
    }
}