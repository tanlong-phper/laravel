<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('/');

//账号管理
Route::group(['prefix'=>'account'],function(){
    //账号
    Route::resource('user','Account\AccountController');
    Route::post('user', 'Account\AccountController@index');
    Route::post('user/store', 'Account\AccountController@store');
    Route::get('user/updateStatus/{id}/{status}','Account\AccountController@updateStatus');
    //部门
    Route::resource('department','Account\DepartmentController');
    Route::post('department/store', 'Account\DepartmentController@store');
    Route::get('department/updateStatus/{id}/{status}','Account\DepartmentController@updateStatus');
    //角色
    Route::resource('role', 'Account\RoleController');
    Route::post('role/store', 'Account\RoleController@store');
    Route::get('role/updateStatus/{id}/{status}','Account\RoleController@updateStatus');

});

//分类管理
Route::group(['prefix'=>'category'],function(){
    //栏目
    Route::any('column','Category\ColumnController@index')->name('column/index');
    Route::any('tree','Category\ColumnController@tree');
    Route::any('column/edit/{id?}','Category\ColumnController@edit');
    Route::any('column/create/{pid}','Category\ColumnController@create')->name('column/create');
    Route::any('column/store','Category\ColumnController@store')->name('column/store');
    Route::any('column/update','Category\ColumnController@update')->name('column/update');
    Route::any('column/show/{class_id}','Category\ColumnController@show')->name('column/show');

});

//订单中心
Route::group(['prefix'=>'order'],function(){
    Route::resource('order', 'Order\OrderController');
});


Route::group(['prefix'=>'product'],function(){
    Route::any('add/step1', 'Product\AddController@step1');
    Route::any('add/step2', 'Product\AddController@step2');
    Route::any('add/step3', 'Product\AddController@step3');
    Route::any('add/step4', 'Product\AddController@step4');
    Route::any('add/add_prop', 'Product\AddController@addProp');
    Route::any('add/add_prop_value', 'Product\AddController@addPropValue');
    Route::any('add/product_class', 'Product\AddController@product_class');
    Route::resource('add', 'Product\AddController');
});

Route::group(['prefix'=>'store'],function(){
    Route::resource('review', 'Store\ReviewController');
    Route::resource('manage', 'Store\ManageController');
    Route::resource('query', 'Store\QueryController');
});

Route::group(['prefix'=>'supplier'],function(){
    Route::resource('review', 'Supplier\ReviewController');
    Route::resource('manage', 'Supplier\ManageController');
    Route::resource('add', 'Supplier\AddController');
    Route::resource('callback', 'Supplier\CallBackController');
});

Route::group(['prefix'=>'app'],function(){
    Route::resource('ad', 'App\AdController');
    Route::resource('ad_cate', 'App\AdCateController');
    Route::resource('image', 'App\ImageController');
    Route::resource('seckill', 'App\SeckillController');
    Route::resource('news', 'App\NewsController');
});



Route::any('users/login','UsersController@login')->name('users/login');
Route::any('users/logout','UsersController@logout')->name('users/logout');



















