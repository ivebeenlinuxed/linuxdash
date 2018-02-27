<?php

Route::group(['middleware' => 'web', 'prefix' => 'helloworld', 'namespace' => 'Modules\HelloWorld\Http\Controllers'], function()
{
	
    Route::get('/', 'HelloWorldController@index');
});
