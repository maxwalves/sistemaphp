<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoPc extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'valorReais',
        'valorDolar',
        'ocorrencia',
        'comentario',
        'anexoRelatorio',
        'av_id',
        'dataOcorrencia'
    ];

    protected $dates = ['dataOcorrencia'];
}
