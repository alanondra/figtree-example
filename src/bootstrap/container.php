<?php

use Psr\Container\ContainerInterface;
use DI\ContainerBuilder;
use FigTree\Framework\Core\Context;

$builder = new ContainerBuilder();

$builder->useAutowiring(true);
$builder->useAnnotations(true);

if (!$debug) {
	$builder->enableDefinitionCache();
}

$builder->addDefinitions([
	Context::class => $context,
]);

$definitions = [
	'app',
	'web',
	'exceptions',
	'logging',
	'events',
	'routing',
	'view',
];

foreach ($definitions as $def) {
	$builder->addDefinitions($context->path("etc/config/{$def}.php"));
}

$container = $builder->build();

$container->set(ContainerInterface::class, $container);

return $container;
