<?php

/**
 *--------------------------------------------------------------------------
 * Register The Auto Loader
 *--------------------------------------------------------------------------
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 *--------------------------------------------------------------------------
 * Add environment variables
 *--------------------------------------------------------------------------
 */
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

/**
 *--------------------------------------------------------------------------
 * Set up app level items.
 *--------------------------------------------------------------------------
 */
require_once dirname(__DIR__) . '/bootstrap/app.php';

/**
 *--------------------------------------------------------------------------
 * Define routes
 *--------------------------------------------------------------------------
 */
$response = require dirname(__DIR__) . '/routes/routes.php';
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);