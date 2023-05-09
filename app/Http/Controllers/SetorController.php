<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setor;
use App\Models\User;


class SetorController extends Controller
{
    public function index()
    {

    }

    public function setores()
    {
        $user = auth()->user();

        $setores = Setor::all();
        $users = User::all();
        
        return view('setores.setores', ['setores' => $setores, 'users' => $users, 'user'=> $user]);
    }

    public function create()
    {
        $user = auth()->user();
        $users = User::all();
        return view('setores.createSetor', ['user'=> $user, 'users' => $users]);
    }

    public function naoAutorizado()
    {
        return view('unauthorized');
    }

    public function store(Request $request)
    {

        $regras = [
            'nome' => ['required', 'string', 'max:255']
        ];
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        $request->validate($regras, $mensagens);

        $setor = new Setor();

        $setor->nome = $request->nome;

        $userEncontrado = User::findOrFail($request->chefe_id);
        $setor->chefe_id = $userEncontrado->id;
        $setor->save();

        return redirect('/setores/setores')->with('msg', 'Setor criado com sucesso!');
    }

    public function funcSetor($id){
        
        $user = auth()->user();

        $setor = Setor::findOrFail($id);
        $users = User::all();
        $usersFiltrado = [];
        foreach($users as $u){
            if($u->setor_id == $setor->id){
                array_push($usersFiltrado, $u);
            }
        }
        
        return view('setores.funcSetor', ['setor' => $setor, 'usersFiltrado' => $usersFiltrado, 'users' => $users, 'user'=> $user]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $setor = User::findOrFail($id);

        return view('setores.show', ['setor' => $setor, 'user'=> $user]);
    }

    public function destroy($id)
    {
        $setor = Setor::findOrFail($id)->delete();

        return redirect('/setores/setores')->with('msg', 'Setor excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $setor = Setor::findOrFail($id);
        $users = User::all();

        return view('setores.editSetor', ['setor' => $setor, 'users' => $users, 'user'=> $user]);
    }

    public function update(Request $request)
    {
        
        $data = array(
            "nome"=> $request->nome,
            "chefe_id"=> $request->chefe_id
        );

        $regras = [
            'nome' => ['required', 'string', 'max:255']
        ];
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        $request->validate($regras, $mensagens);
        
        Setor::findOrFail($request->id)->update($data);

        return redirect('/setores/setores')->with('msg', 'Setor editado com sucesso!');
    }
}
