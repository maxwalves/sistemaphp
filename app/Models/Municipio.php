<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'executor',
        'nomeSam',
        'nomeDSS',
        'assMun',
        'erprcid',
        'cnpj',
        'IBGE',
        'cdCredorSefa',
        'cdTSE',
        'regiaoMetrop',
        'mrae',
        'orgaoProtocolo',
        'localPendencia'
    ];
}
