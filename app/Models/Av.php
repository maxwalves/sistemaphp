<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Av extends Model
{
    use HasFactory;

    protected $casts = [
        'expired_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'dataCriacao',
        'prioridade',
        'banco',
        'agencia',
        'conta',
        'pix',
        'comentario',
        'status',
        'valorExtra',
        'justificativaValorExtra',
        'isVeiculoProprio',
        'isVeiculoEmpresa',
        'contatos',
        'atividades',
        'conclusoes',
        'user_id',
        'objetivo_id',
    ];

    protected $dates = ['dataCriacao'];
    
    //Retorna o usuário relacionado a AV
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function objetivoViagem()
    {
        return $this->belongsTo('App\Models\ObjetivoViagem');
    }
}
