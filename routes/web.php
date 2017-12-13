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

//首页
Route::get('/', 'HomeController@index')->name('/');
//上传
Route::any('home/upload_image', 'HomeController@upload_image');
//登录
Route::any('users/login','UsersController@login')->name('users/login');
Route::any('users/logout','UsersController@logout')->name('users/logout');

//app管理
Route::group(['prefix'=>'app'],function(){
    Route::any('ad', 'App\AdController@index')->name('app/ad');
    Route::any('ad/ad_slot', 'App\AdController@adSlot')->name('app/ad/ad_slot');

    Route::any('ad_cate', 'App\AdCateController@index')->name('app/ad_cate');
    Route::any('ad_cate/down_cate', 'App\AdCateController@downCate')->name('app/ad_cate/down_cate');


    Route::resource('ad', 'App\AdController');
    Route::resource('ad_cate', 'App\AdCateController');
    Route::resource('image', 'App\ImageController');
    Route::resource('seckill', 'App\SeckillController');
    Route::resource('news', 'App\NewsController');
});


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
    Route::any('column/edit/{id?}/{class_name?}','Category\ColumnController@edit');
    Route::any('column/create/{pid}/{class_name?}','Category\ColumnController@create')->name('column/create');
    Route::any('column/store','Category\ColumnController@store')->name('column/store');
    Route::any('column/update','Category\ColumnController@update')->name('column/update');
    Route::any('column/show/{class_id}','Category\ColumnController@show')->name('column/show');
    Route::any('column/destroy/{class_id}','Category\ColumnController@destroy')->name('column/destroy');

    Route::any('menu','Category\MenuController@index')->name('column/menu/index');
    Route::any('tree_menu','Category\MenuController@tree');
    Route::any('menu/updateStatus','Category\MenuController@updateStatus');
    Route::any('menu/create/{pid?}','Category\MenuController@create')->name('column/menu/create');
    Route::any('menu/store','Category\MenuController@store')->name('column/menu/store');
    Route::any('menu/edit','Category\MenuController@edit');
    Route::any('menu/destroy/{id}','Category\MenuController@destroy');

});

//订单中心
Route::group(['prefix'=>'order'],function(){

    Route::any('order', 'Order\OrderController@index')->name('order/order');
    Route::any('order/store', 'Order\OrderController@store');
    Route::any('order/exportOrderData', 'Order\OrderController@exportOrderData');
    Route::any('order/ship/{id}', 'Order\OrderController@ship');
    Route::any('order/{id}', 'Order\OrderController@show');


    Route::any('order/after_sale/{id}', 'Order\OrderController@afterSale');


//    Route::resource('order', 'Order\OrderController');
    Route::any('after_sale', 'Order\AfterSaleController@index');
    Route::any('ship', 'Order\ShipController@index');
});

//商品管理
Route::group(['prefix'=>'product'],function(){
    Route::any('add/step1', 'Product\AddController@step1')->name('product/add/step1');
    Route::any('add/step2', 'Product\AddController@step2')->name('product/add/step2');
    Route::any('add/step3', 'Product\AddController@step3')->name('product/add/step3');
    Route::any('add/step4', 'Product\AddController@step4')->name('product/add/step4');
    Route::any('add/add_prop', 'Product\AddController@addProp');
    Route::any('add/add_prop_value', 'Product\AddController@addPropValue');
    Route::any('add/product_class', 'Product\AddController@product_class');
    Route::any('add/store', 'Product\AddController@store');
    Route::any('add/save', 'Product\AddController@save')->name('product/add/save');
    Route::any('add/paytype', 'Product\AddController@paytype')->name('product/add/paytype');
    Route::resource('add', 'Product\AddController');

    Route::any('insale', 'Product\InsaleController@index');
    Route::any('insale/updateStatus/{id}/{status}', 'Product\InsaleController@updateStatus');
    Route::any('insale/edit/{id}', 'Product\InsaleController@edit');
    Route::any('insale/step1/{id}', 'Product\InsaleController@step1');
    Route::any('insale/step2', 'Product\InsaleController@step2');
    Route::any('insale/step3', 'Product\InsaleController@step3');
    Route::any('insale/step4', 'Product\InsaleController@step4');
    Route::any('insale/store', 'Product\InsaleController@store');
    Route::any('insale/save', 'Product\InsaleController@save')->name('product/insale/save');
    Route::any('insale/show/{id}', 'Product\InsaleController@show');
    Route::any('insale/product_down', 'Product\InsaleController@productDown');

    
    Route::any('forsale', 'Product\ForsaleController@index');
    Route::any('examine', 'Product\ExamineController@index');
    Route::any('image', 'Product\ImageController@index');
    Route::any('review', 'Product\ReviewController@index');
    Route::any('attr', 'Product\AttrController@index');
    Route::any('batch_set', 'Product\BatchSetController@index');
    Route::any('callback', 'Product\CallBackController@index');

    Route::any('cate', function (){
        return redirect('category/column');
    });
});

