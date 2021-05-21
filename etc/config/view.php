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
use Psr\Http\Message\{
	ResponseFactoryInterface,
	StreamFactoryInterface,
};
use FigTree\Framework\Core\Context;
use FigTree\Framework\Web\Emission\{
	Contracts\EmitterInterface,
	Strategies\DefaultEmitterStrategy,
	Emitter,
};
use Twig\Loader\{
	LoaderInterface,
	FilesystemLoader,
};
use App\Web\Responses\Adapters\{
	JsonAdapter,
	TwigAdapter,
};
use Carbon\{
	Carbon,
	CarbonTimeZone,
};
use Twig\Environment;

$globals = [
	'APP_NAME' => env('APP_NAME'),
	'APP_ENV' => env('APP_ENV'),
	'APP_DEBUG' => !!env('APP_DEBUG'),
	'NOW' => create(Carbon::class)
		->constructor(null, get(CarbonTimeZone::class)),
	
];

$twig = autowire(Environment::class)
	->constructorParameter('options', get('twig.options'));

foreach ($globals as $name => $value) {
	$twig->method('addGlobal', $name, $value);
}

return [
	JsonAdapter::class => autowire(JsonAdapter::class)
		->method('setResponseFactory', get(ResponseFactoryInterface::class))
		->method('setStreamFactory', get(StreamFactoryInterface::class)),

	TwigAdapter::class => autowire(TwigAdapter::class)
		->method('setResponseFactory', get(ResponseFactoryInterface::class))
		->method('setStreamFactory', get(StreamFactoryInterface::class)),

	'emitter.strategies' => [
		autowire(DefaultEmitterStrategy::class)
			->constructorParameter('emitBytes', 1024),
	],

	EmitterInterface::class => create(Emitter::class)
		->constructor(get('emitter.strategies')),

	'twig.options' => [
		'debug' => env('APP_DEBUG'),
	],

	LoaderInterface::class => factory(function (ContainerInterface $container) {
		$context = $container->get(Context::class);

		$paths = [
			'',
		];

		return new FilesystemLoader($paths, $context->path('resources/templates'));
	}),

	Environment::class => $twig,
];
