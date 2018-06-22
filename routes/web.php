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


Route::group(['prefix' => 'admin'], function () {
	Voyager::routes();
	Route::post('',  ['uses' => 'DashboardController@lalalal', 'as' => 'dashboard']);

	Route::post('/stock-code-item/',  ['uses' => 'StockCodeItemController@browse', 'as' => 'stock-code-item.browse']);
	Route::get('/stock-code-item/{id}/edit',  ['uses' => 'StockCodeItemController@edit', 'as' => 'stock-code-item.edit']);
	Route::put('/stock-code-item/{id}/update',  ['uses' => 'StockCodeItemController@update', 'as' => 'stock-code-item.update']);
	Route::get('/stock-code-item/find',  ['uses' => 'StockCodeItemController@find', 'as' => 'stock-code-item.find']);
	Route::get('/stock-code-item/update-data',  ['uses' => 'StockCodeItemController@updateDataFormView', 'as' => 'stock-code-item.update-data']);
	Route::post('/stock-code-item/update-data',  ['uses' => 'StockCodeItemController@updateData', 'as' => 'stock-code-item.update-data']);
	Route::get('/stock-code-item/{id}/find',  ['uses' => 'StockCodeItemController@findById', 'as' => 'stock-code-item.find.id']);

	Route::get('/order',  ['uses' => 'OrderController@index', 'as' => 'orders.index']);
	Route::get('/order/all',  ['uses' => 'OrderController@all', 'as' => 'orders.all']);
	Route::get('/order/{id}',  ['uses' => 'OrderController@show', 'as' => 'orders.show']);
	Route::get('/order/{id}/review',  ['uses' => 'OrderController@review', 'as' => 'orders.review']);

	Route::get('/order-stocks/project/{id}',  ['uses' => 'OrderStockController@getByProject', 'as' => 'order-stocks.project.index']);
	Route::put('/order-stock/{id}',  ['uses' => 'OrderStockController@update', 'as' => 'order-stocks.update']);
	Route::delete('/order-stock/{id}',  ['uses' => 'OrderStockController@destroy', 'as' => 'order-stocks.delete']);

	Route::get('/in-stocks/project/{id}',  ['uses' => 'InStockController@getByProject', 'as' => 'in-stocks.project.index']);
	Route::put('/in-stock/{id}',  ['uses' => 'InStockController@update', 'as' => 'in-stocks.update']);
	Route::delete('/in-stock/{id}',  ['uses' => 'InStockController@destroy', 'as' => 'in-stocks.delete']);
	Route::get('/in-stock/find',  ['uses' => 'InStockController@find', 'as' => 'in-stocks.find']);

	Route::get('/out-stocks/project/{id}',  ['uses' => 'OutStockController@getByProject', 'as' => 'out-stocks.project.index']);
	Route::put('/out-stock/{id}',  ['uses' => 'OutStockController@update', 'as' => 'out-stocks.update']);
	Route::delete('/out-stock/{id}',  ['uses' => 'OutStockController@destroy', 'as' => 'out-stocks.delete']);

	Route::get('/stocks/all',  ['uses' => 'StockController@all', 'as' => 'stocks.all']);

	Route::post('/adjust-stock',  ['uses' => 'AdjustStockController@store', 'as' => 'adjust-stocks.store']);
});
