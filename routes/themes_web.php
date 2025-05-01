<?php

//Theme
Route::group(['middleware' => 'auth'], function(){
    Route::get('/admin/themes', 'Themes\ThemesController@ListThemes')->name('AllThemes');
    Route::get('/admin/themes/edit/{id}', 'Themes\ThemesController@Edit_Theme')->where('id', '[0-9]+')->name('Edit_Theme');
    Route::post('/AdminTheme/Update', 'Themes\ThemesController@updateThemeUserAdmin')->name('updateThemesUserAdmin');
    Route::post('/AdminTheme/Update/Status', 'Themes\ThemesController@updateStatusThemesUserAdmin')->name('updateStatusThemesUserAdmin');
    Route::get('/admin/theme/add', 'Themes\ThemesController@AddForm')->name('ThemeAddForm');
    Route::post('/AdminTheme/Store', 'Themes\ThemesController@Store')->name('AdminThemeStore');
    Route::post('/AdminTheme/Delete/{id}', 'Themes\ThemesController@DeleteTheme')->where('id', '[0-9]+')->name('DeleteThemeAdmin');
    Route::post('/AdminTheme/GetAllImage', 'Themes\ThemesController@ListImageThemes')->name('ListImageThemes');
});
