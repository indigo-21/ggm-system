<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
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
                'name'         => 'Cut & Lead',
                'created_by'   => '1'

            ],
            [
                'name'          => 'Cut & Gilded',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cut & Painted Silver',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cut & Painted Black',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cut & Painted White',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Cut & Painted Other',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Deep Cut',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Polished',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Raised Lead',
                'created_by'    => '1'

            ]

        ];
        foreach ($values as $key => $value) {
            LetterType::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"]
            ]);
        }
    }
}
