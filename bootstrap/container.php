<?php declare(strict_types=1); // config/container.php

$container = new \League\Container\Container();


# services
# add alias for Router class, 
$container->add(\Careminate\Routing\RouterInterface::class, \Careminate\Routing\Router::class);

$container->add(Careminate\Http\Kernel::class)
          ->addArgument(Careminate\Routing\RouterInterface::class);

dd($container);
return $container;