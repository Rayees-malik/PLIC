<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('loop', fn ($expression) => "<?php foreach{$expression}: ?>");
        Blade::directive('endloop', fn ($expression) => '<?php endforeach; ?>');

        Blade::directive('branch', function () {
            if (Cache::has('branch')) {
                return Cache::get('branch');
            }

            if (! app()->environment('production') && file_exists(base_path('BRANCH'))) {
                $branch = trim(file_get_contents(base_path('BRANCH')));
                Cache::rememberForever('branch', fn () => $branch);

                return $branch;
            }
        });
    }
}
