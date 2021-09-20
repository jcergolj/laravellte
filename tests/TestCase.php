<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Get array of middleware for given route.
     *
     * @param  string  $route
     * @return array
     */
    protected function getMiddlewareFor($route)
    {
        return array_map(function ($middleware) {
            return explode(':', $middleware)[0];
        }, Route::getRoutes()->getByName($route)->gatherMiddleware());
    }
}
