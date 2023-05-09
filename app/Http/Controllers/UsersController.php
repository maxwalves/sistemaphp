<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use PHPUnit\TextUI\Configuration\Php;

class UsersController extends Controller
{
    

    public function index()
    {
        $user = auth()->user();
        $userEncontrado = User::findOrFail($user->id);
        $permission = Permission::where('name', 'view users')->first();

        $users = User::all();

        if ($userEncontrado->hasPermissionTo($permission)) {
            // O usuário tem a permissão 'view users', permita a visualização de usuários
            return view('users.users', ['users' => $users, 'user'=> $user]);
        } else {
            // O usuário não tem a permissão 'view users', redirecionar para a página de erro 403
            return abort(403, 'Unauthorized');
        }
    }

    public function users()
    {
        $user = auth()->user();
        $userEncontrado = User::findOrFail($user->id);

        //$permission = Permission::where('name', 'view users')->first();
        //$userEncontrado->givePermissionTo($permission); //Criar uma tela de gerenciamento de perfil para o usuário

        try {
            if (Gate::authorize('view users', $userEncontrado)) {
                $users = User::all();
                return view('users.users', ['users' => $users, 'user'=> $user]);
        }
        } catch (\Throwable $th) {
            return view('unauthorized', ['user'=> $user]);
        }

    }

    public function create()
    {
        $user = auth()->user();
        return view('users.createUser', ['user'=> $user]);
    }

    public function naoAutorizado()
    {
        return view('unauthorized');
    }

