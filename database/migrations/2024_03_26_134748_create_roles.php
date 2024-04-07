<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;
use Database\Seeders\RolesYPermisosSeeder;//Seeder

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $seeder = new RolesYPermisosSeeder(); //Linea para que se seten los roles y permisos recien agregados
        $seeder->run(); 
        /*
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'normal']);
        
        
        $user = User::find(1);
        $user->assignRole($role1);
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
