<?php

namespace Database\Seeders;

use App\Models\GraveSpace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GraveSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'Single',
                'created_by'    => '1'

            ],
            [
                'name'         => 'Double',
                'created_by'   => '1'

            ],
            [
                'name'          => 'Double Depth',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Convert',
                'created_by'    => '1'

            ]
        ];
        foreach ($values as $key => $value) {
            GraveSpace::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"]
            ]);
        }
    }
}
