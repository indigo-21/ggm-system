<?php

namespace Database\Seeders;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $values = [
            [
                'name'       => 'Edgeware',
                'created_by' => '1',
            ],
            [
                'name' => 'Clayhall',
                'created_by' => '1',
            ]
        ];
        foreach ($values as $key => $value) {
            Location::create([
                'name' => $value['name'],
            ]);
        }
    }
}
