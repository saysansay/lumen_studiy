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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // API Register dan Login
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
    $router->post('uploadprofile', 'AuthController@uploadProfile');
    // API USER
    $router->get('profile', 'UserController@profile');
    $router->get('users/{id}', 'UserController@singleUser');
    $router->get('users', 'UserController@allUsers');
    $router->get('findbyemail', 'UserController@emailUser');
    $router->get('profile/{userid}','UserController@getProfile');
    $app->get('/profile/{name}', 'AuthController@get_image');
    $app->get('getstorage', function ()    {
         return   "uprit";
    });
    
});