<?php

Route::group(['middleware' => 'web', 'prefix' => 'queuemanager', 'namespace' => 'Modules\QueueManager\Http\Controllers'], function()
{
    Route::get('/', 'QueueManagerController@index');
});
