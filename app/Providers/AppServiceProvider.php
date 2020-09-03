<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         if (config('app.env')==='production')
          $this->app->bind('path.public', function(){
            return '/home/elecrtwh/public_html/acsys.djara.dev/sysapitest.laravel';
          });
        else
          $this->app->bind('path.public', function(){
            return '/home/djaravirtual/Projects/home/elecrtwh/public_html/acsys.djara.dev/sysapitest.laravel';
          });



        $variables = [
            'public_path()'=>public_path(),
            'base_path()' => base_path(),
            'storage_path()'=>storage_path(),
        ];
        //dd($variables);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
