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

use App\Web\Routing\Strategies\ApplicationStrategy;
use DateTimeZone;
use Psr\Container\ContainerInterface;
use FigTree\Framework\Core\Context;
use FigTree\Framework\Support\Evaluator;
use League\Route\Router;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Server\RequestHandlerInterface;

return [
	'routing.middlewares' => [
		//
	],

	RequestHandlerInterface::class => decorate(function (Router $router, ContainerInterface $container) {
		$eval = $container->get(Evaluator::class);

		$data = [
			'router' => $router,
		];

		$eval->read('etc/routes/web.php', $data);

		return $router;
	}),
];