//共享店铺
Route::group(['prefix'=>'store'],function(){
    Route::any('review', 'Store\ReviewController@index');
    Route::any('review/exportExcel', 'Store\ReviewController@exportExcel');
    Route::any('review/apply_action', 'Store\ReviewController@applyAction');

    Route::any('manage', 'Store\ManageController@index');
    Route::any('manage/edit','Store\ManageController@edit');
    Route::any('manage/enable','Store\ManageController@enable');

    Route::resource('query', 'Store\QueryController');
});

//供应商
Route::group(['prefix'=>'supplier'],function(){

    Route::any('add', 'Supplier\AddController@index')->name('supplier/add');
    Route::any('add/getCity', 'Supplier\AddController@getCity');
    Route::any('add/store', 'Supplier\AddController@store')->name('supplier/add/store');

    Route::any('manage', 'Supplier\ManageController@index')->name('supplier/manage');
    Route::get('manage/updateStatus/{id}/{status}','Supplier\ManageController@updateStatus');

    Route::resource('review', 'Supplier\ReviewController');



    Route::resource('callback', 'Supplier\CallBackController');

});

//线下商家
Route::group(['prefix'=>'business'],function(){



    Route::any('review', 'Business\ReviewController@index');
    Route::any('review/apply_action', 'Business\ReviewController@apply_action');
    Route::any('review/apply_save', 'Business\ReviewController@apply_save');
    Route::any('review/apply_edit', 'Business\ReviewController@apply_edit');


    Route::any('manage', 'Business\ManageController@index');
    Route::any('manage/edit', 'Business\ManageController@edit');
    Route::any('manage/save', 'Business\ManageController@upSave');
    Route::any('manage/updown', 'Business\ManageController@updown');
    Route::any('manage/order_list', 'Business\ManageController@orderList');
    Route::any('manage/comment_list', 'Business\ManageController@commentList');
    Route::any('manage/get_list', 'Business\ManageController@getList');
    Route::any('manage/download', 'Business\ManageController@download');
    Route::any('manage/vshops_worker', 'Business\ManageController@worker');


//员工管理
   /* Route::group(['prefix' => 'vshops_worker'], function(){
        $controller = 'VshopsWorkerController@';
        Route::any('/',$controller.'index');
        Route::any('info',$controller.'info');
        Route::any('save',$controller.'save');
        Route::any('del',$controller.'del');
    });*/

    Route::resource('balance', 'Business\BalanceController');
    Route::resource('other', 'Business\OtherController');
});

//结算管理
Route::group(['prefix'=>'balance'],function(){

    #子订单详情
    Route::get('pending','Balance\PendingController@index');
    Route::get('pending/order_detail','Balance\PendingController@orderDetail');
    #标记为结算中
    Route::any('pending/mark_in_settle','Balance\PendingController@markInSettle');


    #T+1预结算
    Route::any('t1','Balance\T1Controller@index');
    Route::any('t1/t_plus1_tmp','Balance\T1Controller@tPlus1Tmp');
    #T+7预结算
    Route::any('t7','Balance\T7Controller@index');
    Route::any('t7/t_plus7_tmp','Balance\T7Controller@tPlus7Tmp');


    #T+1复审
    Route::any('review','Balance\ReviewController@tPlus1Review');
    #T+7复审
    Route::any('review/t_plus7_review','Balance\ReviewController@tPlus7Review');
    #T+1复审详情
    Route::any('review/t_plus1_review_detail','Balance\ReviewController@tPlus1ReviewDetail');
    #T+7复审详情
    Route::any('review/t_plus7_review_detail','Balance\ReviewController@tPlus7ReviewDetail');


    #供应商订单详情
    Route::get('t7/order_detail_s','Balance\T7Controller@orderDetailS');
    #导出对账单
   /* Route::get('export_file','exportFile');
    Route::any('import_file','importFile');*/



    #结算中
    Route::any('processing','Balance\ProcessingController@inSettlementList');
    #已结算
    Route::get('done','Balance\DoneController@settledList');



});

//文章管理
Route::group(['prefix'=>'article'],function() {
    $controller = 'Article\ArticleController@';
    #文章列表
    Route::get('article_lists',$controller.'articleLists');
    #编辑或新增文章
    Route::any('article_lists/edit_article/{id?}',$controller.'editArticle');
});


//房源管理
Route::group(['prefix'=>'house'],function() {
    $controller = 'House\HouseController@';
    #房源列表
    Route::get('houseLister',$controller.'houseLister');
    #房源添加表单
    Route::get('houseAdd',$controller.'houseAdd');
    #房源添加表单提交
    Route::post('houseAdd/save',$controller.'save');
    #房源更新列表
    Route::get('updateList',$controller.'updateList');
    #房源更新详细列举
    Route::get('updateList/detail/{id}',$controller.'detail');
    #Ajax请求删除图片
    Route::any('updateList/delete/{id}',$controller.'deleteImg');
});























