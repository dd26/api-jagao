<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Customer;
use App\Specialist;
use App\UserType;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtén todos los usuarios
        $users = User::all();

        // Itera sobre los usuarios y crea registros en la tabla user_types
        foreach ($users as $user) {
            $userTypes = [];

            // Comprueba si el usuario tiene un registro en la tabla customers
            $customer = Customer::where('user_id', $user->id)->first();
            if ($customer) {
                $userType = UserType::firstOrNew([
                    'user_id' => $user->id,
                    'type' => 'customer',
                ]);

                if (!$userType->exists) {
                    $userType->save();
                }
                $userTypes[] = $userType;
            }

            // Comprueba si el usuario tiene un registro en la tabla specialists
            $specialist = Specialist::where('user_id', $user->id)->first();
            if ($specialist) {
                $userType = UserType::firstOrNew([
                    'user_id' => $user->id,
                    'type' => 'specialist',
                ]);

                if (!$userType->exists) {
                    $userType->save();
                }
                $userTypes[] = $userType;
            }

            // Elimina roles duplicados en la tabla user_types
            $userTypeDuplicates = UserType::select('type', DB::raw('count(type) as count'))
            ->where('user_id', $user->id)
            ->groupBy('type')
            ->havingRaw('count(type) > 1')
            ->get();

            foreach ($userTypeDuplicates as $duplicate) {
                UserType::where('user_id', $user->id)
                    ->where('type', $duplicate->type)
                    ->orderBy('created_at')
                    ->offset(1)
                    ->delete();
            }

            // Si el campo active_role es null, establece un valor predeterminado
            if ($user->active_role === null) {
                if (count($userTypes) > 0) {
                    // Si tiene dos roles, selecciona el más antiguo como el rol activo
                    if (count($userTypes) == 2) {
                        $defaultActiveRole = $userTypes[0]->created_at < $userTypes[1]->created_at ? $userTypes[0]->type : $userTypes[1]->type;
                    } else {
                        // Si solo tiene un rol, establece ese rol como el activo
                        $defaultActiveRole = $userTypes[0]->type;
                    }

                    $user->active_role = $defaultActiveRole;
                    $user->save();
                }
            }
        }
    }


}
