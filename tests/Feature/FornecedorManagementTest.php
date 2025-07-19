<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Fornecedor;

class FornecedorManagementTest extends TestCase
{
    use RefreshDatabase; // Isso garante que o banco de dados seja migrado e resetado para cada método de teste

    /** @test */
    public function a_fornecedor_can_be_created()
    {
        $this->withoutExceptionHandling(); // Ajuda a ver erros detalhados durante o desenvolvimento do teste

        $response = $this->post('/fornecedores', [ // URL da rota de store
            'tipo' => 'PJ',
            'nome' => 'Empresa Teste SA',
            'nome_fantasia' => 'Teste Fantasia',
            'cpf_cnpj' => '12.345.678/0001-90',
            'email' => 'teste@empresa.com',
            'telefone' => '(11) 98765-4321',
            'cep' => '01000-000',
            'logradouro' => 'Rua Teste',
            'numero' => '123',
            'complemento' => 'Apto 1',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'endereco' => 'Rua Teste, 123 - Apto 1 - Centro - São Paulo - SP - 01000-000', // Campo legado, se ainda usa
        ]);

        $response->assertStatus(302); // Espera um redirecionamento (status 302) após o sucesso
        $response->assertRedirect('/fornecedores'); // Espera que redirecione para a lista

        $this->assertCount(1, Fornecedor::all()); // Verifica se um fornecedor foi adicionado ao banco
        $this->assertDatabaseHas('fornecedores', [ // Verifica se os dados estão no banco
            'nome' => 'Empresa Teste SA',
            'cpf_cnpj' => '12.345.678/0001-90',
            'email' => 'teste@empresa.com',
        ]);
    }

    /** @test */
    public function a_fornecedor_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $fornecedor = Fornecedor::factory()->create([ // Cria um fornecedor usando uma factory (veja abaixo)
            'tipo' => 'PF',
            'nome' => 'Fornecedor Original',
            'cpf_cnpj' => '123.456.789-00',
            'email' => 'original@email.com',
            'telefone' => '(11) 1111-1111',
            'cep' => '01000-000',
            'logradouro' => 'Rua Original',
            'numero' => '1',
            'complemento' => 'Casa',
            'bairro' => 'Bairro Original',
            'cidade' => 'Cidade Original',
            'estado' => 'GO',
            'endereco' => 'Rua Original, 1 - Casa - Bairro Original - Cidade Original - GO - 01000-000',
        ]);

        $response = $this->put('/fornecedores/' . $fornecedor->id, [ // URL da rota de update
            'tipo' => 'PF',
            'nome' => 'Fornecedor Atualizado',
            'nome_fantasia' => null, // PF não tem nome fantasia
            'cpf_cnpj' => '123.456.789-00', // Manter CPF para o update
            'email' => 'atualizado@email.com',
            'telefone' => '(22) 22222-2222',
            'cep' => '02000-000',
            'logradouro' => 'Nova Rua',
            'numero' => '456',
            'complemento' => 'Apto B',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'endereco' => 'Nova Rua, 456 - Apto B - Novo Bairro - Nova Cidade - MG - 02000-000',
        ]);

        $response->assertStatus(302); // Espera um redirecionamento
        $response->assertRedirect('/fornecedores');

        $this->assertDatabaseHas('fornecedores', [ // Verifica se os dados foram atualizados no banco
            'id' => $fornecedor->id,
            'nome' => 'Fornecedor Atualizado',
            'email' => 'atualizado@email.com',
            'telefone' => '(22) 22222-2222',
            'logradouro' => 'Nova Rua',
            'cidade' => 'Nova Cidade',
        ]);
        $this->assertDatabaseMissing('fornecedores', [ // Verifica que o nome antigo não existe mais
            'nome' => 'Fornecedor Original',
        ]);
    }

    /** @test */
    public function a_fornecedor_can_be_deleted()
    {
        $this->withoutExceptionHandling();

        $fornecedor = Fornecedor::factory()->create(); // Cria um fornecedor

        $this->assertCount(1, Fornecedor::all()); // Confirma que um fornecedor existe

        $response = $this->delete('/fornecedores/' . $fornecedor->id); // URL da rota de delete

        $response->assertStatus(302); // Espera um redirecionamento
        $response->assertRedirect('/fornecedores');

        $this->assertCount(0, Fornecedor::all()); // Verifica se o fornecedor foi removido
        $this->assertDatabaseMissing('fornecedores', ['id' => $fornecedor->id]); // Verifica que não está mais no banco
    }

    /** @test */
    public function a_fornecedor_requires_a_name()
    {
        $response = $this->post('/fornecedores', [
            'tipo' => 'PJ',
            'nome' => '', // Campo inválido
            'cpf_cnpj' => '12.345.678/0001-90',
            'email' => 'teste@empresa.com',
            'telefone' => '(11) 98765-4321',
        ]);

        $response->assertSessionHasErrors('nome'); // Espera que a sessão tenha erros para o campo 'nome'
    }

    /** @test */
    public function a_fornecedor_requires_a_valid_cpf_cnpj()
    {
        $response = $this->post('/fornecedores', [
            'tipo' => 'PF',
            'nome' => 'Fornecedor Teste',
            'cpf_cnpj' => '123.456.789-0', // CPF inválido (menos dígitos)
            'email' => 'teste@empresa.com',
            'telefone' => '(11) 98765-4321',
        ]);

        $response->assertSessionHasErrors('cpf_cnpj');
    }

    // Adicione mais testes para validações, campos específicos, etc.
    // Ex: Teste para nome_fantasia apenas para PJ, validação de CEP, etc.
}