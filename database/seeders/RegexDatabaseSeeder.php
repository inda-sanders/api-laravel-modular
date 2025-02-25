<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegexRuleModel; // Make sure you have a RegexRule model
use App\Rules\RegexRules;

class RegexDatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
      if (RegexRuleModel::count() == 0) { 

        RegexRuleModel::truncate(); // Optional: Clear existing data before seeding

        RegexRuleModel::create([
              'rules_name' => 'custom_email',
              'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
              'message' => 'Validates email addresses.',
          ]);

        RegexRuleModel::create([
              'rules_name' => 'phone',
              'pattern' => '/^\d{10}$/', // Example: 10-digit phone number
              'message' => 'Validates 10-digit phone numbers.',
          ]);

        RegexRuleModel::create([
              'rules_name' => 'postal_code',
              'pattern' => '/^\d{5}(-\d{4})?$/', // Example: US postal code
              'message' => 'Validates US postal codes (5 or 5-4 format).',
          ]);

        RegexRuleModel::create([
              'rules_name' => 'double_spacing',
              'pattern' => '/\s{2,}|\n/', // Example: User  Name
              'message' => 'Validates double spacing.',
          ]);
      }
        // Add more regex rules as needed...
    }
}
