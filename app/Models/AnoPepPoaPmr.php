<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnoPepPoaPmr extends Model
{
    use HasFactory;

    protected $table = 'ano_peppoa_pmr';

    protected $fillable = [
        'id',
        'ano_id', 
        'justificativaNaoAtingimento',
        'metaFisicaBid',
        'unidadeMedidaBid',
        'metaFisicaPrcid',
        'unidadeMedidaPrcid',
        'metaFinanceiraBid',
        'metaFinanceiraPrcid',
        'peppoa_pmr_id'
    ];
}
