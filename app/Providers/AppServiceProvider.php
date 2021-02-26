<?php

namespace App\Providers;

use App\Services\Team\AirtableGridView;
use App\Services\Team\Manager as TeamDatabaseManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(TeamDatabaseManager::class, function ($app) {
            // goes through each provider and return client accordingly
            if (config('team.default') === 'airtable') {
                return new TeamDatabaseManager($app->make(AirtableGridView::class));
            }

            // default provider
            return new TeamDatabaseManager($app->make(AirtableGridView::class));
        });
    }
}
