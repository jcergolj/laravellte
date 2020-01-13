<?php

namespace App\ViewModels;

use App\Models\Permission;
use App\Models\PermissionRole;

class SaveRoleViewModel
{
    /**
     * Build role-permissions array for storing/updating roles.
     *
     * @param  int|null  $roleId
     * @return array
     */
    public static function buildRolePermissions($roleId = null)
    {
        $permissions = Permission::orderBy('group', 'asc')->get();

        $assignedPermissions = [];
        $rolePermissions = self::getRolePermissions($roleId);

        foreach ($permissions as $permission) {
            $assignedPermissions[$permission->id]['group'] = $permission->group;
            $assignedPermissions[$permission->id]['description'] = $permission->description;
            $assignedPermissions[$permission->id]['allowed'] = key_exists($permission->id, $rolePermissions);
            $assignedPermissions[$permission->id]['owner_restricted'] = $rolePermissions[$permission->id] ?? false;
        }

        return $assignedPermissions;
    }

    /**
     * Group permissions by group name.
     *
     * @param  array  $permissions
     * @return array
     */
    public static function groupPermissions($permissions)
    {
        return collect($permissions)
            ->groupBy('group', true)
            ->sortKeys()
            ->toArray();
    }

    /**
     * Get current role permissions.
     *
     * @param  int  $roleId
     * @return array
     */
    private static function getRolePermissions($roleId)
    {
        if ($roleId === null) {
            return [];
        }

        return PermissionRole::where('role_id', $roleId)
            ->get()
            ->pluck('owner_restricted', 'permission_id')
            ->toArray();
    }
}
