<?php

namespace App\Models;

use App\Filters\UserFilter;
use App\Providers\AppServiceProvider;
use App\Scopes\VisibleToScope;
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
        AppServiceProvider::OWNER_FIELD => 'integer',
        'email_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VisibleToScope());
    }

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
     * Get the user's owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
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
     * Get first user's permission.
     *
     * @param  string  $permissionName
     * @return bool
     */
    public function getPermission($permissionName)
    {
        return $this->role->permissions()
            ->where('name', $permissionName)
            ->first();
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

    /**
     * Is user a model owner.
     *
     * @param  string  $permissionName
     * @param  mixed  $model
     * @return bool
     */
    public function isModelOwner($permissionName, $model)
    {
        $ownerField = AppServiceProvider::OWNER_FIELD;

        $permission = $this->getPermission($permissionName);

        if ($permission === null) {
            return false;
        }

        if ($permission->pivot->owner_restricted === false) {
            return true;
        }

        return $model->$ownerField === $this->id;
    }
}
