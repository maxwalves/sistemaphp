<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnexoFinanceiro extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'anexoFinanceiro',
        'descricao',
        'av_id'
    ];

    public function av()
    {
        return $this->belongsTo('App\Models\Av');
    }
}
