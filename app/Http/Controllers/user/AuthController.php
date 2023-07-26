<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas',
                'error' => $validator->errors()
            ];
            return response()->json($content, 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('TokenHermes')->accessToken;
            $userData = User::where('email', $request->email)->with('getUserPermissions.getPermission')->first();

            // Status User Validate
            if ($userData->status != 1) {
                $content = [
                    'status' => false,
                    'message' => 'Usuario desactivado.'
                ];
                return response()->json($content, 401);
            }

            $permissions = [];
            foreach ($userData->getUserPermissions as $permission) {
                $permissions[] = $permission->getPermission->name;
            }
            $content = [
                'status' => true,
                'message' => 'Inicio de sesión exitoso.',
                'access_token' => $token,
                'data' => [
                    'id' => $userData->id,
                    'name' => $userData->name,
                    'email' => $userData->email,
                    'status' => $userData->status,
                    'permissions' => $permissions
                ]
            ];
            return response()->json($content, 200);
        } else {
            $content = [
                'status' => false,
                'message' => 'Credenciales no válidas.',
            ];
            return response()->json($content, 401);
        }
    }

    public function getPermission(): \Illuminate\Http\JsonResponse
    {
        $permissions = Permission::all();
        $content = [
            'status' => true,
            'message' => 'Lista de Permisos.',
            'data' => $permissions
        ];
        return response()->json($content, 201);
    }

    public function updatePermission(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $content = [
                'status' => false,
                'message' => 'Entradas no válidas',
                'error' => $validator->errors()
            ];
            return response()->json($content, 401);
        }
        $password = $request->password;
        $userId = $request->user_id;
        User::whereId($userId)->update(['password' => bcrypt($password)]);
        UserPermission::where('user_id', $userId)->delete();
        foreach ($request->permissions_name as $name) {
            $permission = Permission::where('name', $name)->first();
            $dataUserPermission = [
                'user_id' => $userId,
                'permission_id' => $permission->id
            ];
            UserPermission::create($dataUserPermission);
        }
        $content = [
            'status' => true,
            'message' => 'Permisos actualizados.',
        ];
        return response()->json($content, 201);
    }
}
