<?php

//Pixels
Route::group(['middleware' => 'auth'], function() {
    Route::get('/admin/pixels', 'Pixels\pixelsController@index')->name('pixels');
});
