<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;


class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                "title" => "None",
                "firstname" => "Testing",
                "lastname" => "Indigo21",
                "salutation" => "Testing Indigo21",
                "address_one" => "Testing address one",
                "address_two" => "Test address two",
                "city_county" => "Up Town",
                "postcode" => "1605",
                "created_by" => "1",
            ]
        ];
        foreach ($values as $key => $value) {
            Customer::create([
                "title" =>  $value["title"],
                "firstname" => $value["firstname"],
                "lastname" => $value["lastname"],
                "salutation" => $value["salutation"],
                "address_one" => $value["address_one"],
                "address_two" => $value["address_two"],
                "city_county" => $value["city_county"],
                "postcode" => $value["postcode"],
                "created_by" => $value["created_by"],
            ]);
        }
    }
}
