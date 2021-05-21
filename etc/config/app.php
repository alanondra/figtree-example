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

use DateTimeZone;
use Carbon\CarbonTimeZone;
use Psr\Container\ContainerInterface;
use FigTree\Framework\Core\Context;
use FigTree\Config\ConfigRepository;
use FigTree\Config\Contracts\{
	ConfigRepositoryInterface,
	ConfigFactoryInterface,
};
use FigTree\Framework\Support\Evaluator;
use App\Config\ConfigFactory;

return [
	'app.debug' => !!env('APP_DEBUG', false),

	'app.env' => !!env('APP_ENV', 'dev'),

	'app.timezone' => env('APP_TIMEZONE', 'UTC'),

	'app.locale' => 'en',

	'app.locales' => [
		'en',
		'sv',
		'no',
	],

	DateTimeZone::class => create()->constructor(get('app.timezone')),

	CarbonTimeZone::class => create()->constructor(get('app.timezone')),

	ConfigFactoryInterface::class => autowire(ConfigFactory::class)
		->method('setContext', get(Context::class)),

	ConfigRepositoryInterface::class => factory(function (ContainerInterface $container) {
		$context = $container->get(Context::class);
		$factory = $container->get(ConfigFactoryInterface::class);

		$repo = new ConfigRepository($factory);
		$repo->addDirectory($context->path('etc/config'));

		return $repo;
	}),

	Evaluator::class => autowire(Evaluator::class),
];
