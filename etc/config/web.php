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
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\ServerRequest;
use Http\Factory\Guzzle;
use League\Route\{
	Strategy\StrategyInterface,
	Router,
};
use App\Web\Routing\Strategies\ApplicationStrategy;

return [
	Message\ServerRequestInterface::class => fn () => ServerRequest::fromGlobals(),

	Message\RequestFactoryInterface::class => autowire(Guzzle\RequestFactory::class),
	Message\ResponseFactoryInterface::class => autowire(Guzzle\ResponseFactory::class),
	Message\ServerRequestFactoryInterface::class => autowire(Guzzle\ServerRequestFactory::class),
	Message\StreamFactoryInterface::class => autowire(Guzzle\StreamFactory::class),
	Message\UploadedFileFactoryInterface::class => autowire(Guzzle\UploadedFileFactory::class),
	Message\UriFactoryInterface::class => autowire(Guzzle\UriFactory::class),

	StrategyInterface::class => autowire(ApplicationStrategy::class)
		->method('setContainer', get(ContainerInterface::class)),

	RequestHandlerInterface::class => autowire(Router::class)
		->method('setStrategy', get(StrategyInterface::class))
		->method('middlewares', get('routing.middlewares')),
];
