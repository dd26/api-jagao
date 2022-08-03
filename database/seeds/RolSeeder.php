<?php

use Illuminate\Database\Seeder;
use App\Role;

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
            [
                'name' => 'Soporte',
                'slug' => 'support',
                'description' => 'Soporte del sistema en el admin',
            ],
        ];
        // insert roles
        foreach ($roles as $role) {
            // verify if role exists
            $exists = Role::where('slug', $role['slug'])->first();
            if (!$exists) {
                Role::create($role);
            }
        }
    }
}
