<?php

namespace App\Providers;

use App\Contracts\Geocoding\GeocodingGateway;
use App\Geocoding\GoogleMapsGateway;
use App\PartialValidator;
use Bouncer;
use Honeybadger\Contracts\Reporter;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Sanitizer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GeocodingGateway::class, GoogleMapsGateway::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Bouncer::useAbilityModel(\App\Models\Ability::class);
        Bouncer::useRoleModel(\App\Models\Role::class);

        Sanitizer::extend('name_case', \App\Filters\NameCaseFilter::class);
        Sanitizer::extend('sum_array', \App\Filters\SumArrayFilter::class);
        Sanitizer::extend('create_catalogue_category', \App\Filters\CreateCatalogueCategoryFilter::class);

        Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new PartialValidator($translator, $data, $rules, $messages);
        });

        ParallelTesting::setUpTestDatabase(function () {
            Artisan::call('db:seed');
        });

        Model::preventLazyLoading(! app()->environment(['production', 'testing']));

        Event::macro('pingHoneybadgerOnSuccessFromConfig', function (string $configKey, $environments = null) {
            $id = config($configKey);

            if (! $id) {
                return $this;
            }

            return $this->onSuccess(function () use ($id, $environments) {
                if ($environments === null || app()->environment($environments)) {
                    app(Reporter::class)->checkin($id);
                }
            });
        });
    }
}
