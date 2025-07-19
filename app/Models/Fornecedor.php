<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'email',
        'telefone',
        'nome_fantasia',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];
    public function getCpfCnpjAttribute($value)
    {
        if (empty($value)) {
            return '';
        }

        $cleanValue = preg_replace('/[^0-9]/', '', $value);

        if (strlen($cleanValue) == 11) {
            return substr($cleanValue, 0, 3) . '.' .
                   substr($cleanValue, 3, 3) . '.' .
                   substr($cleanValue, 6, 3) . '-' .
                   substr($cleanValue, 9, 2);
        } elseif (strlen($cleanValue) == 14) {
            return substr($cleanValue, 0, 2) . '.' .
                   substr($cleanValue, 2, 3) . '.' .
                   substr($cleanValue, 5, 3) . '/' .
                   substr($cleanValue, 8, 4) . '-' .
                   substr($cleanValue, 12, 2);
        }

        return $cleanValue;
    }

    public function getTelefoneAttribute($value)
    {
        if (empty($value)) {
            return '';
        }

        $cleanValue = preg_replace('/[^0-9]/', '', $value);

        $length = strlen($cleanValue);

        if ($length === 10) {
            return '(' . substr($cleanValue, 0, 2) . ') ' .
                   substr($cleanValue, 2, 4) . '-' .
                   substr($cleanValue, 6, 4);
        } elseif ($length === 11) {
            return '(' . substr($cleanValue, 0, 2) . ') ' .
                   substr($cleanValue, 2, 5) . '-' .
                   substr($cleanValue, 7, 4);
        }

        return $cleanValue;
    }

}