<?php

// Holes Routes
Route::group(['middleware'=>'language','prefix' => "admin-panel",'namespace' => "Hole"] , function($router){
    Route::get('home' , 'HomeController@home')->name('hole.home_panel');
//    Route::resources('holes' , 'HoleController@home');

    Route::group([ 'prefix' => 'holes',] , function($router){
        Route::get('show' , 'HoleController@index')->name('holes.show');

        Route::get('details/{id}' , 'HoleController@show')->name('holes.details');
        Route::get('create' , 'HoleController@create')->name('holes.create');
        Route::post('store' , 'HoleController@store')->name('holes.store');
        Route::get('change_status/{status}/{id}' , 'HoleController@change_status');
        Route::get('holes.make_famous/{id}' , 'HoleController@make_famous')->name('holes.make_famous');
//        Route::get('edit/{id}' , 'HoleController@edit');
    });
    Route::get('famous_holes' , 'HoleController@famous_holes')->name('famous_holes');
});

//Route::get('/login' ,  [ 'as' => 'adminlogin', 'uses' => 'Hole_admin\LoginController@getlogin']);
Route::get('/login' , 'Hole_admin\LoginController@getlogin')->name('hole_login');
Route::post('/login' , 'Hole_admin\LoginController@postlogin')->name('post.hole.login');

Route::get('/hole-panel/hole/home' , 'Hole_admin\HomeController@home')->name('hole.home');
//Hole admin panel
Route::group(['middleware'=> ['language','hole'],'prefix' => "admin-panel",'namespace' => "Hole_admin"] , function($router){
    Route::get('/hole/logout' , 'LoginController@logout');
    Route::get('/hole/profile' , 'HomeController@profile');
    Route::post('/hole/profile' , 'HomeController@updateprofile');

});



