<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;


//login
Router::post('/api/login', "App\Controller\LoginController@login");

Router::addGroup('/api/v1', function () {
    //logout
    Router::post('/logout', "App\Controller\LoginController@logout");
});




