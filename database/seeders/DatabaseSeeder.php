<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Ativar isso quando precisar fazer seed de países, estados e cidades
        //$this->call(LocationDatabaseSeeder::class);

        $adminRole = Role::create(['name' => 'admin']);
        $gestorRole = Role::create(['name' => 'gestor']);
        $secretariaRole = Role::create(['name' => 'secretaria']);
        $financeiroRole = Role::create(['name' => 'financeiro']);
        $frotaRole = Role::create(['name' => 'frota']);
        $diretoriaRole = Role::create(['name' => 'diretoria']);
        

        $viewUsersPermission = Permission::create(['name' => 'view users']);
        $editUsersPermission = Permission::create(['name' => 'edit users']);
        $gerenciarAvsPendentesAprovacaoGestor = Permission::create(['name' => 'aprov avs gestor']);
        $gerenciarAvsPendentesReservaSecretaria = Permission::create(['name' => 'aprov avs secretaria']);
        $gerenciarAvsPendentesAprovacaoFinanceiro = Permission::create(['name' => 'aprov avs financeiro']);
        $gerenciarAvsPendentesReservaFrota = Permission::create(['name' => 'aprov avs frota']);
        $gerenciarAvsPendentesDiretoria = Permission::create(['name' => 'aprov avs diretoria']);

        $adminRole->givePermissionTo([$viewUsersPermission, $editUsersPermission]);
        $gestorRole->givePermissionTo([$gerenciarAvsPendentesAprovacaoGestor]);
        $secretariaRole->givePermissionTo([$gerenciarAvsPendentesReservaSecretaria]);
        $financeiroRole->givePermissionTo([$gerenciarAvsPendentesAprovacaoFinanceiro]);
        $frotaRole->givePermissionTo([$gerenciarAvsPendentesReservaFrota]);
        $diretoriaRole->givePermissionTo([$gerenciarAvsPendentesDiretoria]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
    }
}
