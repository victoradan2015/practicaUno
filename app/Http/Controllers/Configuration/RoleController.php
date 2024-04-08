<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\User;

class RoleController extends Controller
{
    public function viewListRoles(){
        $roles = Role::all();
        $permissions = Permission::all();

        return view('configuration.role', compact('roles', 'permissions'));
    }

    public function update(Request $request)
    {
        //dd($request);
        $permissions = Permission::all();

        // Itera sobre los roles
        foreach ($request->permissions as $roleId => $rolePermissions) {
            // Obtiene el rol actual
            $role = Role::findById($roleId);

            // Sincroniza los permisos del rol con los proporcionados en el formulario
            foreach ($permissions as $permission) {
                $permissionId = $permission->id;
                $hasPermission = isset($rolePermissions[$permissionId]);

                if ($hasPermission) {
                    $role->givePermissionTo($permission);
                } else {
                    $role->revokePermissionTo($permission);
                }
            }
        }

        return redirect()->back()->with('success', 'Permisos actualizados exitosamente para los roles.');
    }

    public function viewListUsers(){
        $users = User::all();

        return view('configuration.users_role', compact('users'));
    }
}
