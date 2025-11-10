<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            // Config
                AccountLevelSeeder::class,
                LocationSeeder::class,
                UserSeeder::class,
                ModuleSeeder::class,
            // Masterfiles
                CemeterySeeder::class,
                BurialSocietyOrganizationSeeder::class,
                GraveSpaceSeeder::class,
                LettertypeSeeder::class,
                MaterialSeeder::class,
                AccessorySeeder::class,
                BasedLedgerSeeder::class,
                OrderTypeSeeder::class,

        ]);

        
    }
}
