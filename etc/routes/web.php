<?php

use League\Route\Router;
use League\Route\RouteGroup;
use App\Web\Controllers;
use FigTree\Framework\Performance\Middleware\PreloadsResources;

/**
 * @var \League\Route\Router $router
 */

$router->middlewares([
	(new PreloadsResources())
		->withResource('/assets/build/css/app.css', 'style')
		->withResource('/assets/build/js/vendor.js', 'script')
		->withResource('/assets/build/js/app.js', 'script'),
]);

$router->get('/', [Controllers\IndexController::class, 'index'])->setName('home');
$router->get('/about', [Controllers\AboutController::class, 'index'])->setName('about');
$router->get('/privacy', [Controllers\PrivacyController::class, 'index'])->setName('privacy');
