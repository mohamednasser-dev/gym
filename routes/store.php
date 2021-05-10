<?php

// admin  Routes
Route::group(['middleware'=>'language','prefix' => "admin-panel",'namespace' => "Admin\Store"] , function($router){

    Route::get('store/home' , 'HomeController@home')->name('store.home_panel');

    // Shops Route
    Route::group([
        "prefix" => "shops"
    ] , function($router){
        Route::get('/show' , 'ShopController@index')->name('shops.show');
        Route::get('add' , 'ShopController@AddGet')->name('shops.create');
        Route::post('add' , 'ShopController@AddPost');
        Route::get('make_famous/{id}' , 'ShopController@make_famous')->name('shop.make_famous');
        Route::get('edit/{store}' , 'ShopController@EditGet')->name('shops.edit');
        Route::post('edit/{store}' , 'ShopController@EditPost');
        Route::get('details/{store}' , 'ShopController@details')->name('shops.details');
        Route::get('action/{store}/{status}' , 'ShopController@action')->name('shops.action');
    });
    Route::get('shops_famous' , 'ShopController@famous')->name('shops_famous');
});

// admin  Routes
Route::group(['middleware'=>'language','prefix' => "shop-panel",'namespace' => "Shop_admin"] , function($router){

    Route::get('shop/home' , 'HomeController@home')->name('shop.home');

    Route::get('/logout' , 'HomeController@logout')->name('shop.logout');
    Route::get('/profile' , 'HomeController@profile')->name('shop.profile');
    Route::post('/profile' , 'HomeController@updateprofile');

    // Products Route
    Route::group([
        "prefix" => "products"
    ], function($router){
        Route::get('show' , 'ProductController@show')->name('products.index');
        Route::get('fetchbrands/{category}' , 'ProductController@fetch_category_brands');
        Route::get('fetchsubcategories/{brand}' , 'ProductController@fetch_brand_sub_categories');
        Route::get('fetchproducts/{subCategory}' , 'ProductController@sub_category_products');
        Route::get('fetchcategoryproducts/{category}' , 'ProductController@fetch_category_products');
        Route::get('fetchproductsbystore/{store}' , 'ProductController@fetch_products_by_store');
        Route::get('fetchbrandproducts/{brand}' , 'ProductController@fetch_brand_products');
        Route::get('fetchcategoryoptions/{category}' , 'ProductController@fetch_category_options');
        Route::get('fetchsubcategorymultioptions/{category}' , 'ProductController@fetch_sub_category_multi_options');
        Route::get('validatebarcodeunique/{type}/{text}' , 'ProductController@validate_barcode_unique');
        Route::get('edit/{product}' , 'ProductController@EditGet')->name('products.edit');
        Route::post('edit/{product}' , 'ProductController@EditPost');
        Route::get('delete/productimage/{productImage}' , 'ProductController@delete_product_image')->name("productImage.delete");
        Route::get('details/{product}' , 'ProductController@details')->name('products.details');
        Route::get('delete/{product}' , 'ProductController@delete')->name('products.delete');
        Route::get('search' , 'ProductController@product_search')->name('products.search');
        Route::get('searched' , 'ProductController@product_search');
        Route::post('update/quantity/{product}' , 'ProductController@update_quantity')->name('update.quantity');
        Route::post('update/quantity/option/{option}' , 'ProductController@update_quantity_m_option')->name('option.update.quantity');
        Route::get('add' , 'ProductController@AddGet')->name('products.add');
        Route::post('add' , 'ProductController@AddPost');
        Route::get('hide/{product}/{status}' , 'ProductController@visibility_status_product')->name('products.visibility.status');
        Route::get('getbysubcat' , 'ProductController@get_product_by_sub_cat')->name('products.getbysubcat');
        Route::get('fetchsubcategorybycategory/{category}' , 'ProductController@fetch_sub_categories_by_category');
        Route::get('review/{product}/{status}' , 'ProductController@review_product')->name('products.review');
    });

    // Offer Control Route
    Route::group([
        "prefix" => "offers_control"
    ], function($router){
        Route::get('add' , 'OffersControlController@AddGet')->name('offers_control.add');
        Route::post('add' , 'OffersControlController@AddPost');
        Route::post('sort' , 'OffersControlController@updateOffersSorting')->name('offers_control.sort');
        Route::get('edit/{section}' , 'OffersControlController@EditGet')->name('offers_control.edit');
        Route::post('edit/{section}' , 'OffersControlController@EditPost');
        Route::get('show' , 'OffersControlController@show')->name('offers_control.index');
        Route::get('details/{section}' , 'OffersControlController@details')->name('offers_control.details');
        Route::get('delete/{section}' , 'OffersControlController@delete')->name('offers_control.delete');
    });

    // Home Properties Route
    Route::group([
        "prefix" => "properties"
    ], function($router){
        Route::get('show' , 'OptionsController@show')->name('options.index');
        Route::get('add' , 'OptionsController@AddGet')->name('options.add');
        Route::post('add' , 'OptionsController@AddPost');
        Route::get('edit/{option}' , 'OptionsController@EditGet')->name('options.edit');
        Route::post('edit/{option}' , 'OptionsController@EditPost');
        Route::get('delete/{option}' , 'OptionsController@delete')->name('options.delete');
    });

    // Categories Route
    Route::group([
        "prefix" => "categories"
    ], function ($router) {
        Route::get('add', 'CategoryController@AddGet')->name('shop.categories.create');
        Route::post('add', 'CategoryController@AddPost')->name('shop.categories.store');
        Route::get('show', 'CategoryController@show')->name('shop.categories.show');
        Route::get('edit/{id}', 'CategoryController@EditGet')->name('shop.categories.edit');
        Route::post('edit/{id}', 'CategoryController@EditPost')->name('shop.categories.update');
        Route::get('delete/{id}', 'CategoryController@delete')->name('shop.categories.delete');
    });

});
