<?php

namespace App\Models;

use App\Filters\UserFilter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];

    /**
     * Get the user's role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new UserFilter($query);
    }

    /**
     *  Get user's image file.
     *
     * @return string
     */
    public function getImageFileAttribute()
    {
        if ($this->image === null) {
            return asset('images/default-user.png');
        }

        return Storage::disk('avatar')->url("{$this->image}");
    }

    /**
     * Does user have role admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role->isAdmin();
    }

    /**
     * Does user have permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->role->permissions()
            ->where('name', $permission)
            ->first() ? true : false;
    }

    /**
     * Save user's image name.
     *
     * @return string
     */
    public function saveImage($imageName)
    {
        $this->update(['image' => $imageName]);
    }

    /**
     * Save user's password.
     *
     * @param  string  $password
     * @return mixed
     */
    public function savePassword($password)
    {
        return $this->update(['password' => Hash::make($password)]);
    }

    /**
     * Is auth user same as compared user.
     *
     * @param  \App\Models\User  $comparedUser
     * @return bool
     */
    public function isHimself($comparedUser)
    {
        return $this->is($comparedUser);
    }
}
