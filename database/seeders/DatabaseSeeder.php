<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Dummy\Database\Seeders\DummyDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            AuthDatabaseSeeder::class,
            DummyDatabaseSeeder::class,
            // AccountSeeder::class,
            RegexDatabaseSeeder::class,

            // Add other seeders as needed
        ]);
    }
}
