<?php

use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // roles
        $roles = [
            [
                'name' => 'Administrador',
                'slug' => 'admin',
                'description' => 'Administrador del sistema',
            ],
            [
                'name' => 'Especialista',
                'slug' => 'specialist',
                'description' => 'Especialista del sistema',
            ],
            [
                'name' => 'Cliente',
                'slug' => 'customer',
                'description' => 'Cliente del sistema',
            ],
        ];
        // insert roles
        foreach ($roles as $role) {
            \App\Role::create($role);
        }
    }
}
