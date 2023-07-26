<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'admin',
            'description' => 'Super usuario',
            'status' => true
        ]);
        Permission::create([
            'name' => 'invoice',
            'description' => 'Facturardor',
            'status' => true
        ]);
        Permission::create([
            'name' => 'seller',
            'description' => 'Vendedor',
            'status' => true
        ]);
        Permission::create([
            'name' => 'logistic',
            'description' => 'Logística',
            'status' => true
        ]);
    }
}
