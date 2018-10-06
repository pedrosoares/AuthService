<?php

$router->group([
    'prefix' => 'auth'
], function ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
    $router->post('can', 'PermissionController@can');
});

/**
 * TODO REMOVE THE CODE BELLOW
 */
$router->get('/help', function() {
    return "help";
});

$router->post('/home/{id}', function($id) {
    return "home {".$id."}";
});
