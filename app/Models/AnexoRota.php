<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnexoRota extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'anexoHotel',
        'anexoTransporte',
        'usuario_id',
        'rota_id',
        'descricao',
        'av_id'
    ];

    public function rota()
    {
        return $this->belongsTo('App\Models\Rota');
    }
}
