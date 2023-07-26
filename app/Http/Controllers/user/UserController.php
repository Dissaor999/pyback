<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = User::with('getUserPermissions.getPermission')->get();
        $usersArray = [];
        foreach ($users as $u) {
            $permissions = '';
            foreach ($u->getUserPermissions as $permission) {
                $permissions .= $permission->getPermission->name . '  ';
            }
            $data['id'] = $u->id;
            $data['name'] = $u->name;
            $data['email'] = $u->email;
            $data['status'] = $u->status;
            $data['permissions'] = $permissions;
            $usersArray[] = $data;
        }
        $content = [
            'status' => true,
            'message' => 'Lista de usuarios.',
            'data' => $usersArray
        ];
        return response()->json($content, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
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

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        foreach ($request->permissions_name as $name) {
            $permission = Permission::where('name', $name)->first();
            $dataUserPermission = [
                'user_id' => $user->id,
                'permission_id' => $permission->id
            ];
            UserPermission::create($dataUserPermission);
        }

        $content = [
            'status' => true,
            'message' => 'Usuario registrado con éxito',
            // 'token' => $user->createToken('TokenHermes')->accessToken;
            'data' => $user
        ];
        return response()->json($content, 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $user = User::whereId($id)->first();
        if (is_null($user)) {
            $content = [
                'status' => false,
                'message' => 'Usuario no encontrado.'
            ];
            return response()->json($content, 404);
        }
        $content = [
            'success' => true,
            'message' => 'Usuario encontrado.',
            'data' => $user
        ];
        return response()->json($content, 200);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $user =  User::whereId($id)->first();
        if (is_null($user)) {
            $content = [
                'status' => false,
                'message' => 'Usuario no encontrado.'
            ];
            return response()->json($content, 404);
        }

        $user->status = 0;
        $user->save();

        $content = [
            'status' => true,
            'message' => 'Usuario desactivado exitosamente.',
            'data' => $user
        ];
        return response()->json($content, 200);
    }
}
