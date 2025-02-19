<?php

namespace Modules\Dummy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

// use Modules\Dummy\Database\Factories\DummyFactory;

class DummyModel extends Model
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

    public function getAllBy($limit, $offset, $search = [], $col = null, $dir = null, $where = [])
    {
        $query = $this->select(DB::raw("*"));

        // Add a where clause to filter by model type

        // Apply the limit and offset clauses
        if ($limit) {
            $query->limit($limit);
        }
        if ($offset) {
            $query->offset($offset);
        }

        // Apply the order by clause
        if ($col && $dir) {
            $query->orderBy($col, $dir);
        }

        // Apply the search criteria
        if (!empty($search)) {
            // $query->orWhere(function ($query) use ($search) {
            //     $i = 0;
            //     foreach ($search as $key => $value) {
            //         if ($i = 0) {
            //             $query->where($key, 'LIKE', "%{$value}%");
            //         } else {
            //             $query->orWhere($key, 'LIKE', "%{$value}%");
            //         }
            //         $i++;
            //     }
            // });
            $searchs = [];
            foreach ($search as $key => $value) {
                $searchs[] = [$key, 'LIKE', "%{$value}%"];
            }

            $query->where($searchs);
        }

        // Apply the additional where clauses
        if (!empty($where)) {
            $query->where($where);
        }

        // Return the results
        return $query->get();
    }

    public function getCountAllBy($search = [], $where = [])
    {
        $query = $this->select(DB::raw('count(*) as total'));


        if (!empty($search)) {
            // $query->orWhere(function ($query) use ($search) {
            //     $i = 0;
            //     foreach ($search as $key => $value) {
            //         if ($i = 0) {
            //             $query->where($key, 'LIKE', "%{$value}%");
            //         } else {
            //             $query->orWhere($key, 'LIKE', "%{$value}%");
            //         }
            //         $i++;
            //     }
            // });

            $searchs = [];
            foreach ($search as $key => $value) {
                $searchs[] = [$key, 'LIKE', "%{$value}%"];
            }

            $query->where($searchs);
        }

        if (!empty($where)) {
            $query->where($where);
        }

        return $query->first()->total;
    }
}
