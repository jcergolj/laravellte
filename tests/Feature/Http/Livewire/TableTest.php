<?php

namespace Tests\Feature\Http\Livewire;

use App\Http\Livewire\Table;
use App\Traits\LivewireAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Livewire\Table
 */
class TableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function assert_table_has_trait()
    {
        $this->assertContains(LivewireAuth::class, class_uses(Table::class));
    }

    /** @test */
    public function sortBy()
    {
        $table = new class(1) extends Table {
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
