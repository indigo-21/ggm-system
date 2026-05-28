<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [   
                'account_level_id'  => "1",
                'location_id'       => "1",
                'firstname'         => 'Support',
                'lastname'          => 'Indigo21',
                'email'             => 'support@indigo21.com',
                'username'          => 'admin',
                'password'          => Hash::make("indigo21"),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
            [   
                'account_level_id'  => "2",
                'location_id'       => "2",
                'firstname'         => 'Gary',
                'lastname'          => 'Green',
                'email'             => 'gary@garygreenmemorials.co.uk',
                'username'          => 'gary',
                'password'          => Hash::make("gary123"),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
            [   
                'account_level_id'  => "2",
                'location_id'       => "2",
                'firstname'         => 'Jessica',
                'lastname'          => 'Redmill',
                'email'             => 'jessica@garygreenmemorials.co.uk',
                'username'          => 'jessica',
                'password'          => Hash::make("jessica"),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
            [   
                'account_level_id'  => "2",
                'location_id'       => "2",
                'firstname'         => 'Paul',
                'lastname'          => 'Gayer',
                'email'             => 'paul@garygreenmemorials.co.uk',
                'username'          => 'paul',
                'password'          => Hash::make("paul"),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ],
            [   
                'account_level_id'  => "2",
                'location_id'       => "2",
                'firstname'         => 'Joe',
                'lastname'          => 'Sword',
                'email'             => 'joe@nordens.co.uk',
                'username'          => 'joe.sword',
                'password'          => Hash::make("password123"),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]
        ];
       
        foreach ($values as $key => $value) {
            User::create([
                'account_level_id'  => $value['account_level_id'],
                'location_id'       => $value['location_id'],
                'firstname'         => $value['firstname'],
                'lastname'          => $value['lastname'],
                'email'             => $value['email'],
                'username'          => $value['username'],
                'password'          => $value['password'],
                'email_verified_at' => $value['email_verified_at']
            ]);
        }

    }
}
