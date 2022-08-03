<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // si ya existe el usuario admin, no se crea
        if (DB::table('users')->where('email', 'admin@example.com')->count() == 0) {
            // insert user
            DB::table('users')->insert([
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => '123456789',
                'role_id' => 1,
            ]);
        }


        /* DB::table('users')->insert(
            [
                'name' => 'Usuario Prueba',
                'email' => 'admin@example.com',
                'password' => '123456789',
                'role_id' => 1,
            ]
        ); */
    }
}
