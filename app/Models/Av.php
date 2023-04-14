<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Av extends Model
{
    use HasFactory;

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
