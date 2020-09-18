<?php

namespace App\Models;

use App\Filters\RoleFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * The permissions that belong to the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
            ->withPivot('owner_restricted')
            ->using(PermissionRole::class);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new RoleFilter($query);
    }

    /**
     * Create role's permissions.
     *
     * @param  array  $permissions
     * @return void
     */
    public function createPermissions($permissions)
    {
        $permissions = $this->removeDisallowedPermissions($permissions);

        $this->permissions()->attach($this->dataToSync($permissions));
    }

    /**
     * Does role have permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->permissions()
            ->where('name', $permission)
            ->first() ? true : false;
    }

    /**
     * Update role's permissions.
     *
     * @param  array  $permissions
     * @return void
     */
    public function updatePermissions($permissions)
    {
        $permissions = $this->removeDisallowedPermissions($permissions);

        $this->permissions()->sync($this->dataToSync($permissions));
    }

    /**
     * Is this an admin role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->name === 'admin';
    }

    /**
     * Remove permissions where allowed is false.
     *
     * @param  array  $permissions
     * @return array
     */
    protected function dataToSync($permissions)
    {
        $dataForSync = [];
        foreach ($permissions as $id => $permission) {
            $dataForSync[$id] = ['owner_restricted' => $permission['owner_restricted'] ?? false];
        }

        return $dataForSync;
    }

    /**
     * Remove permissions where allowed is false.
     *
     * @param  array  $permissions
     * @return array
     */
    protected function removeDisallowedPermissions($permissions)
    {
        return array_filter($permissions, function ($permission) {
            return $permission['allowed'];
        });
    }
}
