<?php

use Dotenv\Dotenv;
use FigTree\Framework\Core\Application;

$dotenv = Dotenv::createImmutable($context->path());
$dotenv->load();

$debug = !!$context->env('APP_DEBUG', false);

/**
 * @var \DI\Container
 */
$container = (require 'container.php');

$app = new Application($context, $container);

$container->set(Application::class, $app);

return $app;
