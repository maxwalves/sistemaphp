<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PepPoaPmr extends Model
{
    use HasFactory;

    protected $table = 'peppoa_pmr';

    protected $fillable = [
        'id',
        'categoriaPeppoa_id', 
        'categoriaPmr_id',
        'codigoBid'
    ];
}
