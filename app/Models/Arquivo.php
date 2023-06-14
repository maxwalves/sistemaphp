<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arquivo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'nome',
        'texto',
        'anexo',
        'data',
        'textoHtml',
        'tipo'
    ];

    protected $dates = ['deleted_at', 'data'];
}
