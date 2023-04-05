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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'file'], function () use ($router) {
        $router->get('', ['uses' => 'FileController@showAllFiles']);
        $router->get('{id}', ['uses' => 'FileController@showOneFile']);
        $router->post('', ['uses' => 'FileController@create']);
        $router->delete('/{id}', ['uses' => 'FileController@delete']);
        $router->put('/{id}', ['uses' => 'FileController@update']);
    });
    $router->group(['prefix' => 'post'], function () use ($router) {
        $router->get('', ['uses' => 'PostController@showAllPosts']);
        $router->get('{id}', ['uses' => 'PostController@showOnePost']);
        $router->post('', ['uses' => 'PostController@create']);
        $router->delete('/{id}', ['uses' => 'PostController@delete']);
        $router->put('/{id}', ['uses' => 'PostController@update']);
    });
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('', ['uses' => 'UserController@showAllUsers']);
        $router->get('{id}', ['uses' => 'UserController@showOneUser']);
        $router->post('', ['uses' => 'UserController@create']);
        $router->delete('/{id}', ['uses' => 'UserController@delete']);
        $router->put('/{id}', ['uses' => 'UserController@update']);
    });
});
