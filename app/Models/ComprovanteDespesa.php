<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprovanteDespesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'anexoDespesa',
        'descricao',
        'valorReais',
        'valorDolar',
        'dataOcorrencia',
        'av_id'
    ];

    protected $dates = ['dataOcorrencia'];
}
