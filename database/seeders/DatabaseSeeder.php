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

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'fortify']);
        $gestorRole = Role::create(['name' => 'gestor', 'guard_name' => 'fortify']);
        $secretariaRole = Role::create(['name' => 'secretaria', 'guard_name' => 'fortify']);
        $financeiroRole = Role::create(['name' => 'financeiro', 'guard_name' => 'fortify']);
        $frotaRole = Role::create(['name' => 'frota', 'guard_name' => 'fortify']);
        $diretoriaRole = Role::create(['name' => 'diretoria', 'guard_name' => 'fortify']);
        

        $viewUsersPermission = Permission::create(['name' => 'view users', 'guard_name' => 'fortify']);
        $editUsersPermission = Permission::create(['name' => 'edit users', 'guard_name' => 'fortify']);
        $gerenciarAvsPendentesAprovacaoGestor = Permission::create(['name' => 'aprov avs gestor', 'guard_name' => 'fortify']);
        $gerenciarAvsPendentesReservaSecretaria = Permission::create(['name' => 'aprov avs secretaria', 'guard_name' => 'fortify']);
        $gerenciarAvsPendentesAprovacaoFinanceiro = Permission::create(['name' => 'aprov avs financeiro', 'guard_name' => 'fortify']);
        $gerenciarAvsPendentesReservaFrota = Permission::create(['name' => 'aprov avs frota', 'guard_name' => 'fortify']);
        $gerenciarAvsPendentesDiretoria = Permission::create(['name' => 'aprov avs diretoria', 'guard_name' => 'fortify']);

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
