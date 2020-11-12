<?php

namespace Tests\Feature\Console\Commands;

use Tests\TestCase;

class ComponentsScaffoldMakeCommandTest extends TestCase
{
    public function tearDown() : void
    {
        parent::tearDown();

        @unlink(base_path('resources/views/bla/index.blade.php'));
        @unlink(base_path('app/Http/Livewire/IndexBlaComponent.php'));
        @unlink(base_path('tests/Feature/Http/Livewire/IndexBlaComponentTest.php'));
        @unlink(base_path('app/Filters/BlaFilter.php'));

        @unlink(base_path('resources/views/bla/create.blade.php'));
        @unlink(base_path('app/Http/Livewire/CreateBlaComponent.php'));
        @unlink(base_path('tests/Feature/Http/Livewire/CreateBlaComponentTest.php'));

        @unlink(base_path('resources/views/bla/show.blade.php'));
        @unlink(base_path('app/Http/Livewire/ShowBlaComponent.php'));
        @unlink(base_path('tests/Feature/Http/Livewire/ShowBlaComponentTest.php'));

        @unlink(base_path('resources/views/bla/edit.blade.php'));
        @unlink(base_path('app/Http/Livewire/EditBlaComponent.php'));
        @unlink(base_path('tests/Feature/Http/Livewire/EditBlaComponentTest.php'));

        @unlink(base_path('resources/views/bla/delete.blade.php'));
        @unlink(base_path('app/Http/Livewire/DeleteBlaComponent.php'));
        @unlink(base_path('tests/Feature/Http/Livewire/DeleteBlaComponentTest.php'));
    }

    /** @test */
    public function index_files_are_scaffolded()
    {
        $this->artisan('make:ltd-component bla --index')
            ->expectsOutput("Route::get('blas', IndexBlaComponent::class)->name('blas.index')")
            ->expectsOutput("\nAdd an review \Tests\Unit\Http\Middleware\AuthorizationMiddlewareTest. Add @dataProvider BlaRoutesProvider.")
            ->expectsOutput("\nFactory and Model needs to be created too in most cases.")
            ->assertExitCode(0);
    }

    /** @test */
    public function create_files_are_scaffolded()
    {
        $this->artisan('make:ltd-component bla --create')
            ->expectsOutput("Route::get('blas/create', CreateBlaComponent::class)->name('blas.create')")
            ->assertExitCode(0);
    }

    /** @test */
    public function show_files_are_scaffolded()
    {
        $this->artisan('make:ltd-component bla --show')
            ->expectsOutput("Route::get('blas', ShowBlaComponent::class)->name('blas.show')")
            ->assertExitCode(0);
    }

    /** @test */
    public function edit_files_are_scaffolded()
    {
        $this->artisan('make:ltd-component bla --edit')
            ->expectsOutput("Route::get('blas/{bla}/edit', EditBlaComponent::class)->name('blas.edit')")
            ->assertExitCode(0);
    }

    /** @test */
    public function delete_files_are_scaffolded()
    {
        $this->artisan('make:ltd-component bla --delete')
            ->assertExitCode(0);
    }
}
