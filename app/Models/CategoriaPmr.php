<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class CategoriaPmr extends Model
{
    use HasFactory;

    protected $table = 'categorias_pmr';

    protected $fillable = ['id', 'nome', 'codigo'];
}
