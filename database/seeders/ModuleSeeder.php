<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;


class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'Module',
                'route_name'    => 'module',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Account Level',
                'route_name'    => 'account_level',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Location',
                'route_name'    => 'location',
                'created_by'    => '1'

            ],
            [
                'name'          => 'User',
                'route_name'    => 'user',
                'created_by'    => '1'

            ]
           
        ];
        foreach ($values as $key => $value) {
            Module::create([
                'name'          => $value["name"],
                'route_name'    => $value["route_name"],
                'created_by'    => $value["created_by"],
            ]);
        }
    }
}
