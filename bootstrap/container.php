<?php declare(strict_types=1); // config/container.php
// Load environment variables
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

$container = new \League\Container\Container();

// Enable auto-resolution of dependencies through reflection
$container->delegate(new \League\Container\ReflectionContainer(true));

#env parameters
$appEnv     = env('APP_ENV', 'production'); // Default to 'production' if not set
$appKey     = env('APP_KEY');               // Default to 'production' if not set
$appVersion = env('APP_VERSION');

#parameters
// Load application routes from an external configuration file.
$routes = include BASE_PATH . '/routes/web.php';
# twig template path
$templatesPath = BASE_PATH . '/templates';
# sqlite connection
$databaseUrl = 'sqlite:///' . BASE_PATH . '/storage/database.sqlite';

$container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));
$container->add('APP_KEY', new \League\Container\Argument\Literal\StringArgument($appKey));
$container->add('APP_VERSION', new \League\Container\Argument\Literal\StringArgument($appVersion));

# database connection
$container->add(Careminate\Databases\Dbal\ConnectionFactory::class)
    ->addArguments([
        new \League\Container\Argument\Literal\StringArgument($databaseUrl) 
    ]);

$container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container): \Doctrine\DBAL\Connection {
    return $container->get(Careminate\Databases\Dbal\ConnectionFactory::class)->create();
});

# services
# add alias for Router class,
$container->add(\Careminate\Routing\RouterInterface::class, \Careminate\Routing\Router::class);

// Extend RouterInterface definition to inject routes
$container->extend(Careminate\Routing\RouterInterface::class)
    ->addMethodCall('setRoutes', [new League\Container\Argument\Literal\ArrayArgument($routes)]);

// Register the Kernel service and inject its dependency on RouterInterface.
$container->add(
    \Careminate\Http\Middlewares\Contracts\RequestHandlerInterface::class,
    \Careminate\Http\Middlewares\RequestHandler::class
);

$container->add(Careminate\Http\Kernel::class)
    ->addArguments([
        Careminate\Routing\RouterInterface::class,
        $container,
        \Careminate\Http\Middlewares\Contracts\RequestHandlerInterface::class
    ]);

# twig Environment
//add session to twig template 
$container->add('template-renderer-factory', \Careminate\Template\TwigFactory::class)
    ->addArguments([
        \Careminate\Sessions\SessionInterface::class,
        new \League\Container\Argument\Literal\StringArgument($templatesPath)
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('template-renderer-factory')->create();
});

$container->add(\Careminate\Http\Controllers\AbstractController::class);

$container->inflector(\Careminate\Http\Controllers\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

    //add session to container 
$container->addShared(
    Careminate\Sessions\SessionInterface::class,
    Careminate\Sessions\Session::class
);

// dd($container);

return $container;