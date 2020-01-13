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
        return $this->builder
            ->when($term, function ($query, $term) {
                $query->where('email', 'LIKE', "%$term%");
            });
    }
}
