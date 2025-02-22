<?php

namespace Tests\Feature;

use App\Http\Middleware\DebugbarEnable;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Tests\TestCase;

class DebugbarEnableTest extends TestCase
{
    /**
     * Test that the Debugbar is enabled for a logged in user.
     *
     * @see App\Http\Middleware\DebugbarEnable::handle()
     */
    public function testDebugbarIsEnabledForLoggedInUser()
    {
        $this->actingAs($this->adminUser());

        // Create a dummy request
        $request = Request::create('/test', 'GET');

        $middleware = new DebugbarEnable;
        $middleware->handle($request, function ($request) {
            $this->assertTrue(Debugbar::isEnabled());
            $this->assertTrue(Debugbar::hasCollector('time'));

            return response('OK');
        });
    }

    /**
     * Test that the Debugbar is disabled for a basic user.
     *
     * @see App\Http\Middleware\DebugbarEnable::handle()
     */
    public function testDebugbarIsDisabledForBasicUser()
    {
        $this->actingAs($this->basicUser());

        // Create a dummy request
        $request = Request::create('/test', 'GET');

        $middleware = new DebugbarEnable;
        $middleware->handle($request, function ($request) {
            $this->assertFalse(Debugbar::isEnabled());
            $this->assertFalse(Debugbar::hasCollector('time'));

            return response('OK');
        });
    }

    /**
     * Test that the Debugbar is disabled for a guest user.
     *
     * @see App\Http\Middleware\DebugbarEnable::handle()
     */
    public function testDebugbarIsDisabledForGuestUser()
    {
        // Create a dummy request
        $request = Request::create('/test', 'GET');

        $middleware = new DebugbarEnable;
        $middleware->handle($request, function ($request) {
            $this->assertFalse(Debugbar::isEnabled());
            $this->assertFalse(Debugbar::hasCollector('time'));

            return response('OK');
        });
    }
}
