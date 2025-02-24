<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Modules\UserManagement\Models\Department;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'entry_date',
        'is_active',
        'department_id',
        'client_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'client_id'
    ];

    protected $guard_name = 'api';


    /**
     * The attributes that are auto fill created_at and updated_at.
     */
    public $timestamps = true;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getUpdateFillable()
    {
        return [
            'name',
            'username',
            'email',
            'entry_date',
            'is_active',
            'department_id',
            'updated_by',
            'created_by'
        ];
    }

    // public function department()
    // {
    //     return $this->belongsTo(Department::class, 'department_id', 'id', 'name');
    // }

    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions(); // Returns a Collection of permissions
    }

    /**
     * Scope a query to only select two columns.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function get_user_data()
    {

        $userData = collect($this);
        $userData['roles'] = $this->getRoleNames();
        $userData['permissions'] = $this->getAllPermissions()->pluck('name');
        // $userData['department'] = $this->department();


        return collect($userData);
    }
    static function get_all_user_data($users = null)
    {
        if (empty($users)) {
            $users = self::all(); // Get all users from the database.  Use with caution!
        }

        $allUserData = [];
        foreach ($users as $user) {
            $userData = $user->get_user_data(); // Reuse the existing function
            $allUserData[] = $userData;
        }

        return $allUserData;
    }

    public function getAllBy($limit, $offset, $search = [], $col = null, $dir = null, $where = [])
    {
        $query = $this->select(DB::raw("*"))->with('roles');

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
