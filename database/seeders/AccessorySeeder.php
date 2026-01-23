<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'TBC',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Granite Heart',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Granite Book',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Granite Vase',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Marble Heart',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Marble Book',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Marble Vase',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Granite Page Book',
                'created_by'    => '1'

            ],

            [
                'name'          => 'Granite Page Heart',
                'created_by'    => '1'

            ]

        ];
        foreach ($values as $key => $value) {
            Accessory::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"]
            ]);
        }
    }
}
