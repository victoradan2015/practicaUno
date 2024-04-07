<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;

class RolesYPermisosSeeder extends Seeder
{
    /**
     * Crea tres roles: administrador, coordinador, invitado
     * Crea permisos para modulos: departamentos, empleados y role_permissions y 
     * Asigna permisos recientes al rol de administrador.
     */
    public function run(): void
    {
        
        $roleAdmin = Role::create(['name' => 'administrador']);
        $roleCoordinador = Role::create(['name' => 'coordinador']);
        $roleInvitado = Role::create(['name' => 'invitado']);

        Permission::create(['name' => 'departamentos.view']);
        Permission::create(['name' => 'departamentos.create']);
        Permission::create(['name' => 'departamentos.edit']);
        Permission::create(['name' => 'departamentos.delete']);

        Permission::create(['name' => 'empleados.view']);
        Permission::create(['name' => 'empleados.create']);
        Permission::create(['name' => 'empleados.edit']);
        Permission::create(['name' => 'empleados.delete']);

        Permission::create(['name' => 'role_permissions.view']);
        Permission::create(['name' => 'role_permissions.create']);
        Permission::create(['name' => 'role_permissions.edit']);
        Permission::create(['name' => 'role_permissions.delete']);

        //Asigna permisos a rol Administrador, todos los permisos
        $roleAdmin->syncPermissions(
            ['departamentos.view',
            'departamentos.create',
            'departamentos.create',
            'departamentos.delete',
            'empleados.view',
            'empleados.create',
            'empleados.edit',
            'empleados.delete',
            'role_permissions.view'
        ]);

        //Crud completo, no toca cosas de configuracion
        $roleCoordinador->syncPermissions(
            ['departamentos.view',
            'departamentos.create',
            'departamentos.create',
            'departamentos.delete',
            'empleados.view',
            'empleados.create',
            'empleados.edit',
            'empleados.delete'
        ]);

        // Manejo de departamento y empleados solamente (CRUD, no puede eliminar)
        $roleInvitado->syncPermissions(
            ['departamentos.view',
            'departamentos.create',
            'departamentos.create',
            'empleados.view',
            'empleados.create',
            'empleados.edit',
        ]);


    }
}