<?php declare(strict_types=1);

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

// Bind RouterInterface to Router implementation
$container->add(\Careminate\Routing\RouterInterface::class, \Careminate\Routing\Router::class);

// Register the HTTP Kernel with its dependencies
$container->add(Careminate\Http\Kernel::class)
          ->addArgument(Careminate\Routing\RouterInterface::class);

dd($container);

return $container;
