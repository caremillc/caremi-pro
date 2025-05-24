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
       
// Register the Twig FilesystemLoader as a shared (singleton) service.
// It will use the provided $templatesPath as the base directory for template files.
$container->addShared('filesystem-loader', \Twig\Loader\FilesystemLoader::class)
    ->addArgument(new \League\Container\Argument\Literal\StringArgument($templatesPath));

// Register the Twig Environment as a shared (singleton) instance
// and inject the 'filesystem-loader' service into its constructor.
$container->addShared('twig', \Twig\Environment::class)
    ->addArgument('filesystem-loader');

// Register the AbstractController so it can be resolved by the container.
$container->add(\Careminate\Http\Controllers\AbstractController::class);

// Automatically call the setContainer() method on any class that extends AbstractController
// This injects the container itself into the controller, enabling dependency resolution within controllers.
$container->inflector(\Careminate\Http\Controllers\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

// Debug output (should be removed in production)
// dd($container);

return $container;
