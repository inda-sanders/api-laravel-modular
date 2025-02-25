<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegexRuleModel extends Model
{
    use HasFactory;

    protected $table= "regex_validation_rules";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rule_name',
        'regex',
        'message',
    ];

    /**
     * The attributes that should be cast.  Useful if you have
     * specific data types you want to enforce (e.g., dates, booleans).
     *
     * @var array
     */
    protected $casts = [
        // 'regex' => 'string',  // Typically regex will be stored as strings
        // 'active' => 'boolean', // Example: If you add an 'active' column
    ];

}
