<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(KeySeeder::class);
        $this->call(PermissionSedeer::class);
        $this->call(UserSeeder::class);
        $this->call(ChannelSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(ItemSeeder::class);
    }
}
