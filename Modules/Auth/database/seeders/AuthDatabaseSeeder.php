<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Laravel\Passport\PersonalAccessClient;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appList = [
            'public',
            'transactional',
            'finance',
            'thirdParty',
            'bank'
        ];
        foreach ($appList as $key => $value) {
            $apiKey = Str::random(32);
            $client = new Client();
            $client->id = $key+1;
            $client->name = $value;
            $client->secret =  Str::random(40);
            $client->redirect = 'http://localhost';
            $client->personal_access_client = 1;
            $client->password_client = 0;
            $client->revoked = 0;
            $client->api_key = $apiKey;
            $client->save();

            PersonalAccessClient::create([
                'client_id'=>$key+1
            ]);

        }
    }
}
