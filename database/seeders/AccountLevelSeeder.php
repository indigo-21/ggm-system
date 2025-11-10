<?php

namespace Database\Seeders;
use App\Models\AccountLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $values = [
            [
                'name'          => 'Administrator',
                'module_ids'    => '1,2,3,4',
                'created_by'    => '1'

            ],
            [
                'name'         => 'Admin-Staff',
                'module_ids'   => '1,2,3,4',
                'created_by'   => '1'

            ]
        ];
        foreach ($values as $key => $value) {
            AccountLevel::create([
                'name'          => $value["name"],
                'module_ids'    => $value["module_ids"],
                // 'created_by'    => $value["created_by"],
            ]);
        }
    }
}
