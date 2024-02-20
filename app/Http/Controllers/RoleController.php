<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
    public function index()
    {
        $roles = Role::whereNotIn('name', ['admin'])->get();
        return response()->json($roles);
    }
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', 'min:3']]);
        
        try {
            $role = Role::create($validated);
            return response()->json(['message' => 'Role stored successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store role', 'error' => $e->getMessage()], 500);
        }
    }

}