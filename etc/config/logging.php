<?php

use function DI\{
	add,
	autowire,
	create,
	decorate,
	env,
	factory,
	get,
	string,
	value,
};
use Psr\Container\ContainerInterface;
use FigTree\Framework\Core\Context;
use Psr\Log\{
	LoggerInterface,
	LogLevel,
};
use Monolog\{
	Handler\StreamHandler,
	Logger,
};

return [
	'logger.name' => 'main',

	'logger.handlers' => [

		factory(function (ContainerInterface $container) {
			$context = $container->get(Context::class);

			$handler = new StreamHandler(
				$context->path('var/log/app.log'),
				LogLevel::WARNING,
				true,
				null,
				false
			);

			return $handler;
		}),

	],

	'logger.processors' => [
		//
	],

	LoggerInterface::class => create(Logger::class)
		->constructor(
			get('logger.name'),
			get('logger.handlers'),
			get('logger.processors'),
			get(DateTimeZone::class)
		),
];
