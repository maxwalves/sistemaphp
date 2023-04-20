<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeiculoParanacidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'marca',
        'modelo',
        'placa',
    ];

    public function avs()
    {
        return $this->hasMany('App\Models\Av');
    }
}
