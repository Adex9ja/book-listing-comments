<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'books'], function () use ($router) {
    $router->get('/', 'BookController@getBooks');
    $router->get('/{id}','BookController@getBookById');
});

$router->group(['prefix' => 'characters'], function () use ($router) {
    $router->get('/', 'CharacterController@getCharacters');
    $router->get('/{id}','CharacterController@getCharacterById');
});

$router->group(['prefix' => 'comments'], function () use ($router) {
    $router->get('/', 'CommentController@getComments');
    $router->post('/', 'CommentController@addComment');
    $router->get('/{book_id}','CommentController@getCommentsByBookId');
});
