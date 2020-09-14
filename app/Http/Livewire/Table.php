<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\WithPagination;

trait Table
{
    use WithPagination, CanFlash;

    /** @var int */
    public $perPage = 10;

    /** @var bool */
    public $sortDirection = 'asc';

    /** @var string */
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    /**
     * Component mount.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function mount(Request $request)
    {
        $this->routeName = $request->route()->getName();
    }

    /**
     * Sort results by field.
     *
     * @param  string  $field
     * @return void
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field && $this->sortDirection === 'asc') {
            $this->sortDirection = 'desc';
        } elseif ($this->sortField === $field && $this->sortDirection === 'asc') {
            $this->sortDirection = 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
}
