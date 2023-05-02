<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Av extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'valorExtraReais',
        'valorExtraDolar',
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
        'objetivo_id',
        'valorReais',
        'valorDolar'
    ];

    protected $dates = ['dataCriacao', 'dataAutorizacaoVeiculoProprio', 'deleted_at'];
    
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
