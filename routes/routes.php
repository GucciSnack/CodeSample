<?php
declare(strict_types=1);

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$router = new League\Route\Router;

#region Routes

$router->get('/', [\App\Controllers\FileProcessor::class, 'showUploadForm']);
$router->get('/process-form', [\App\Controllers\FileProcessor::class, 'showUploadForm']);
$router->post('/process-form', [\App\Controllers\FileProcessor::class, 'processForm']);

#endregion

return $router->dispatch($request);