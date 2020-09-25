<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\HasTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @see \App\Http\Livewire\HasTable */
class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function sortBy()
    {
        $table = new class() {
            use HasTable;
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
