<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faturamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'comprador',
        'descricao',
        'preco_unit',
        'quantidade',
        'endereco',
        'fornecedor',
        'file_id'
    ];

    public function file()
    {
        return $this->hasOne(File::class,'file_id','id');
    }
}
