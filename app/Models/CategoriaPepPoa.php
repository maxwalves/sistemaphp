<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class CategoriaPepPoa extends Model
{
    use HasFactory;

    protected $table = 'categorias_pep_poa';

    protected $fillable = ['id', 'nome', 'codigo', 'subcomponente_id'];
}
