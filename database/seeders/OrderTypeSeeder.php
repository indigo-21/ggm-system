<?php

namespace Database\Seeders;
use App\Models\OrderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'New Memorial',
                'created_by'    => '1'

            ],
            [
                'name'         => 'Added Inscription',
                'created_by'   => '1'
            ],
            [
                'name'         => 'Renovation',
                'created_by'   => '1'
            ],
            [
                'name'         => 'Washdown',
                'created_by'   => '1'
            ],
            [
                'name'         => 'Other',
                'created_by'   => '1'
            ]
        ];
        foreach ($values as $key => $value) {
            OrderType::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"],
            ]);
        }
    }
}
