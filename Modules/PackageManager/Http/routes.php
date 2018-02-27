<?php

Route::group(['middleware' => 'web', 'prefix' => 'packagemanager', 'namespace' => 'Modules\PackageManager\Http\Controllers'], function()
{
    Route::get('/', 'PackageManagerController@index');
	Route::get('/get_update_tasks', 'PackageManagerController@get_update_tasks');
	Route::get('/get_repo/{id}', 'PackageManagerController@get_repo');
	Route::get('/get_module_manifest/{name}', 'PackageManagerController@get_module_manifest');
	Route::post('/install_module', 'PackageManagerController@install_module');
	
	
	
});
