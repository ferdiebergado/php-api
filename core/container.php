<?php declare (strict_types = 1);

use League\Container\Definition\Definition;
// use Http\Factory\Diactoros\ResponseFactory;
// use League\Route\Strategy\JsonStrategy;
// use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
// use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use ParagonIE\EasyDB\EasyDB;
use App\Controllers\Controller;
use App\Models\Model;
use App\Providers\AppServiceProvider;
use League\Container\Container;

$db = require(CONFIG_PATH . 'database.php');
$dsn = "mysql:host=" . $db['host'] . ";port=" . $db['port'] . ";dbname=" . $db['dbname'] . ";charset=" . $db['charset'];

$definitions = [
    (new Definition(ResponseInterface::class))->addTag('response'),
    // (new Definition(ResponseFactory::class))->addTag('responsefactory'),
    // (new Definition(JsonStrategy::class))
    //     ->addArgument(ResponseFactory::class)
    //     ->addTag('strategy'),
    // (new Definition(Router::class))
    //     ->addMethodCall('setStrategy', [JsonStrategy::class])
    //     ->addTag('router'),
    // (new Definition(SapiEmitter::class))
    //     ->addTag('emitter'),
    (new Definition(PDO::class))
        ->addArgument($dsn)
        ->addArgument($db['username'])
        ->addArgument($db['password'])
        ->addArgument($db['options'])
        ->addTag('pdo'),
    (new Definition(EasyDB::class))
        ->addArgument(PDO::class)
        ->setShared()
        ->addTag('db'),
    (new Definition(Controller::class))
        ->addArgument(Model::class)
        ->addTag('controller'),
    (new Definition(Model::class))
        ->addArgument(EasyDB::class)
        ->addTag('model')
];

// $aggregate = new League\Container\Definition\DefinitionAggregate($definitions);
// $container = new League\Container\Container($aggregate);
$container = new Container;
$container->addServiceProvider(new AppServiceProvider);

$delegate = new DelegateContainer;
return $container;
