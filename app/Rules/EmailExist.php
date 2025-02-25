<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\DB;  // Import the DB facade

class EmailExist implements Rule
{
    protected $clientId;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }
    public function passes($attribute, $value)
    {
        // Check if the email exists in the database.
        // Adjust 'users' and 'email' to your table and column names.
        $count = User::where(["client_id" => $this->clientId, 'email' => $value])->count();

        return !($count > 0); // Return true if the email exists, false otherwise.
    }

    public function message()
    {
        return 'The :attribute is exist.'; // Customize the error message.
    }
}
