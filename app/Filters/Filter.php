<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /**
     * @var array
     */
    protected $filters;

    /**
     * The builder instance.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Create a new QueryFilters instance.
     *
     * @param  array $filters
     */
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Apply the filters to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }

            if (is_array($value) && $value !== '') {
                $this->$name($value);
            } elseif (strlen($value) || $value === false) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->builder;
    }

    /**
     * Order results by field in specific order.
     *
     * @param  mixed  $array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBy($array)
    {
        return $this->builder->orderBy($array[0], $array[1] ? 'asc' : 'desc');
    }
}
