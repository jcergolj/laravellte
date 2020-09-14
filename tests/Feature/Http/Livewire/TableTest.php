<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/** @see \App\Http\Livewire\Table */
class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mount()
    {
        $table = new class() {
            use Table;
            public $sortField = 'name';
            public $sortDirection = 'asc';
        };

        $request = new Request();
        $request->setRouteResolver(function () {
            return Route::get('home', 'App\Http\Controllers\HomeController@index')
                ->name('home.index');
        });

        $table->mount($request);

        $this->assertSame('home.index', $table->routeName);
    }

    /** @test */
    public function sortBy()
    {
        $table = new class() {
            use Table;
            public $sortField = 'name';
            public $sortDirection = 'asc';
        };

        $table->sortBy('name');

        $this->assertSame('name', $table->sortField);
        $this->assertSame('desc', $table->sortDirection);

        $table->sortBy('name');

        $this->assertSame('name', $table->sortField);
        $this->assertSame('asc', $table->sortDirection);

        $table->sortBy('email');

        $this->assertSame('email', $table->sortField);
        $this->assertSame('asc', $table->sortDirection);

        $table->sortBy('email');

        $this->assertSame('email', $table->sortField);
        $this->assertSame('desc', $table->sortDirection);
    }
}
