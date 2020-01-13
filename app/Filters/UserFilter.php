<?php

namespace App\Filters;

class UserFilter extends Filter
{
    /**
     * Filter by email field.
     *
     * @param  mixed  $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search($term = null)
    {
        $this->when($term, function ($query, $term) {
            $query->where('email', 'LIKE', "%$term%");
        });

        return $this;
    }

    /**
     * Filter by role.
     *
     * @param  mixed  $roleId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function roleId($roleId = null)
    {
        $this->when($roleId, function ($query, $roleId) {
            $query->where('role_id', $roleId);
        });

        return $this;
    }
}
