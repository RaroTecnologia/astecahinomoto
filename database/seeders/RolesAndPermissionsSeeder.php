<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissões para usuários
        $userPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.manage_roles'
        ];

        // Outras permissões do sistema
        $otherPermissions = [
            'gerenciar_categorias',
            'gerenciar_nutrientes',
            'gerenciar_tabelas_nutricionais',
            'gerenciar_noticias',
            'gerenciar_receitas',
            'gerenciar_produtos',
            'gerenciar_home',
            'acessar_dashboard'
        ];

        // Criar todas as permissões
        foreach ([...$userPermissions, ...$otherPermissions] as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Admin tem todas as permissões
        $admin->syncPermissions(Permission::all());

        // Editor tem permissões específicas
        $editor->syncPermissions([
            'users.view',
            'gerenciar_noticias',
            'gerenciar_receitas',
            'gerenciar_produtos',
            'gerenciar_home',
            'acessar_dashboard'
        ]);

        // Usuário comum
        $user->syncPermissions([
            'acessar_dashboard'
        ]);
    }
}
