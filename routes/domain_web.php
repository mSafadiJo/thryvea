<?php
//Domains
Route::group(['middleware' => 'auth'], function() {
    Route::get('/admin/domains', 'Domains\DomainsController@ListDomains')->name('AllDomains');
    Route::get('/admin/domain/edit/{id}', 'Domains\DomainsController@Edit_Domain')->where('id', '[0-9]+')->name('Edit_Domain');

    Route::post('/admin/domain/edit/save', 'Domains\DomainsController@Edit_Domain_save')->name('Edit_Domain_save');

    Route::get('/admin/domain/add', 'Domains\DomainsController@AddForm')->name('DomainAddForm');
    Route::post('/AdminDomain/Store', 'Domains\DomainsController@Store')->name('AdminDomainStore');
    Route::post('/admin/domain/delete/{id}', 'Domains\DomainsController@DeleteDomain')->where('id', '[0-9]+')->name('DeleteDomainAdmin');
    Route::post('/AdminDomain/TrafficSorce', 'Domains\DomainsController@getAllTrafficSorce')->name('getAllTrafficSorce');
//ajax
    Route::get('/ajax/traffic', 'Domains\DomainsController@trafficSourceAjax')->name('trafficSourceAjax');
    Route::post('/updateStatusDomainsUserAdmin', 'Domains\DomainsController@changeStatus')->name('updateStatusDomainsUserAdmin');
});
