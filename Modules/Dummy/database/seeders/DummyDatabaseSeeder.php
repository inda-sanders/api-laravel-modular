<?php

namespace Modules\Dummy\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DummyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Instantiate Faker
        $faker = Faker::create();

        // Loop to create fake records
        foreach (range(1, 100) as $index) {
            $imagePath = storage_path('favicon.ico'); // Make sure the image exists in this path
            $imageBinary = file_get_contents($imagePath);
            DB::table('dummy')->insert([
                'name' => $faker->name, // Fake name
                'age' => $faker->numberBetween(18, 99), // Random number between 18 and 99
                'price' => $faker->randomFloat(2, 1, 1000), // Random float (2 decimal places, between 1 and 1000)
                'lat' => $faker->latitude, // Random latitude
                'is_active' => $faker->boolean, // Random boolean (true/false)
                'description' => $faker->paragraph, // Random text paragraph
                'birthdate' => $faker->date, // Random date
                'start_time' => $faker->time, // Random time
                'amount' => $faker->randomFloat(2, 1, 1000), // Random decimal number
                'preferences' => json_encode(['dummyData'=>'dummy']), // Random JSON
                'status' => $faker->randomElement(['pending', 'completed', 'cancelled']), // Random status
                'image' => $imageBinary, // Random binary data (e.g., for image)
                'uuid' => $faker->uuid, // Random UUID
                'created_at' => $faker->datetime, // Random datetime
                'updated_at' => $faker->datetime, // Random datetime
            ]);
        }
    }
}
