<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Menu::make('MainNav', function ($menu) {
        	$menu->add('Services', ['class'=>'menu-title', 'id'=>'services']);
        	$menu->add('Utilities', ['class'=>'menu-title', 'id'=>'utilities']);
        	$menu->add('Administration', ['class'=>'menu-title', 'id'=>'administration']);
    	});
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
