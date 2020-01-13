<?php

namespace App\Http\Livewire;

use App\Traits\LivewireAuth;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Table extends Component
{
    use WithPagination, LivewireAuth, Flashable;

    /** @var int */
    public $perPage = 10;

    /** @var bool */
    public $sortDirection = 'asc';

    /** @var string */
    public $search = '';

    /** @var array */
    protected $updatesQueryString = ['perPage', 'sortField', 'sortDirection', 'search'];

    /** @var array */
    protected $listeners = ['destroy' => 'destroy'];

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
