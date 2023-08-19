<?php

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

$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');

$router->group(['prefix' => 'products', 'middleware' => 'auth'], function () use ($router) {
    $router->put('/{id}', 'ProductController@update');
    $router->get('/get-list', 'ProductController@getList');
    $router->post('/', 'ProductController@store');
    $router->get('/{id}', 'ProductController@show');
    $router->delete('/{id}', 'ProductController@destroy');
});
