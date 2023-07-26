<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permissions
        $permissionAdmin = Permission::where('name', 'admin')->first()->id;
        $permissionInvoice = Permission::where('name', 'invoice')->first()->id;
        $permissionSeller = Permission::where('name', 'seller')->first()->id;
        $permissionLogistic = Permission::where('name', 'logistic')->first()->id;
        // Users
        $userMike = User::create([
            'name' => 'Mike',
            'email' => 'miguel@hermes.mx',
            'password' => hash::make('Migu3l.454'),
        ])->id;
        $userDani = User::create([
            'name' => 'Dissaor',
            'email' => 'daniel@hermes.mx',
            'password' => hash::make('D@ni3l.190'),
        ])->id;
        $userGianni = User::create([
            'name' => 'Gianni',
            'email' => 'gianni@hermes.mx',
            'password' => hash::make('Gi@nni.981'),
        ])->id;
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@hermes.mx',
            'password' => hash::make('@dmin.038'),
        ])->id;
        $userInvoice = User::create([
            'name' => 'Facturardor',
            'email' => 'facturardor@hermes.mx',
            'password' => hash::make('Inv0ic3.901'),
        ])->id;
        $userSeller = User::create([
            'name' => 'Vendedor',
            'email' => 'vendedor@hermes.mx',
            'password' => hash::make('v3nd3d0r.091'),
        ])->id;
        $userLogistic = User::create([
            'name' => 'Logística',
            'email' => 'logistica@hermes.mx',
            'password' => hash::make('logistic@.237'),
        ])->id;

        $users = [$userMike, $userDani, $userGianni, $userAdmin, $userInvoice, $userSeller, $userLogistic];
        foreach ($users as $userId) {
            if ($userId == 1 || $userId == 2 || $userId == 3 || $userId == 4) { // Admins
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionAdmin,
                ]);
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionInvoice,
                ]);
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionSeller,
                ]);
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionLogistic,
                ]);
            } elseif ($userId == 5) {
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionInvoice,
                ]);
            } elseif ($userId == 6) {
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionSeller,
                ]);
            } elseif ($userId == 7) {
                UserPermission::create([
                    'user_id' => $userId,
                    'permission_id' => $permissionLogistic,
                ]);
            }
        }
    }
}
