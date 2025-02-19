<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id', 'name');
    }

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
            $userData['department'] = $this->department();


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
}
