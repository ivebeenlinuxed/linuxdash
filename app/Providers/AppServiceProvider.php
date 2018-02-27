<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Theme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
	if(session()->has('theme-name')){
        	Theme::set(session('theme-name'));
    	} else {
		Theme::set("Bootstrap4Dashboard");
	}
	
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
}
