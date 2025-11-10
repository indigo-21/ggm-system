<?php

namespace Database\Seeders;

use App\Models\BurialSocietyOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BurialSocietyOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'cemetery_id'   => '1',
                'name'          => 'United Synagogue',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '1',
                'name'          => 'United Sephardi',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '2',
                'name'          => 'Adath Yisroel',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '3',
                'name'          => 'Western',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '3',
                'name'          => 'Liberal Judaism',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '3',
                'name'          => 'Joint Jewish Burial Society(Reformed)',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '3',
                'name'          => 'Cheshunt Columbarium and Woodlands',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '3',
                'name'          => 'Waltham Forest/Woodford Forest',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '4',
                'name'          => 'Joint Jewish Burial Society only',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '5',
                'name'          => '-',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '6',
                'name'          => 'United Synagogue',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '7',
                'name'          => 'Federation',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '7',
                'name'          => 'Liberal Judaism',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '7',
                'name'          => 'West London',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '7',
                'name'          => 'Spanish and Portuguese',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '7',
                'name'          => 'Belsize Square',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '8',
                'name'          => 'Federation',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '8',
                'name'          => 'Western',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '9',
                'name'          => 'Adath Yisroel',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '10',
                'name'          => 'Jewish section in Christian Cemetery',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '11',
                'name'          => 'West London',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '12',
                'name'          => 'West London',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '12',
                'name'          => 'Spanish and Portuguese',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '12',
                'name'          => 'The Columbarium(West London)',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '13',
                'name'          => '-',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '14',
                'name'          => '-',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '15',
                'name'          => 'Federation',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '16',
                'name'          => 'New is mixed with a Jewish section Brunswick Park Rd Old',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '17',
                'name'          => 'United Synagogue',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '18',
                'name'          => 'Liberal Judaism',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '19',
                'name'          => 'United Synagogue',
                'created_by'    => '1'

            ],
            [
                'cemetery_id'   => '20',
                'name'          => '-',
                'created_by'    => '1'

            ],

        ];
        foreach ($values as $key => $value) {
            BurialSocietyOrganization::create([
                'cemetery_id'   => $value["cemetery_id"],
                'name'          => $value["name"],
                'created_by'    => $value["created_by"],
            ]);
        }
    }
}
