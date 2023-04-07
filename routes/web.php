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

// Authorization
$router->group(['prefix' => 'api'], function () use ($router) {
    // No authentification required
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', 'AuthController@register');
        $router->post('login', 'AuthController@login');
    });
    // Authentification required
    $router->group(['prefix' => 'auth', 'middleware' => 'auth'], function () use ($router) {
        $router->get('me', 'AuthController@me');
    });
});

// No authentification required
$router->group(['prefix' => 'api'], function () use ($router) {});

// Authentification required
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->group(['prefix' => 'filePost'], function () use ($router) {
        $router->get('/{id}', ['uses' => 'MappingPostFileController@showAllFilesOfPost']);
    });
    $router->group(['prefix' => 'role'], function () use ($router) {
        $router->get('', ['uses' => 'RoleController@showAllRoles']);
        $router->get('{id}', ['uses' => 'RoleController@showOneRole']);
        $router->post('', ['uses' => 'RoleController@create']);
        $router->delete('/{id}', ['uses' => 'RoleController@delete']);
        $router->put('/{id}', ['uses' => 'RoleController@update']);
    });
    $router->group(['prefix' => 'center'], function () use ($router) {
        $router->get('', ['uses' => 'CenterController@showAllCenters']);
        $router->get('{id}', ['uses' => 'CenterController@showOneCenter']);
        $router->post('', ['uses' => 'CenterController@create']);
        $router->delete('/{id}', ['uses' => 'CenterController@delete']);
        $router->put('/{id}', ['uses' => 'CenterController@update']);
    });
    $router->group(['prefix' => 'priority'], function () use ($router) {
        $router->get('', ['uses' => 'PriorityController@showAllPriorities']);
        $router->get('{id}', ['uses' => 'PriorityController@showOnePriority']);
        $router->post('', ['uses' => 'PriorityController@create']);
        $router->delete('/{id}', ['uses' => 'PriorityController@delete']);
        $router->put('/{id}', ['uses' => 'PriorityController@update']);
    });
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
