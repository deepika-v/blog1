<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Auth\AuthController@index');
Route::post ( 'register', 'Auth\AuthController@register' );
Route::post('login','Auth\AuthController@login')->name('login');


Route::group(array('middleware' => ['oauth']), function()
{
//Route::get ( '/logout', 'MainController@logout' );
Route::get('manage-item-ajax', 'Items\ItemsController@manageItemAjax')->name('item.view');
Route::resource('item-ajax', 'Items\ItemsController');
});

