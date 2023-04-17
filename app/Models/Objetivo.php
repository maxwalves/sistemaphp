<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    use HasFactory;

    protected $casts = [
        'expired_at' => 'datetime',
        'last_active_at' => 'datetime',
    ];

    protected $fillable = [
        'id',
        'nomeObjetivo',
        'outros',
    ];
    
    public function avs()
    {
        return $this->hasMany('App\Models\Av');
    }
}
