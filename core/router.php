<?php

/*** Application Router ***/

$router = $container->get('router');
$routes = (require_once CONFIG_PATH . 'routes.php');
foreach ($routes as $route) {
    $router->map($route[0], $route[1], $route[2] . '::' . $route[3]);
}

$response = $router->dispatch($container->get('request'));

// send the response to the browser
$container->get('emitter')->emit($response);
