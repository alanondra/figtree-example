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

use Psr\Log\LoggerInterface;
use Psr\Http\Message\{
	ResponseFactoryInterface,
	ServerRequestInterface,
	StreamFactoryInterface,
};
use FigTree\Framework\Exceptions\Contracts\{
	ExceptionHandlerInterface,
	ExceptionResponseAdapterInterface,
};
use FigTree\Framework\Exceptions\{
	Adapters\ExceptionResponseAdapter,
	Handlers\ExceptionHandler,
	Strategies\DefaultStrategy,
};
use App\Exceptions\Strategies\{
	JsonStrategy,
	TwigStrategy,
};

return [
	'exception.strategies' => [
		autowire(JsonStrategy::class)
			->method('setServerRequest', get(ServerRequestInterface::class)),

		autowire(TwigStrategy::class)
			->method('setServerRequest', get(ServerRequestInterface::class)),

		autowire(DefaultStrategy::class)
			->method('setStreamFactory', get(StreamFactoryInterface::class))
			->method('setResponseFactory', get(ResponseFactoryInterface::class)),
	],

	ExceptionResponseAdapterInterface::class => create(ExceptionResponseAdapter::class)
		->constructor(get('exception.strategies')),

	ExceptionHandlerInterface::class => autowire(ExceptionHandler::class)
		->method('setLogger', get(LoggerInterface::class)),
];
