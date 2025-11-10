<?php

namespace Database\Seeders;

use App\Models\BasedLedger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BasedLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'name'          => 'Marble',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Granite',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Earth Filled',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Chippings - White',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Chippings - Green',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Chippings - Grey',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Chippings - Cobolt Blue',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Chippings - Light Blue',
                'created_by'    => '1'

            ],
            [
                'name'          => 'Chippings - Black',
                'created_by'    => '1'

            ],
        ];
        foreach ($values as $key => $value) {
            BasedLedger::create([
                'name'          => $value["name"],
                'created_by'    => $value["created_by"],
            ]);
        }
    }
}
