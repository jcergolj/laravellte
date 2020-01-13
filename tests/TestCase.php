<?php

namespace Tests;

use App\Providers\EventServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Assert event has listener.
     *
     * @param [type] $event
     * @param [type] $listener
     * @return boll
     */
    public function assertEventHasListener($event, $listener)
    {
        $events = [];

        foreach ($this->app->getProviders(EventServiceProvider::class) as $provider) {
            $providerEvents = array_merge_recursive(
                $provider->shouldDiscoverEvents() ? $provider->discoverEvents() : [], $provider->listens()
            );

            $events = array_merge_recursive($events, $providerEvents);
        }

        $this->assertContains($listener, $events[$event]);
    }

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
