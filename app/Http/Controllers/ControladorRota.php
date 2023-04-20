<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\Av;
use Illuminate\Http\Request;

class ControladorRota extends Controller
{
    public function index($id)//Arrumar rota no web.php e fazer com que a view que está mandando envie a id da AV
    {
        $av = Av::findOrFail($id);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV

        $search = request('search');

        if ($search) {
            $rotas = $rotas::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        }

        return view('rotas.rotas', ['rotas' => $rotas, 'search' => $search]); 
    }

    public function rotas($id)//Arrumar rota no web.php e fazer com que a view que está mandando envie a id da AV
    {
        $av = Av::findOrFail($id);//Busca a AV com base no ID
        $rotas = $av->rotas;//Busca as rotas da AV
        return view('rotas.rotas', ['rotas' => $rotas]);
    }

    public function create()
    {
        $user = auth()->user();
        $veiculosProprios = $user->veiculosProprios;
        
        return view('rotas.createRota', ['veiculosProprios' => $veiculosProprios]);
    }

    public function store(Request $request)
    {

        $regras = [
            'cidadeSaida' => 'required',
            'dataHoraSaida' => 'required',
            'cidadeChegada' => 'required',
            'dataHoraChegada' => 'required',
        ];
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        $request->validate($regras, $mensagens);

        $rota = new Rota();

        //DADOS DE SAÍDA/CHEGADA
        $rota->cidadeSaida = $request->cidadeSaida;
        $rota->dataHoraSaida = $request->dataHoraSaida;
        $rota->cidadeChegada = $request->cidadeChegada;
        $rota->dataHoraChegada = $request->dataHoraChegada;

        //DADOS DE TRANSPORTE
        $rota->isReservaHotel = $request->isReservaHotel;
        $rota->isViagemInternacional = $request->isViagemInternacional;
        $rota->isOnibusLeito = $request->isOnibusLeito;
        $rota->isOnibusConvencional = $request->isOnibusConvencional;
        $rota->isVeiculoProprio = $request->isVeiculoProprio;
        $rota->isVeiculoEmpresa = $request->isVeiculoEmpresa;
        $rota->isAereo = $request->isAereo;


        //CHAVES ESTRANGEIRAS
        $rota->av_id = $request->av_id;
        $rota->veiculoProprio_id = $request->veiculoProprio_id;
        $rota->veiculoParanacidade_id = $request->veiculoParanacidade_id;
    
        $rota->save();

        return redirect('/rotas/rotas')->with('msg', 'Rota criada com sucesso!');
    }

    public function show($id)
    {
        $rota = Rota::findOrFail($id);

        //$avOwner = Av::where('id', $av->user_id)->first()->toArray();

        return view('rotas.show', ['rota' => $rota]);
    }

    public function destroy($id)
    {
        $rota = Rota::findOrFail($id)->delete();

        return redirect('/rotas/rotas')->with('msg', 'Rota excluída com sucesso!');
    }

    public function edit($id)
    {
        $rota = Rota::findOrFail($id);

        return view('rotas.editRota', ['rota' => $rota]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        Rota::findOrFail($request->id)->update($data);

        return redirect('/rotas/rotas')->with('msg', 'Rota editada com sucesso!');
    }
}
