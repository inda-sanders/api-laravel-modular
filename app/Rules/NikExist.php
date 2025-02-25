<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
 

class NikExist implements Rule {
    
    public function passes($attribute, $value)
    {
        return strtoupper($value) === $value;

    }

    public function message()
    {
        return 'The :attribute must be lowercase.';
    }
}