    public function store(Request $request)
    {

        $regras = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ];
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        $request->validate($regras, $mensagens);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password) ;
    
        $user->save();

        return redirect('/users/users')->with('msg', 'Usuário criado com sucesso!');
    }

    public function show($id)
    {
        $user = auth()->user();
        $users = User::findOrFail($id);

        return view('users.show', ['users' => $users, 'user'=> $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();

        return redirect('/users/users')->with('msg', 'Usuário excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $usuarioEditado = User::findOrFail($id);

        return view('users.editUser', ['usuarioEditado' => $usuarioEditado, 'user'=> $user]);
    }

    public function editPerfil($id)
    {
        $user = auth()->user();

        $usuarioEditar = User::findOrFail($id);

        $permission1 = Permission::where('name', 'view users')->first();
        $permission2 = Permission::where('name', 'edit users')->first();
        $permission3 = Permission::where('name', 'aprov avs gestor')->first();
        $permission4 = Permission::where('name', 'aprov avs secretaria')->first();
        $permission5 = Permission::where('name', 'aprov avs financeiro')->first();
        $permission6 = Permission::where('name', 'aprov avs frota')->first();
        $permission7 = Permission::where('name', 'aprov avs diretoria')->first();

        $dados = [];

        try {
            $dados += ["permission1"=> ($usuarioEditar->hasPermissionTo($permission1) ? "true" : "false")];
    
        } catch (\Throwable $th) {
            $dados += ["permission1"=> "false"];
        }
        try {
            $dados += ["permission2"=> ($usuarioEditar->hasPermissionTo($permission2) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission2"=> "false"];
        }
        try {
            $dados += ["permission3"=> ($usuarioEditar->hasPermissionTo($permission3) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission3"=> "false"];
        }
        try {
            $dados += ["permission4"=> ($usuarioEditar->hasPermissionTo($permission4) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission4"=> "false"];
        }
        try {
            $dados += ["permission5"=> ($usuarioEditar->hasPermissionTo($permission5) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission5"=> "false"];
        }
        try {
            $dados += ["permission6"=> ($usuarioEditar->hasPermissionTo($permission6) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission6"=> "false"];
        }
        try {
            $dados += ["permission7"=> ($usuarioEditar->hasPermissionTo($permission7) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission7"=> "false"];
        }
        
        return view('users.editPerfil', ['usuarioEditar' => $usuarioEditar, 'user'=> $user, 'dados' => $dados]);
    }
    public function updatePerfil($id, $ordem)
    {
        $user = auth()->user();

        $usuarioEditar = User::findOrFail($id);

        $permission1 = Permission::where('name', 'view users')->first();
        $permission2 = Permission::where('name', 'edit users')->first();
        $permission3 = Permission::where('name', 'aprov avs gestor')->first();
        $permission4 = Permission::where('name', 'aprov avs secretaria')->first();
        $permission5 = Permission::where('name', 'aprov avs financeiro')->first();
        $permission6 = Permission::where('name', 'aprov avs frota')->first();
        $permission7 = Permission::where('name', 'aprov avs diretoria')->first();

        $dados = [];
        
        if($ordem == 'desativarAdmin')
        {
            $usuarioEditar->revokePermissionTo($permission1);
        }
        else if($ordem == 'desativarGestor')
        {
            $usuarioEditar->revokePermissionTo($permission3);
        }
        else if($ordem == 'desativarSecretaria')
        {
            $usuarioEditar->revokePermissionTo($permission4);
        }
        else if($ordem == 'desativarFinanceiro')
        {
            $usuarioEditar->revokePermissionTo($permission5);
        }
        else if($ordem == 'desativarFrota')
        {
            $usuarioEditar->revokePermissionTo($permission6);
        }
        else if($ordem == 'desativarDiretoriaExecutiva')
        {
            $usuarioEditar->revokePermissionTo($permission7);
        }//----------------------------------------------------------------------------------
        else if($ordem == 'ativarAdmin')
        {
            $usuarioEditar->givePermissionTo($permission1);
        }
        else if($ordem == 'ativarGestor')
        {
            $usuarioEditar->givePermissionTo($permission3);
        }
        else if($ordem == 'ativarSecretaria')
        {
            $usuarioEditar->givePermissionTo($permission4);
        }
        else if($ordem == 'ativarFinanceiro')
        {
            $usuarioEditar->givePermissionTo($permission5);
        }
        else if($ordem == 'ativarFrota')
        {
            $usuarioEditar->givePermissionTo($permission6);
        }
        else if($ordem == 'ativarDiretoriaExecutiva')
        {
            $usuarioEditar->givePermissionTo($permission7);
        }

        try {
            $dados += ["permission1"=> ($usuarioEditar->hasPermissionTo($permission1) ? "true" : "false")];
    
        } catch (\Throwable $th) {
            $dados += ["permission1"=> "false"];
        }
        try {
            $dados += ["permission2"=> ($usuarioEditar->hasPermissionTo($permission2) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission2"=> "false"];
        }
        try {
            $dados += ["permission3"=> ($usuarioEditar->hasPermissionTo($permission3) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission3"=> "false"];
        }
        try {
            $dados += ["permission4"=> ($usuarioEditar->hasPermissionTo($permission4) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission4"=> "false"];
        }
        try {
            $dados += ["permission5"=> ($usuarioEditar->hasPermissionTo($permission5) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission5"=> "false"];
        }
        try {
            $dados += ["permission6"=> ($usuarioEditar->hasPermissionTo($permission6) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission6"=> "false"];
        }
        try {
            $dados += ["permission7"=> ($usuarioEditar->hasPermissionTo($permission7) ? "true" : "false")];
        } catch (\Throwable $th) {
            $dados += ["permission7"=> "false"];
        }

        return view('users.editPerfil', ['usuarioEditar' => $usuarioEditar, 'user'=> $user, 'dados' => $dados]);
    }

    public function update(Request $request)
    {
        $data = array(
            "name"=> $request->name,
            "email"=> $request->email,
            "password"=> Hash::make($request->password)
        );

        $regras = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'confirmed'],
        ];
        $mensagens = [
            'required' => 'Este campo não pode estar em branco',
        ];
        $request->validate($regras, $mensagens);
        
        User::findOrFail($request->id)->update($data);

        return redirect('/users/users')->with('msg', 'Usuário editado com sucesso!');
    }
}
