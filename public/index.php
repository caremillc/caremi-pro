<?php declare(strict_types=1); // public/index.php
define('BASE_PATH', dirname(__DIR__));
define('ROOT_DIR', dirname(__FILE__));
define('CAREMI_START', microtime(true));


// Include Composer autoload to load dependencies
require_once dirname(__DIR__) . '/vendor/autoload.php';

// bootstrapping
require BASE_PATH . '/bootstrap/app.php';
require BASE_PATH . '/bootstrap/performance.php';

// request received

// perform some logic

// send response (string of content)
echo 'Hello World';