<?php declare(strict_types=1);

/**
 * Careminate Application Bootstrap File
 * 
 * Initializes the application environment, loads dependencies, 
 * handles incoming HTTP requests, and sends responses.
 * 
 * @package Careminate
 * @version 1.0.0
 * @author Your Name
 * @license MIT
 */

use Careminate\Http\Kernel;
use Careminate\Routing\Router;
use Careminate\Http\Requests\Request;
use Careminate\EventListener\ContentLengthListener;
use Careminate\EventListener\InternalErrorListener;

// Define application constants
define('CAREMI_START', microtime(true));  // Application start time for performance tracking
define('BASE_PATH', dirname(__DIR__));    // Base directory path
define('ROOT_PATH', dirname(__FILE__));   // Root directory path
define('ROOT_DIR', dirname(__FILE__));

// Load dependencies
require dirname(__DIR__) . '/vendor/autoload.php';  // Composer autoloader
require BASE_PATH . '/bootstrap/app.php';            // Application initialization
//require BASE_PATH . '/bootstrap/performance.php';   // Performance optimizations
$container = require BASE_PATH . '/bootstrap/container.php';  // Load DI container

/**
 * Handle incoming HTTP request
 * 
 * 1. Creates request object from global variables
 * 2. Initializes router and dependency container
 * 3. Processes request through application kernel
 * 4. Sends HTTP response to client
 */
$request = Request::createFromGlobals();  // Create request from PHP globals
$router = new Router();                   // Initialize router


// Retrieve the DBAL-specific EventDispatcher from the container and register multiple listeners 
// for the ResponseEvent. These listeners handle internal error responses and manage Content-Length headers.
$eventDispatcher = $container->get(\Careminate\Databases\Dbal\EventDispatcher\EventDispatcher::class);
$eventDispatcher->addListener(
    \Careminate\Databases\Dbal\EventDispatcher\ResponseEvent::class,
    new InternalErrorListener()
)->addListener(
    \Careminate\Databases\Dbal\EventDispatcher\ResponseEvent::class,
    new ContentLengthListener()
);


// Initializes the application's kernel 
$kernel =  $container->get(Kernel::class); 

// Process request and get response
$response = $kernel->handle($request);

// Send response to client
$response->send(); 

// if terminate is active session flah message will not work
// $kernel->terminate($request, $response);