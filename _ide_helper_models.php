<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $group
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PermissionRole
 *
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 * @property bool|null $owner_restricted
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole whereOwnerRestricted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole whereRoleId($value)
 */
	class PermissionRole extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \App\Filters\RoleFilter|Role filter(array $filters)
 * @method static \App\Filters\RoleFilter|Role newModelQuery()
 * @method static \App\Filters\RoleFilter|Role newQuery()
 * @method static \Illuminate\Database\Query\Builder|Role onlyTrashed()
 * @method static \App\Filters\RoleFilter|Role orderByField($field, $direction)
 * @method static \App\Filters\RoleFilter|Role query()
 * @method static \App\Filters\RoleFilter|Role search($term = null)
 * @method static \App\Filters\RoleFilter|Role whereCreatedAt($value)
 * @method static \App\Filters\RoleFilter|Role whereDeletedAt($value)
 * @method static \App\Filters\RoleFilter|Role whereId($value)
 * @method static \App\Filters\RoleFilter|Role whereLabel($value)
 * @method static \App\Filters\RoleFilter|Role whereName($value)
 * @method static \App\Filters\RoleFilter|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int $owner_id
 * @property int $role_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string $image_file
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read User $owner
 * @property-read \App\Models\Role $role
 * @method static \App\Filters\UserFilter|User filter(array $filters)
 * @method static \App\Filters\UserFilter|User newModelQuery()
 * @method static \App\Filters\UserFilter|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \App\Filters\UserFilter|User orderByField($field, $direction)
 * @method static \App\Filters\UserFilter|User query()
 * @method static \App\Filters\UserFilter|User roleId($roleId = null)
 * @method static \App\Filters\UserFilter|User search($term = null)
 * @method static \App\Filters\UserFilter|User whereCreatedAt($value)
 * @method static \App\Filters\UserFilter|User whereDeletedAt($value)
 * @method static \App\Filters\UserFilter|User whereEmail($value)
 * @method static \App\Filters\UserFilter|User whereEmailVerifiedAt($value)
 * @method static \App\Filters\UserFilter|User whereId($value)
 * @method static \App\Filters\UserFilter|User whereImage($value)
 * @method static \App\Filters\UserFilter|User whereOwnerId($value)
 * @method static \App\Filters\UserFilter|User wherePassword($value)
 * @method static \App\Filters\UserFilter|User whereRememberToken($value)
 * @method static \App\Filters\UserFilter|User whereRoleId($value)
 * @method static \App\Filters\UserFilter|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

