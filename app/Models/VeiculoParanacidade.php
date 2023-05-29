<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoParanacidade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'marca',
        'modelo',
        'placa',
        'isAtivo',
        'observacao'
    ];

    protected $dates = ['deleted_at'];

    public function avs()
    {
        return $this->hasMany('App\Models\Av');
    }
}
