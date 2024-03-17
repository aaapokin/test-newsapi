<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

$router->group(['namespace' => 'Api', 'prefix' => 'api', 'as' => 'api'], function () use ($router) {
    $router->get('/news', ['as' => 'news', 'uses' => 'NewsController@list']);

});
