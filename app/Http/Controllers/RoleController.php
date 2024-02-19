<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getUserRolesAndPermissions(Request $request, $userId) {
        // Retrieve the user by their ID
        $user = User::findOrFail($userId);
        
        // Get the roles assigned to the user
        $rolesWithPermissions = [];
        foreach ($user->roles as $role) {
            $roleData = [
                'role_name' => $role->name,
                'permissions' => $role->permissions->pluck('name')->toArray()
            ];
            $rolesWithPermissions[] = $roleData;
        }
    
        return response()->json($rolesWithPermissions);
    }
}
