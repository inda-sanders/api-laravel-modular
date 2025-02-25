<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class RegexRules implements Rule
{
    protected $regexName;

    public function __construct($regexName)
    {
        $this->regexName = $regexName;
    }

    public function passes($attribute, $value)
    {
        $validation = DB::table('regex_validation_rules')
            ->where('rules_name', $this->regexName)
            ->first();
        if (!$validation) {
            return false; // Atau throw exception jika regex tidak ditemukan
        }


        if (preg_match("/{$validation->pattern}/", $value) == true) {
            return false;
        } else {
            return true;
        }
    }

    public function message()
    {
        $validation = DB::table('regex_validation_rules')
            ->where('rules_name', $this->regexName)
            ->first();

        return $validation ? $validation->message : 'Format :attribute tidak valid.';
    }
}
