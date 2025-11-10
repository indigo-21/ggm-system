<?php

namespace Database\Seeders;

use App\Models\Cemetery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CemeterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'Bushey',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cheshunt',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cheshunt Western',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cheshunt Woodlands',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Crow Lane(Romford Cemetery)',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Eastham',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Edgwarebury',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Edmonton',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Enfield',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Hendon REFORM(Southgate)',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Hendon(Holders Hill and Southgate)',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Hoop Lane(Golders Green)',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Hove Reform',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Meadow View (Brighton)',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Rainham',
                'created_by'    => '1'

            ],
            [
                // 16
                'name'          => 'South Gate', 
                'created_by'    => '1'

            ],
            [
                'name'          => 'Waltham Abbey',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Willesden Liberal(Pound Lane)',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Willesden United',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Others',
                'created_by'    => '1'

            ]

            
        ];
        foreach ($values as $key => $value) {
            Cemetery::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"],
            ]);
        }
    }
}
