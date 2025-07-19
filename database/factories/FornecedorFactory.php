<?php

namespace Database\Factories;

use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class FornecedorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fornecedor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isPJ = $this->faker->boolean(50); // 50% de chance de ser PJ

        if ($isPJ) {
            $cpf_cnpj = $this->faker->cnpj(false); // CNPJ sem formatação
            $nome = $this->faker->company;
            $nomeFantasia = $this->faker->catchPhrase();
            $tipo = 'PJ';
        } else {
            $cpf_cnpj = $this->faker->cpf(false); // CPF sem formatação
            $nome = $this->faker->name;
            $nomeFantasia = null; // CPF não tem nome fantasia
            $tipo = 'PF';
        }

        // Gerar endereço completo
        $cep = $this->faker->postcode;
        $logradouro = $this->faker->streetName;
        $numero = $this->faker->buildingNumber;
        $complemento = $this->faker->secondaryAddress;
        $bairro = $this->faker->citySuffix;
        $cidade = $this->faker->city;
        $estado = $this->faker->stateAbbr; // Sigla do estado

        // Construir o campo 'endereco' legado
        $enderecoCompleto = $logradouro . ', ' . $numero;
        if ($complemento) {
            $enderecoCompleto .= ' - ' . $complemento;
        }
        $enderecoCompleto .= ' - ' . $bairro . ' - ' . $cidade . ' - ' . $estado . ' - ' . $cep;


        return [
            'tipo' => $tipo,
            'nome' => $nome,
            'nome_fantasia' => $nomeFantasia,
            'cpf_cnpj' => $cpf_cnpj,
            'email' => $this->faker->unique()->safeEmail,
            'telefone' => $this->faker->numerify('(##) #####-####'),
            'cep' => $cep,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'complemento' => $complemento,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'endereco' => $enderecoCompleto, // Campo 'endereco' para compatibilidade
        ];
    }
}