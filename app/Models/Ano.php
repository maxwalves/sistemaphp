<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Ano extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'ano'];
}
