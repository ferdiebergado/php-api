<?php
namespace App\Providers;

use League\Container\ServiceProvider \{
    AbstractServiceProvider, BootableServiceProviderInterface
};
use Http\Factory\Diactoros\ResponseFactory;
use League\Route\Strategy\JsonStrategy;
// use League\Route\Router;
use Zend\Diactoros\Response\SapiEmitter;

class RouteServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * The provided array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        ResponseFactory::class,
        JsonStrategy::class,
        \League\Route\Router::class,
        SapiEmitter::class
    ];

    /**
     * In much the same way, this method has access to the container
     * itself and can interact with it however you wish, the difference
     * is that the boot method is invoked as soon as you register
     * the service provider with the container meaning that everything
     * in this method is eagerly loaded.
     *
     * If you wish to apply inflectors or register further service providers
     * from this one, it must be from a bootable service provider like
     * this one, otherwise they will be ignored.
     */
    public function boot()
    {
    }

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.z
     */
    public function register()
    {
        $this->getContainer()
            ->add(ResponseFactory::class);
            // ->addTag('responsefactory');
        $this->getContainer()
            ->add(JsonStrategy::class)
            ->addArgument(ResponseFactory::class);
            // ->addTag('jsonstrategy');
        $this->getContainer()
            ->add(\League\Route\Router::class)
            ->addMethodCall('setStrategy', [JsonStrategy::class]);
            // ->addTag('router');
        $this->getContainer()
            ->add(SapiEmitter::class);
        // ->addTag('emitter');
    }
}
