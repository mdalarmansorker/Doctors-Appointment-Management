<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class AuthController extends Controller
{
    public function index()
    {
        // Fetch users with their roles
        $users = User::whereHas('roles', function($query) {
                        $query->where('name', '!=', 'admin');
                    })
                    ->with('roles:id,name')
                    ->select('id', 'name', 'email', 'active')
                    ->get();
        
        return response()->json($users);
    }

    public function register(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ], 201);
            $user->assignRole($request->role);
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not exist.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $userRoleNames = $user->getRoleNames();
            return response()->json([
                'roles' => $userRoleNames,
                'user' => $user,
                'status' => true,
                'message' => 'Logged In Successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validation
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Check if the user exists for update, otherwise create a new user
            $user = User::where('id', $request->id)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
            } else {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
            }

            // Assign the role
            $user->syncRoles([$request->role]);

            return response()->json([
                'status' => true,
                'message' => $user->wasRecentlyCreated ? 'User Created Successfully' : 'User Updated Successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($userID)
    {
        $user = User::find($userID);
        $user->delete();
        return response()->json(['message' => 'Successfully deleted', 'user' => $user], 200);
    }

    public function doctors()
    {
        // Retrieve users with the 'Doctor' role
        $doctors = User::select('id', 'name', 'email')->whereHas('roles', function ($query) {
            $query->where('name', 'Doctor');
        })->get();

        return $doctors;
    }

}