<?php

/**
 * phpnofw - A No Framework PHP Web Application Skeleton
 *
 * @package  phpnofw
 * @author   Ferdinand Saporas Bergado <ferdiebergado@gmail.com>
 * MIT License

Copyright (c) 2018 Ferdinand Saporas Bergado

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
 */

/* Declare constants */
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('APP_PATH', BASE_PATH . 'app' . DIRECTORY_SEPARATOR);
define('CORE_PATH', BASE_PATH . 'core' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', BASE_PATH . 'config' . DIRECTORY_SEPARATOR);
define('CONTROLLER_PATH', APP_PATH . 'Controllers' . DIRECTORY_SEPARATOR);
define('VIEW_PATH', APP_PATH . 'views' . DIRECTORY_SEPARATOR);
define('VENDOR_PATH', BASE_PATH . 'vendor' . DIRECTORY_SEPARATOR);
define('TMP_PATH', BASE_PATH . 'tmp' . DIRECTORY_SEPARATOR);
define('DATE_FORMAT_SHORT', 'Y-m-d h:i:s');
define('DATE_FORMAT_LONG', 'Y-m-d h:i:s A e');
define('LOG_FILE', TMP_PATH . 'app_' . date('Y') . '.log');
/* end constants */

/* Load composer libraries */
require_once VENDOR_PATH . 'autoload.php';
/* end libraries */

/* Register the error handler */
error_reporting(E_ALL);
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

$whoops = new Whoops\Run;

if (config('debug_mode')) {
    $whoops->pushHandler(new JsonResponseHandler);
} else {
    $whoops->pushHandler(function ($e) use ($whoops) {
        $whoops->allowQuit(false);
        $whoops->writeToOutput(false);
        $whoops->pushHandler(new PrettyPageHandler);
        $body = $whoops->handleException($e);
        $app = require(CONFIG_PATH . 'app.php');
        Core\Mail::send($app['author_email'], $app['name'] . ' Error Exception', $body);
        logger($e->getMessage(), 2);
        require VIEW_PATH . '500.php';
    });
}
$whoops->register();
/* end error handler */

/* Initialize the dependency injection container */
// $container = require_once(CORE_PATH . 'container.php');
$container = require_once(CORE_PATH . 'dependencies.php');
/* end container */

/* Set security headers */
set_secure_headers();
/* end headers */

/* Filter superglobals */
// sanitizeglobals();
/* end superglobals */

/* Dispatch the router */
require_once CORE_PATH . 'router.php';
/* end router */

/*** LIFT OFF!!! ***/
