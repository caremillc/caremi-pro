<?php declare(strict_types=1); // config/container.php

$container = new \League\Container\Container();

#parameters
// Load application routes from an external configuration file.
$routes = include BASE_PATH . '/routes/web.php';

# services
# add alias for Router class, 
$container->add(\Careminate\Routing\RouterInterface::class, \Careminate\Routing\Router::class);

// Extend RouterInterface definition to inject routes
$container->extend(Careminate\Routing\RouterInterface::class)
          ->addMethodCall('setRoutes',[new League\Container\Argument\Literal\ArrayArgument($routes)]);
          
$container->add(Careminate\Http\Kernel::class)
          ->addArgument(Careminate\Routing\RouterInterface::class);

// dd($container);

return $container;