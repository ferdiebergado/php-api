<?php declare (strict_types = 1);

use League\Container\Definition\Definition;
use App\Controllers\Controller;
use App\Models\Model;
use ParagonIE\EasyDB\EasyDB;

$db = require(CONFIG_PATH . 'database.php');
$dsn = "mysql:host=" . $db['host'] . ";port=" . $db['port'] . ";dbname=" . $db['dbname'] . ";charset=" . $db['charset'];

$definitions = [
    (new Definition(Controller::class))->addArgument(Model::class),
    (new Definition(EasyDB::class))->addArgument(PDO::class),
    (new Definition(Model::class))->addArgument(EasyDB::class),
    (new Definition(PDO::class))
        ->addArgument($dsn)
        ->addArgument($db['username'])
        ->addArgument($db['password'])
        ->addArgument($db['options'])
        ->setShared()
];

$aggregate = new League\Container\Definition\DefinitionAggregate($definitions);
$container = new League\Container\Container($aggregate);

// $controller = $container->get(App\Controllers\Controller::class);

return $container;
