<?php

namespace Api;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    protected $namespace = "Api";

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
    }

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
     * Register api routes.
     *
     * @return void
     */
    public function registerRoutes()
    {
        Route::name("api.")
            ->prefix("api")
            ->namespace($this->namespace)
            ->group(function() {
                foreach (glob(__DIR__."/*/routes.php") as $path) {
                    include_once($path);
                }
            });
    }

    /**
     * Register api translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        Lang::addNamespace($this->namespace, "/Translations");
    }
}
