<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fornecedor; 
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Rules\CpfCnpjValidator;
use Illuminate\Support\Facades\Http;   
use Illuminate\Support\Facades\Cache; 

class FornecedorController extends Controller
{
    public function buscarDadosCnpj(string $cnpj)
    {
        $cnpjLimpo = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpjLimpo) != 14) {
            return response()->json(['error' => 'CNPJ inválido. Digite 14 dígitos numéricos.'], 400);
        }

        $cacheKey = 'cnpj_data_' . $cnpjLimpo;
        $ttl = now()->addHours(24);

        $mappedData = Cache::remember($cacheKey, $ttl, function () use ($cnpjLimpo) {
            try {
                $response = Http::timeout(10)->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpjLimpo}");

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['message']) && $data['message'] === 'CNPJ not found') {
                         return ['error' => 'CNPJ não encontrado na base de dados da BrasilAPI.', 'status' => 404];
                    }
                    if (isset($data['type']) && $data['type'] === 'cnpj_nao_encontrado') {
                         return ['error' => 'CNPJ não encontrado na base de dados da BrasilAPI.', 'status' => 404];
                    }

                    return [
                        'razao_social' => $data['razao_social'] ?? '',
                        'nome_fantasia' => $data['nome_fantasia'] ?? '',
                        'cep' => $data['cep'] ?? '',
                        'logradouro' => $data['logradouro'] ?? '',
                        'numero' => $data['numero'] ?? '',
                        'complemento' => $data['complemento'] ?? '',
                        'bairro' => $data['bairro'] ?? '',
                        'cidade' => $data['municipio'] ?? '',
                        'estado' => $data['uf'] ?? '',
                        'telefone' => $data['ddd_telefone_1'] ?? ($data['ddd_telefone_2'] ?? ''),
                        'email' => $data['email'] ?? '',
                        'status' => 200
                    ];
                } elseif ($response->clientError()) {
                    $errorData = $response->json();
                    return ['error' => $errorData['message'] ?? 'Erro de cliente ao consultar CNPJ na BrasilAPI.', 'status' => $response->status(), 'details' => $errorData];
                } else {
                    return ['error' => 'Erro interno ao consultar CNPJ na BrasilAPI. Tente novamente mais tarde.', 'status' => 500];
                }
            } catch (\Exception $e) {
                return ['error' => 'Não foi possível conectar à BrasilAPI. Verifique sua conexão ou tente novamente.', 'status' => 500, 'details' => $e->getMessage()];
            }
        });

        if (isset($mappedData['error'])) {
            return response()->json(['error' => $mappedData['error'], 'details' => $mappedData['details'] ?? null], $mappedData['status']);
        }
        return response()->json($mappedData);
    }

    /**
     * Busca dados de CEP na BrasilAPI, com cache.
     *
     * @param string $cep
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarDadosCep(string $cep)
    {
        $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cepLimpo) != 8) {
            return response()->json(['error' => 'CEP inválido. Digite 8 dígitos numéricos.'], 400);
        }

        $cacheKey = 'cep_data_' . $cepLimpo;
        $ttl = now()->addDays(7);

        $mappedData = Cache::remember($cacheKey, $ttl, function () use ($cepLimpo) {
            try {
                $response = Http::timeout(5)->get("https://brasilapi.com.br/api/cep/v1/{$cepLimpo}");

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['message']) && $data['message'] === 'CEP not found') {
                         return ['error' => 'CEP não encontrado.', 'status' => 404];
                    }

                    return [
                        'cep' => $data['cep'] ?? '',
                        'logradouro' => $data['street'] ?? '',
                        'bairro' => $data['neighborhood'] ?? '',
                        'cidade' => $data['city'] ?? '',
                        'estado' => $data['state'] ?? '',
                        'status' => 200
                    ];
                } elseif ($response->clientError()) {
                    $errorData = $response->json();
                    return ['error' => $errorData['message'] ?? 'Erro ao consultar CEP na BrasilAPI.', 'status' => $response->status(), 'details' => $errorData];
                } else {
                    return ['error' => 'Erro interno ao consultar CEP na BrasilAPI. Tente novamente mais tarde.', 'status' => 500];
                }
            } catch (\Exception $e) {
                return ['error' => 'Não foi possível conectar à BrasilAPI. Verifique sua conexão ou tente novamente.', 'status' => 500, 'details' => $e->getMessage()];
            }
        });

        if (isset($mappedData['error'])) {
            return response()->json(['error' => $mappedData['error'], 'details' => $mappedData['details'] ?? null], $mappedData['status']);
        }
        return response()->json($mappedData);
    }
    protected function clearFornecedoresCache()
    {
        Cache::forget('fornecedores_list_*'); 
        Cache::flush();
    }
    public function index(Request $request)
    {
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        $search = $request->input('search');
        $page = $request->input('page', 1);

        $cacheKey = 'fornecedores_list_' . md5(json_encode([
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'search' => $search,
        ]));

        $fornecedoresCollection = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($sortBy, $sortOrder, $search) {
            $query = Fornecedor::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('cpf_cnpj', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('telefone', 'like', '%' . $search . '%')
                    ->orWhere('endereco', 'like', '%' . $search . '%')
                    ->orWhere('nome_fantasia', 'like', '%' . $search . '%')
                    ->orWhere('cep', 'like', '%' . $search . '%')
                    ->orWhere('logradouro', 'like', '%' . $search . '%')
                    ->orWhere('bairro', 'like', '%' . $search . '%')
                    ->orWhere('cidade', 'like', '%' . $search . '%')
                    ->orWhere('estado', 'like', '%' . $search . '%');
                });
            }

            return $query->orderBy($sortBy, $sortOrder)->get();
        });

        $fornecedores = new \Illuminate\Pagination\LengthAwarePaginator(
            $fornecedoresCollection->forPage($page, 10),
            $fornecedoresCollection->count(),
            10,
            $page,
            ['path' => $request->url(), 'query' => $request->query()] 
        );

        return view('index', compact('fornecedores', 'sortBy', 'sortOrder', 'search'));
    }

    public function showBlade($id)
    {
        $fornecedor = Fornecedor::find($id);

        if (!$fornecedor) {
            return redirect('/fornecedores')->with('error', 'Fornecedor não encontrado.');
        }

        return view('show', compact('fornecedor'));
    }
    
    public function createFornecedorCpf()
    {
        return view('create_cpf');
    }
    public function createFornecedorCNPJ()
    {
        return view('create_cnpj');
    }

    public function store(Request $request)
    {
        $rules = [
            'nome' => ['required', 'string', 'max:255'],
            'cpf_cnpj' => ['required', new CpfCnpjValidator(), 'unique:fornecedores,cpf_cnpj'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cep' => ['nullable', 'string', 'max:9'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:50'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:2'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);
        $validated['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $validated['cpf_cnpj']);

        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/[^0-9]/', '', $validated['telefone']);
        }

        Fornecedor::create($validated);
        $this->clearFornecedoresCache();

        return redirect()->route('index')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function show(Fornecedor $fornecedor)
    {
        return response()->json($fornecedor);
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $rules = [
            'nome' => ['required', 'string', 'max:255'],
            'cpf_cnpj' => ['required', new CpfCnpjValidator(), 'unique:fornecedores,cpf_cnpj'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cep' => ['nullable', 'string', 'max:9'],
            'logradouro' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:50'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cidade' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:2'],
            'nome_fantasia' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);
 
        $validated['cpf_cnpj'] = preg_replace('/[^0-9]/', '', $validated['cpf_cnpj']);

        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/[^0-9]/', '', $validated['telefone']);
        }

        $fornecedor->update($validated);
        $this->clearFornecedoresCache();

        return redirect()->route('index')->with('success', 'Fornecedor atualizado com sucesso!');
   
    }   
    public function editFornecedor(Fornecedor $fornecedor) 
    {
        return view('edit', compact('fornecedor'));
    }

    public function destroy(Fornecedor $fornecedor) 
    {
        $fornecedor->delete();
        $this->clearFornecedoresCache();
        return redirect()->route('index')->with('success', 'Fornecedor excluído com sucesso!');
    }
}