<?php

// Holes Routes
Route::group(['middleware'=>'language','prefix' => "admin-panel",'namespace' => "Hole"] , function($router){
    Route::get('home' , 'HomeController@home')->name('hole.home_panel');
//    Route::resources('holes' , 'HoleController@home');

    Route::group([ 'prefix' => 'holes',] , function($router){
        Route::get('show' , 'HoleController@index')->name('holes.show');
        Route::get('create' , 'HoleController@create')->name('holes.create');
        Route::post('store' , 'HoleController@store')->name('holes.store');
        Route::get('block/{id}' , 'HoleController@block');
        Route::get('active/{id}' , 'HoleController@active');
//        Route::get('edit/{id}' , 'HoleController@edit');
    });
});

Route::get('/login' ,  [ 'as' => 'adminlogin', 'uses' => 'Hole_admin\LoginController@getlogin']);
Route::post('/login' , 'Hole_admin\LoginController@postlogin')->name('hole.login');

Route::get('admin-panel/hole/home' , 'Hole_admin\HomeController@home')->name('hole.home');
//Hole admin panel
Route::group(['middleware'=> ['language','hole'],'prefix' => "admin-panel",'namespace' => "Hole_admin"] , function($router){


    Route::get('/hole/logout' , 'LoginController@logout');
    Route::get('/hole/profile' , 'HomeController@profile');
    Route::post('/hole/profile' , 'HomeController@updateprofile');

});



