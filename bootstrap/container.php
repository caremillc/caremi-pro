<?php declare(strict_types=1);

// Load environment variables
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

/**
 * --------------------------------------------------------------------------
 * Service Container Configuration
 * --------------------------------------------------------------------------
 *
 * This file configures the service container for the Careminate framework.
 * It sets up bindings and dependencies using the League\Container.
 *
 * Services registered:
 * - Binds the RouterInterface to the Router concrete implementation.
 * - Registers the HTTP Kernel with the RouterInterface dependency.
 *
 * @package Careminate\Framework
 */

$container = new \League\Container\Container();

// Enable auto-resolution of dependencies through reflection
$container->delegate(new \League\Container\ReflectionContainer(true));

# twig template path
$templatesPath = BASE_PATH . '/templates';

#env parameters
$appEnv = env('APP_ENV', 'production'); // Default to 'production' if not set
$appKey = env('APP_KEY'); // Default to 'production' if not set
$appVersion = env('APP_VERSION');

$container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));
$container->add('APP_KEY', new \League\Container\Argument\Literal\StringArgument($appKey));
$container->add('APP_VERSION', new \League\Container\Argument\Literal\StringArgument($appVersion));

# start database connection
$dbConfig = require BASE_PATH . '/config/database.php';
$defaultDriver = $dbConfig['default'];
$driverConfig = $dbConfig['drivers'][$defaultDriver];

$container->add(Careminate\Databases\Dbal\Connections\Contracts\ConnectionInterface::class, Careminate\Databases\Dbal\Connections\ConnectionFactory::class)
    ->addArgument($driverConfig);
# Optional – Register DB Connection globally in container
$container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container) {
    return $container->get(Careminate\Databases\Dbal\Connections\Contracts\ConnectionInterface::class)->create();
});

# end database connection

// Bind RouterInterface to Router implementation
$container->add(\Careminate\Routing\RouterInterface::class, \Careminate\Routing\Router::class);

// Register the HTTP Kernel with its dependencies
// $container->add(Careminate\Http\Kernel::class)
//           ->addArgument(Careminate\Routing\RouterInterface::class);

$container->add(\Careminate\Http\Kernel::class)
          ->addArgument(\Careminate\Routing\RouterInterface::class)
          ->addArgument($container);

#parameters
// Load application routes from an external configuration file.
$routes = include BASE_PATH . '/routes/web.php';

// Extend RouterInterface definition to inject routes
$container->extend(Careminate\Routing\RouterInterface::class)
          ->addMethodCall('setRoutes',[new League\Container\Argument\Literal\ArrayArgument($routes)]);
       
# Start Twig Environment

// Register a factory that will be responsible for creating the Twig environment instance.
// The factory is passed the SessionInterface (for making session data available in views)
// and the templates path as a string literal.
$container->add('template-renderer-factory', \Careminate\Template\TwigFactory::class)
    ->addArguments([
        \Careminate\Sessions\SessionInterface::class,                      // Inject session service
        new \League\Container\Argument\Literal\StringArgument($templatesPath) // Path to view templates
    ]);

// Add a shared Twig environment instance to the container.
// This uses the factory registered above to build and return the Twig instance.
// The closure ensures the environment is created only once and reused (singleton).
$container->addShared('twig', function () use ($container) {
    return $container->get('template-renderer-factory')->create();
});


// Register the AbstractController so it can be resolved by the container.
$container->add(\Careminate\Http\Controllers\AbstractController::class);

// Automatically call the setContainer() method on any class that extends AbstractController
// This injects the container itself into the controller, enabling dependency resolution within controllers.
$container->inflector(\Careminate\Http\Controllers\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

// Add the Session service to the container as a shared (singleton) instance.
// Binds the SessionInterface to the concrete Session implementation,
// ensuring that all dependencies requiring SessionInterface will receive the same shared instance.
$container->addShared(
    Careminate\Sessions\SessionInterface::class,
    Careminate\Sessions\Session::class
);

// Debug output (should be removed in production)
// dd($container);

return $container;
