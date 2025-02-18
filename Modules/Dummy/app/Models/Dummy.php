<?php

namespace Modules\Dummy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Dummy\Database\Factories\DummyFactory;

class Dummy extends Model
{
    use HasFactory;

    protected $table = 'dummy';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'age',
        'price',
        'lat',
        'is_active',
        'description',
        'birthdate',
        'start_time',
        'amount',
        'preferences',
        'status',
        'image',
        'uuid'
    ];

    /**
     * The attributes that are auto fill created_at and updated_at.
     */
    public $timestamps = true;

    // protected static function newFactory(): DummyFactory
    // {
    //     // return DummyFactory::new();
    // }
}
