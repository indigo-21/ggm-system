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
                'firstname'         => 'Charles Vincent',
                'lastname'          => 'Verdadero',
                'email'             => 'charles.verdadero@indigo21.com',
                'username'          => 'charles',
                'password'          => Hash::make("password"),
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
