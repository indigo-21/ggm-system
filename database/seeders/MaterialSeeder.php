<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $values = [
        //     [
        //         'name'          => 'Marble',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Marble Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Black',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - S/A Dark Grey',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Blue Pearl',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Karin Grey',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Balmoral Red',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Lavender Blue',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Butterfly Blue',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Imperial Red',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble - Tropical Green',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Marble Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Black',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - S/A Dark Grey',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Blue Pearl',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Karin Grey',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Balmoral Red',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Lavender Blue',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Butterfly Blue',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Imperial Red',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Marble with Granite Panel - Tropical Green',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Marble Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Granite',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Black',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - S/A Dark Grey',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Blue Pearl',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Karin Grey',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Balmoral Red',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Lavender Blue',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Butterfly Blue',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Imperial Red',
        //         'created_by'    => '1'

        //     ],
        //     [
        //         'name'          => 'Granite - Tropical Green',
        //         'created_by'    => '1'

        //     ],


        // ];

        $values = [
            [
                'name'          => 'Marble',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Marble with Granite Panel',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Granite',
                'created_by'    => '1'

            ]


        ];
        foreach ($values as $key => $value) {
            Material::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"]
            ]);
        }
    }
}
