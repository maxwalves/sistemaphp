<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    use HasFactory;

    protected $table = 'medicoes';

    protected $fillable = [
        'id',
        'nome_municipio',
        'municipio_id',
        'numero_projeto',
        'numero_lote',
        'numero_medicao',
        'av_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function av()
    {
        return $this->belongsTo('App\Models\Av');
    }
}
