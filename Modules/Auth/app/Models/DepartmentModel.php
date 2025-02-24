<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
// use Modules\Auth\Database\Factories\DepartmentModelFactory;

class departmentModel extends Model
{
    use HasFactory;


    protected $table = 'department';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    // protected static function newFactory(): DepartmentModelFactory
    // {
    //     // return DepartmentModelFactory::new();
    // }

    public function getUpdateFillable()
    {
        return [
            'name',
            'description',
        ];
    }

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
