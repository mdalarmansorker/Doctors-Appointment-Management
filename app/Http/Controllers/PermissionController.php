<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permission = Permission::all();
        return response()->json($permission);
    }
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', 'min:3']]);
        
        try {
            $permission = Permission::create($validated);
            return response()->json(['message' => 'Permission stored successfully', 'permission' => $permission], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store permission', 'error' => $e->getMessage()], 500);
        }
    }
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
