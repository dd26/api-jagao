<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'home',
                'description' => 'Acceso a la página principal',
                'slug' => 'home-admin',
                'role_id' => 1,
            ],
            [
                'name' => 'home',
                'description' => 'Acceso a la página principal',
                'slug' => 'home-soporte',
                'role_id' => 4,
            ],
            [
                'name' => 'module-users',
                'description' => 'Acceso a los módulos de usuarios admin del sistema',
                'slug' => 'module-users-admin',
                'role_id' => 1,
            ],
            [
                'name' => 'module-categories',
                'description' => 'Acceso a los módulos de categorías y subcategorias',
                'slug' => 'module-categories-admin',
                'role_id' => 1,
            ],
            [
                'name' => 'module-customers',
                'description' => 'Acceso a los módulos de clientes',
                'slug' => 'module-customers-admin',
                'role_id' => 1,
            ],
            [
                'name' => 'module-specialist',
                'description' => 'Acceso a los módulos de empleados',
                'slug' => 'module-specialist-admin',
                'role_id' => 1,
            ],
            [
                'name' => 'module-cupons',
                'description' => 'Acceso a los módulos de cupones',
                'slug' => 'module-cupons-admin',
                'role_id' => 1,
            ],
            [
                'name' => 'module-services-admin',
                'description' => 'Acceso a los módulos de servicios',
                'slug' => 'module-services-admin',
                'role_id' => 1,
            ]
        ];

        foreach ($permissions as $permission) {
            // verify if permission exists
            $exists = Permission::where('slug', $permission['slug'])->first();
            if (!$exists) {
                Permission::create($permission);
            }
        }

    }
}
