<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    use HasFactory;

    protected $casts = [
        'expired_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'cidadeSaida',
        'dataHoraSaida',
        'cidadeChegada',
        'dataHoraChegada',
        'isReservaHotel',
        'isViagemInternacional',
        'isOnibusLeito',
        'isOnibusConvencional',
        'isVeiculoProprio',
        'isVeiculoEmpresa',
        'isAereo',
        'av_id',
        'veiculoProprio_id',
        'veiculoParanacidade_id'
    ];

    protected $dates = ['dataHoraSaida', 'dataHoraChegada'];

    public function av()
    {
        return $this->belongsTo('App\Models\Av');
    }
}
