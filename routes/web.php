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

Route::get('/', function () {
    return view('welcome');
});
//首頁
//Route::get('/', 'HomeController@indexPage' );
//使用者
Route::get('/user/auth/sign-up', 'UserAuthController@signUpPage' );
Route::post('/user/auth/sign-up', 'UserAuthController@signUpProcess' );
Route::get('/user/auth/sign-in', 'UserAuthController@signInPage' );
Route::post('/user/auth/sign-in', 'UserAuthController@signInProcess' );
Route::get('/user/auth/sign-out', 'UserAuthController@signOut' );

//商品
//商品清單檢視
Route::get('/merchandise', 'MerchandiseController@merchandiseListPage' );
//新增商品
Route::get('/merchandise/create', 'MerchandiseController@merchandiseCreateProcess' )->middleware(['user.auth.admin']);
//商品管理檢視
Route::get('/merchandise/manage', 'MerchandiseController@merchandiseManageListPage' )->middleware(['user.auth.admin']);
//商品單品檢視
Route::get('/merchandise/{merchandise_id}', 'MerchandiseController@merchandiseItemPage' );
//商品單品編輯頁面
Route::get('/merchandise/{merchandise_id}/edit', 'MerchandiseController@merchandiseItemEditPage' )->middleware(['user.auth.admin']);
//商品單品資料修改
Route::put('/merchandise/{merchandise_id}', 'MerchandiseController@merchandiseItemUpdateProcess' )->middleware(['user.auth.admin']);
//商品購買
Route::post('/merchandise/{merchandise_id}/buy', 'MerchandiseController@merchandiseItemBuyProcess' )->middleware(['user.auth']);

//交易
Route::get('/transaction', 'transactionController@transactionListPage' )->middleware(['user.auth']);