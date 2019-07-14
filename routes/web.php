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

/** CORS options route */
/** @var \Laravel\Lumen\Routing\Router $router */
$router->options('/{any:.*}', ['middleware' => ['cors'], function () {
    return response('OK', \Illuminate\Http\Response::HTTP_OK);
}]);

/** Routes that doesn't require auth */
$router->group(['middleware' => 'cors'], function () use ($router) {
    /** Information about this API */
    $router->get('/', ['uses' => 'ApiController@version']);

    /** User routes */
    $router->post('/login', ['uses' => 'UserController@login']);
    $router->post('/forgot-password', ['uses' => 'UserController@forgotPassword']);
    $router->post('/change-password', ['uses' => 'UserController@changePassword']);
});

/** Routes with auth */
$router->group(['middleware' => ['cors', 'auth']], function () use ($router) {
    /** User routes */
    $router->post('/logout', ['uses' => 'UserController@logout']);
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->get('', ['uses' => 'UserController@getUser']);
    });

    /** Category routes */
    $router->get('/categories', ['uses' => 'CategoryController@getAll']);
    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->post('/', ['uses' => 'CategoryController@create']);
        $router->get('/{id}', ['uses' => 'CategoryController@get']);
        $router->patch('/{id}', ['uses' => 'CategoryController@update']);
        $router->delete('/{id}', ['uses' => 'CategoryController@delete']);
    });

    /** Product routes */
    $router->get('/products', ['uses' => 'ProductController@getAll']);
    $router->group(['prefix' => 'product'], function () use ($router) {
        $router->post('/', ['uses' => 'ProductController@create']);
        $router->get('/{id}', ['uses' => 'ProductController@get']);
        $router->patch('/{id}', ['uses' => 'ProductController@update']);
        $router->delete('/{id}', ['uses' => 'ProductController@delete']);
    });


    $router->get('/users', ['uses' => 'UserController@getAll']);
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('/', ['uses' => 'UserController@create', 'middleware' => 'admin']);
        $router->get('/{id}', ['uses' => 'UserController@get']);
        $router->patch('/{id}', ['uses' => 'UserController@update']);
    });

});

