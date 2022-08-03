<?php

use Illuminate\Database\Seeder;
use App\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // cities
        $cities = [
            [
                'name' => 'Orlando',
                'country_id' => 1
            ],
            [
                'name' => 'Kissimmee',
                'country_id' => 1
            ],
            [
                'name' => 'Davenport',
                'country_id' => 1
            ],
            [
                'name' => 'Sanford',
                'country_id' => 1
            ],
            [
                'name' => 'Lakeland',
                'country_id' => 1
            ],
            [
                'name' => 'Winter Haven',
                'country_id' => 1
            ],
            [
                'name' => 'Clermont',
                'country_id' => 1
            ],
            [
                'name' => 'St. Cloud',
                'country_id' => 1
            ],
            [
                'name' => 'Apopka',
                'country_id' => 1
            ],
            [
                'name' => 'Altamonte Spring',
                'country_id' => 1
            ],
            [
                'name' => 'Oviedo',
                'country_id' => 1
            ],
            [
                'name' => 'Lake Mary',
                'country_id' => 1
            ],
            [
                'name' => 'Ocoee',
                'country_id' => 1
            ]

        ];
        foreach ($cities as $city) {
            // verify if city exists
            $exists = City::where('name', $city['name'])->first();
            if (!$exists) {
                City::create($city);
            }
        }
    }
}
