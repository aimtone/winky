<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function(){
    //Route::get('getPlans', 'PlanController@index');

    Route::post('/user/register', 'UserController@register');
    Route::post('/user/login', 'UserController@login')->middleware('localization');
    Route::get('/user/verificate/{email_verification_key}', 'UserController@verificate');
    Route::put('/user/operator/update/{email_verification_key}', 'UserController@updateOperator');
    Route::get('/website/{uuid}', 'WebsiteController@getWebsitePublicClientInfo');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('getUser', 'UserController@getUser');
        Route::get('logout', 'UserController@logout');
        
        //Route::resource('plan', 'PlanController');
        //Route::resource('planitem', 'PlanitemController');
        Route::resource('plan_planitem', 'Plan_PlanitemController');

        Route::post('/website/create', 'WebsiteController@create');
    });

});