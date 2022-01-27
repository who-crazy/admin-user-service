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


/** Admin */
Router::addGroup('/api/v1', function () {

    //login
    Router::post('/admin/login', "App\Controller\Admin\LoginController@login");

    Router::addGroup('/admin', function () {

        //logout
        //Router::post('/logout', "App\Controller\LoginController@logout");

        // article-category
        Router::get('/article/categorys', "App\Controller\Admin\ArticleCategoryController@index");
        Router::get('/article/categorys/{id}', "App\Controller\Admin\ArticleCategoryController@show");
        Router::post('/article/categorys', "App\Controller\Admin\ArticleCategoryController@store");
        Router::put('/article/categorys/{id}', "App\Controller\Admin\ArticleCategoryController@update");
        Router::delete('/article/categorys/{id}', "App\Controller\Admin\ArticleCategoryController@destroy");

        //form-category
        /*Router::get('/form/categorys', "App\Controller\Admin\FormCategoryController@index");
        Router::get('/form/categorys/{id}', "App\Controller\Admin\FormCategoryController@show");
        Router::post('/form/categorys', "App\Controller\Admin\FormCategoryController@store");
        Router::put('/form/categorys/{id}', "App\Controller\Admin\FormCategoryController@update");
        Router::delete('/form/categorys/{id}', "App\Controller\Admin\FormCategoryController@destroy");

        //form
        Router::get('/forms', "App\Controller\Admin\FormController@index");
        Router::get('/forms/{id}', "App\Controller\Admin\FormController@show");
        Router::post('/forms', "App\Controller\Admin\FormController@store");
        Router::put('/forms/{id}', "App\Controller\Admin\FormController@update");
        Router::delete('/forms/{id}', "App\Controller\Admin\FormController@destroy");

        //form-answer
        Router::get('/forms', "App\Controller\Admin\FormAnswerController@index");
        Router::get('/forms/{id}', "App\Controller\Admin\FormController@show");
        Router::delete('/forms/{id}', "App\Controller\Admin\FormController@destroy");*/
    }, ['middleware' => [App\Middleware\AdminAuthMiddleware::class]]);
});


/** API */
Router::addGroup('/api/v1', function () {

    //form-category
    /*Router::get('/form/categorys', "App\Controller\Api\FormCategoryController@index");

    //form
    Router::get('/forms', "App\Controller\Api\FormController@index");
    Router::get('/forms/{id}', "App\Controller\Api\FormController@show");
    //click praise
    Router::put('/forms/{id}', "App\Controller\Api\FormController@update");

    //form-answer: submit
    Router::post('/form/answer', "App\Controller\Api\FormController@store");*/
});


