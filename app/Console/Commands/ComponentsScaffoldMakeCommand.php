<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ComponentsScaffoldMakeCommand extends Command
{
    /** @var string */
    public $ucArgument;

    /** @var string */
    public $loArgument;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ltd-component {name} {--index} {--create} {--show} {--edit} {--delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new component classes, views and testes for selected actions.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->ucArgument = ucfirst($this->argument('name'));
        $this->loArgument = strtolower($this->argument('name'));

        $this->createViewFolder();

        $this->getAppliedOptions()
            ->each(function ($used, $action) {
                $this->createForAction($action);
            });

        $this->showOutput($this->ucArgument);
    }

    /**
     * Create view folder if doesn't exist.
     *
     * @return void
     */
    protected function createViewFolder()
    {
        if (file_exists(base_path("resources/views/{$this->loArgument}"))) {
            return;
        }

        mkdir(base_path("resources/views/{$this->loArgument}"), 0755);
    }

    /**
     * Get applied options.
     *
     * @param  mixed  name
     * @return \Illuminate\Support\Collection
     */
    protected function getAppliedOptions()
    {
        return collect($this->options())
            ->filter(function ($used, $action) {
                return $used;
            });
    }

    /**
     * Create files for each action.
     *
     * @param  mixed  $action
     * @return void
     */
    protected function createForAction($action)
    {
        foreach ($this->getFiles($action) as $file => $path) {
            $this->createStub($action, $file, $path);
        }
    }

    /**
     * Get file to stub.
     *
     * @return array
     */
    protected function getFiles($option)
    {
        $ucOption = ucfirst($option);
        $lowOption = strtolower($option);

        $files = [
            'view' => "resources/views/{$this->loArgument}/{$lowOption}.blade.php",
            'class' => "app/Http/Livewire/{$ucOption}{$this->ucArgument}Component.php",
            'test' => "tests/Feature/Http/Livewire/{$ucOption}{$this->ucArgument}ComponentTest.php",
        ];

        if ($option === 'index') {
            $files['filter'] = "app/Filters/{$this->ucArgument}Filter.php";
        }

        return $files;
    }

    /**
     * Create files form stubs.
     *
     * @param  string  $option
     * @param  string  $file
     * @param  string  $path
     * @return void
     */
    protected function createStub($option, $file, $path)
    {
        $fileContent = file_get_contents(base_path("stubs/laravellte/{$option}.{$file}.stub"));
        $stub = str_replace(
            ['{{ DummyText }}', '{{ dummyText }}', '{{ DummyTextPlu }}', '{{ dummyTextPlu }}'],
            [
                ucfirst($this->argument('name')),
                strtolower($this->argument('name')),
                Str::plural(ucfirst($this->argument('name'))),
                Str::plural(strtolower($this->argument('name'))),
            ],
            $fileContent
        );

        $file = fopen(base_path("{$path}"), 'w');
        fwrite($file, $stub);
        fclose($file);
    }

    /**
     * Generate info.
     *
     * @return void
     */
    protected function showOutput()
    {
        $argumentPlu = Str::plural($this->loArgument);
        $this->info('Please add following routes:');

        $routes = [
            'index' => "Route::get('{$argumentPlu}', Index{$this->ucArgument}Component::class)->name('{$argumentPlu}.index')",
            'create' => "Route::get('{$argumentPlu}/create', Create{$this->ucArgument}Component::class)->name('{$argumentPlu}.create')",
            'show' => "Route::get('{$argumentPlu}', Show{$this->ucArgument}Component::class)->name('{$argumentPlu}.show')",
            'edit' => "Route::get('{$argumentPlu}/{".strtolower($this->argument('name'))."}/edit', Edit{$this->ucArgument}Component::class)->name('{$argumentPlu}.edit')",
        ];

        $this->getAppliedOptions()
            ->filter(function ($applied, $action) {
                return $action !== 'delete';
            })
            ->each(function ($applied, $action) use ($routes) {
                return $this->info($routes[$action]);
            });

        $this->info("\nAdd an review \Tests\Unit\Http\Middleware\AuthorizationMiddlewareTest. Add @dataProvider {$this->ucArgument}RoutesProvider.");
        $this->info("\nFactory and Model needs to be created too in most cases.");
    }
}
