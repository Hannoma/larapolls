<?php
namespace Hannoma\Larapolls;

use Illuminate\Support\ServiceProvider;
use Hannoma\Larapolls\Commands\PermissionSetup;
use Hannoma\Larapolls\PollDrawer;

class LarapollsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
    /**
     * Boot What is needed
     *
     */
    public function boot()
    {
        // migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        //translations
        $this->loadTranslationsFrom(__DIR__.'/translations', 'larapolls');
        // routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        // views
        $this->loadViewsFrom(__DIR__.'/views', 'larapolls');
        // config
        $this->publishes([
          __DIR__.'/config/larapolls.php' => config_path('larapolls.php'),
          __DIR__.'/views' => resource_path('views/vendor/larapolls'),
          __DIR__.'/translations' => resource_path('lang/vendor/larapolls'),
        ]);

        //ARTISAN
        if ($this->app->runningInConsole()) {
          $this->commands([
            PermissionSetup::class,
          ]);
        }
    }
}
