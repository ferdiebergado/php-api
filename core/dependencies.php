<?php

use Http\Factory\Diactoros\ResponseFactory;
use League\Route\Strategy\JsonStrategy;
use League\Route\Router;
use ParagonIE\EasyDB\EasyDB;
use App\Models\Model;
// use League\Route\Strategy\ApplicationStrategy;

$container = new League\Container\Container();

/* Response */
$container->share('response', \Zend\Diactoros\Response::class);

/* Request */
$container->share('request', function () {
    return \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
});

/* Response Factory */
$container->share('responsefactory', ResponseFactory::class);

/* Response Strategy */
$container->share('strategy', function () use ($container) {
    return (new JsonStrategy($container->get('responsefactory')));
    // return (new ApplicationStrategy)->setContainer(($container));
});

/* Router */
$container->share('router', function () use ($container) {
    return (new Router)->setStrategy($container->get('strategy'));
});

/* Emitter */
$container->share('emitter', Zend\Diactoros\Response\SapiEmitter::class);

/* PDO */
$container->share('pdo', function () {
    $db = require(CONFIG_PATH . 'database.php');
    $dsn = "mysql:host=" . $db['host'] . ";port=" . $db['port'] . ";dbname=" . $db['dbname'] . ";charset=" . $db['charset'];
    return (new PDO($dsn, $db['username'], $db['password'], $db['options']));
});

/* DB */
$container->share('db', function () use ($container) {
    return new EasyDB($container->get('pdo'));
});

/* Model */
$container->share('model', function () use ($container) {
    return new App\Models\Model($container->get('db'));
});

/* Controller */
// $container->share('controller', function () use ($container) {
//     return new App\Controllers\Controller($container->get('model'));
// });

/* Delegate */
$container->delegate(
    (new League\Container\ReflectionContainer())->cacheResolutions()
);

return $container;
