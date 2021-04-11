<?php

// Holes Routes
Route::group(['middleware'=>'language','prefix' => "admin-panel",'namespace' => "Admin\Coach"] , function($router){

    Route::group([ 'prefix' => 'coaches',] , function($router){
        Route::get('show' , 'CoachController@index')->name('coaches.show');
        Route::get('details/{id}' , 'CoachController@show')->name('coaches.details');
        Route::get('create' , 'CoachController@create')->name('coaches.create');
        Route::post('sort' , 'CoachController@sort')->name('coaches.sort');
        Route::get('edit/{id}' , 'CoachController@edit')->name('coaches.edit');
        Route::post('update/{id}' , 'CoachController@update')->name('coaches.update');
        Route::post('store' , 'CoachController@store')->name('coaches.store');
        Route::get('change_status/{status}/{id}' , 'CoachController@change_status');
        Route::get('make_famous/{id}' , 'CoachController@make_famous')->name('coaches.make_famous');
        Route::get('rates/{id}' , 'CoachController@rates')->name('coaches.rates');
        Route::get('/rate/change_status/{type}/{id}' , 'CoachController@change_rate_status')->name('coaches.change_status');
    });
    Route::get('famous_coaches' , 'CoachController@famous_coaches')->name('famous_coaches');
});
