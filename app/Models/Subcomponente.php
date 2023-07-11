<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Subcomponente extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'nome, componente_id'];
}
