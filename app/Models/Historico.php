<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    use HasFactory;

    protected $casts = [
        'expired_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'dataOcorrencia',
        'tipoOcorrencia',
        'comentario',
        'perfilDonoComentario',
        'usuario_id',
        'usuario_comentario_id',
        'av_id'
    ];

    protected $dates = ['dataOcorrencia'];
}
