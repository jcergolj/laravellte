<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get the view path based on convention
     * based on controller's name and parent function's name.
     *
     * @return string
     */
    protected function resolveViewPath()
    {
        $controllerName = Str::of(static::class)
            ->afterLast('\\')
            ->lower()
            ->replace('controller', '');

        return "{$controllerName}s.".debug_backtrace()[1]['function'];
    }
}
