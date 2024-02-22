<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $roles = Role::whereNotIn('name', ['admin'])->with('permissions')->get();
        return response()->json($roles);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3'],
            'permissions' => ['array'], // Assuming permissions are passed as an array
        ]);

        try {
            // Check if role with the same name already exists
            if (Role::where('name', $validated['name'])->exists()) {
                return response()->json(['message' => 'Role with the same name already exists'], 400);
            }

            // Create the role
            $role = Role::create(['name' => $validated['name']]);
            
            // Attach permissions to the role if provided
            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);
            }

            return response()->json(['message' => 'Role stored successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store role', 'error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'min:3'],
            'permissions' => ['array'], // Assuming permissions are passed as an array
        ]);

        try {
            // Update the role name
            $role->update(['name' => $validated['name']]);
            
            // Sync permissions if provided
            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);
            }

            return response()->json(['message' => 'Role updated successfully', 'role' => $role], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update role', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

}