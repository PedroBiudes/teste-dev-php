<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fornecedor; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Fornecedor::query();

        // Lógica de filtro (se precisar)
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }
        if ($request->filled('cpf_cnpj')) {
            $query->where('cpf_cnpj', 'like', '%' . $request->cpf_cnpj . '%');
        }

        $fornecedores = $query->orderBy('nome')->paginate(10); // Paginação
        return response()->json($fornecedores);
    }
    
    public function criarFornecedor(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|unique:fornecedores,cpf_cnpj',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
        ]);

        $fornecedor = Fornecedor::create($validated);
        return response()->json($fornecedor, Response::HTTP_CREATED);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|unique:fornecedores,cpf_cnpj',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
        ]);

        $fornecedor = Fornecedor::create($validated);
        return response()->json($fornecedor, Response::HTTP_CREATED); // 201 Created
    }

    public function show(Fornecedor $fornecedor) // Usando Route Model Binding
    {
        return response()->json($fornecedor);
    }

    public function update(Request $request, Fornecedor $fornecedor) // Usando Route Model Binding
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|unique:fornecedores,cpf_cnpj,' . $fornecedor->id, // Exclui o próprio ID
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'endereco' => 'nullable|string',
        ]);

        $fornecedor->update($validated);
        return response()->json($fornecedor);
    }

    public function destroy(Fornecedor $fornecedor) // Usando Route Model Binding
    {
        $fornecedor->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204 No Content
    }

    // Método para a busca na BrasilAPI (exemplo)
    // Você precisará instalar guzzlehttp/guzzle: composer require guzzlehttp/guzzle
    // public function buscarPorCnpj(Request $request)
    // {
    //     $cnpj = $request->query('cnpj'); // Pega o CNPJ da query string (ex: ?cnpj=...)
    //     if (!$cnpj) {
    //         return response()->json(['message' => 'CNPJ é obrigatório'], Response::HTTP_BAD_REQUEST);
    //     }

    //     $client = new \GuzzleHttp\Client();
    //     try {
    //         $response = $client->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
    //         $data = json_decode($response->getBody()->getContents(), true);
    //         return response()->json($data);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Erro ao buscar CNPJ: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}