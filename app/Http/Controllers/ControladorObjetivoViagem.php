<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objetivo;

class ControladorObjetivoViagem extends Controller
{
    public function indexJson()
    {
        $objetivos = Objetivo::all();
        return json_encode($objetivos);
    }
}
