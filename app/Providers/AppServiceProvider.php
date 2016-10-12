<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('file_model_uniqueness', function ($attribute, $value, $parameters, $validator) {
            $storage = $this->app['filesystem'];

            list($file, $field) = $parameters;

            if ($storage->exists($file)) {
                $list = unserialize($storage->get($file));

                $result = ! $list->where($field, $value, false)->first();
            } else {
                $result = true;
            }

            return $result;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
