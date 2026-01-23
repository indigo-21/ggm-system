<?php

namespace Database\Seeders;

use App\Models\Colour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'Marble Granite',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Granite',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Black',
                'created_by'    => '1'

            ],
            [
                'name'          => 'S/A Dark Grey',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Blue Pearl',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Karin Grey',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Balmoral Red',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Lavender Blue',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Butterfly Blue',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Imperial Red',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Tropical Green',
                'created_by'    => '1'

            ]
        ];

       
        foreach ($values as $key => $value) {
            Colour::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"]
            ]);
        }
    }
}
