<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
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

Route::group(['prefix' => 'v1'], function () {

    //general unauthenticated customers routes here

    Route::group(['prefix' => 'customers'], function () {

        //unauthenticated routes for customers here
        Route::post('login', [CustomerController::class, 'login']);
        Route::post('register', [CustomerController::class, 'register']);


        Route::group(['middleware' => ['auth:api-customers', 'scopes:customers']], function () {
            // authenticated customers routes here

            Route::get('listMyQuestions', [CustomerController::class, 'listMyQuestions']);
            Route::post('sendQuestion', [CustomerController::class, 'sendQuestion']);


        });
    });

    //general unauthenticated employees routes here

    Route::group(['prefix' => 'employees'], function () {

        //unauthenticated routes for employees here
        Route::post('login', [EmployeeController::class, 'login']);
        Route::post('register', [EmployeeController::class, 'register']);


        Route::group(['middleware' => ['auth:api-employees', 'scopes:employees']], function () {
            // authenticated employees routes here

            Route::post('listQuestions', [EmployeeController::class, 'listQuestions']);
            Route::post('ChangeQuestionStatus', [EmployeeController::class, 'ChangeQuestionStatus']);
            Route::post('sendReply', [EmployeeController::class, 'sendReply']);

        });
    });

});


