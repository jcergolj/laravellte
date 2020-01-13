<?php

namespace App\Filters;

class RoleFilter extends Filter
{
    /**
     * Filter by name field.
     *
     * @param  mixed  $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search($term = null)
    {
        return $this->builder
            ->when($term, function ($query, $term) {
                $query->where('name', 'LIKE', "%$term%");
            });
    }
}
