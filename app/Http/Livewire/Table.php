<?php

namespace App\Http\Livewire;

use App\Traits\LivewireAuth;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Table extends Component
{
    use WithPagination, LivewireAuth;

    protected $listeners = ['destroy' => 'destroy'];

    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @var bool
     */
    public $sortAsc = true;

    /**
     * @var string
     */
    public $search = '';

    /**
     * @var array
     */
    protected $updatesQueryString = ['perPage', 'sortField', 'sortAsc', 'search'];

    /**
     * Sort results by field.
     *
     * @param  string  $field
     * @return void
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }
}
