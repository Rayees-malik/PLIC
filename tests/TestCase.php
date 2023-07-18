<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\LaravelRay\RayServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn($role = null): User
    {
        return tap(User::factory()->create(), function ($user) use ($role) {
            if ($role) {
                $user->assign($role);
            }

            $this->actingAs($user);
        });
    }

    public function shouldHaveCalledAction(string $action, string $method = 'execute')
    {
        $original = $this->app->make($action);

        $this->mock($action)
            ->shouldReceive($method)
            ->atLeast()->once()
            ->andReturnUsing(fn (...$args) => $original->$method(...$args));
    }

    protected function getPackageProviders($app)
    {
        return [
            RayServiceProvider::class,
        ];
    }
}
