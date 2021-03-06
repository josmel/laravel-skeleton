<?php

$router->prefix('user')->group(function ($router) {
    $router->post('login', 'UserController@postLogin');
    $router->get('lostpassword', 'UserController@getPassword');
    $router->resource('', 'UserController', ['only' => ['store']]);
});

$router->group(['middleware' => ['auth:provider']], function ($router) {
    $router->get('user/logout', 'UserController@getLogout');
    $router->post('order/{order}', 'OrderController@postComment');
    $router->resource('order', 'OrderController');
    $router->get('coupon/validate', 'CouponController@getValidate');
    $router->resource('coupon', 'CouponController');
    $router->resource('reservation', 'ReservationController');
    $router->get('client/{client}/{type}', 'ClientController@show');
    $router->resource('client', 'ClientController');
    $router->resource('reservation', 'ReservationController');
    $router->resource('user', 'UserController', ['only' => ['index']]);
});