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
        'contatos',
        'atividades',
        'conclusoes',
        'isVeiculoProprioAutorizado',
        'dataAutorizacaoVeiculoProprio',
        'assinaturaDiretoriaExecutiva',
        'usuarioDiretoriaExecutiva',
        'outroObjetivo',
        'user_id',
        'objetivo_id'
    ];

    protected $dates = ['dataCriacao', 'dataAutorizacaoVeiculoProprio'];
    
    //Retorna o usuário relacionado a AV
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function objetivoViagem()
    {
        return $this->belongsTo('App\Models\ObjetivoViagem');
    }

    public function rotas()
    {
        return $this->hasMany('App\Models\Rota');
    }
}
