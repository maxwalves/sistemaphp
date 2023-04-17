<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Av;
use App\Models\Objetivo;
use DateTime;

class ControladorAv extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $avs = $user->avs;

        $search = request('search');

        if ($search) {
            $avs = $avs::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        }

        return view('welcome', ['avs' => $avs, 'search' => $search]);
    }

    public function avs()
    {
        $user = auth()->user();
        $avs = $user->avs;
        return view('avs.avs', ['avs' => $avs]);
    }

    public function create()
    {
        $objetivos = Objetivo::all();
        return view('avs.create', ['objetivos' => $objetivos]);
    }

    public function store(Request $request)
    {
        $av = new Av();

        $av->objetivo_id = $request->objetivo_id;
        $av->prioridade = $request->prioridade;
        $av->dataCriacao = new DateTime();
        $av->banco = $request->banco;
        $av->agencia = $request->agencia;
        $av->conta = $request->conta;
        $av->pix = $request->pix;
        $av->comentario = $request->comentario;
        $av->status = "Aguardando envio para o Gestor";

        $user = auth()->user();
        $av->user_id = $user->id;
    
        $av->save();

        return redirect('/')->with('msg', 'AV criada com sucesso!');
    }

    public function show($id)
    {
        $av = Av::findOrFail($id);

        //$avOwner = Av::where('id', $av->user_id)->first()->toArray();

        return view('avs.show', ['av' => $av]);
    }

    public function dashboard()
    {
        $search = request('search');

        if ($search) {
            $avs = Av::where([
                ['title', 'like', '%'.$search. '%']
            ])->get();
        } else {
            $avs = Av::all();
        }

        $user = auth()->user();
        $avs = $user->avs;

        return view('avs.dashboard', ['avs' => $avs], ['search' => $search]);
    }

    public function destroy($id)
    {
        $av = Av::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'av excluído com sucesso!');
    }

    public function edit($id)
    {
        $objetivos = Objetivo::all();

        $user = auth()->user();

        $av = Av::findOrFail($id);

        if($user->id != $av->user->id) {
            return redirect('/dashboard')->with('msg', 'Você não tem permissão para editar esta av!');
        }

        return view('avs.edit', ['av' => $av, 'objetivos' => $objetivos]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        //Image upload
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            
            $requestImage->move(public_path('img/avs'), $imageName);

            $data['image'] = $imageName;

        }

        Av::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'av editado com sucesso!');
    }
}
