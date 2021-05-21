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
use Psr\EventDispatcher\EventDispatcherInterface;
use League\Event\EventDispatcher;

$events = [
//	Event::class => [
//		Listener::class,
//	],
];

$dispatcher = autowire(EventDispatcher::class);

foreach ($events as $event => $listeners) {
	foreach ($listeners as $listener) {
		$dispatcher = $dispatcher->method('subscribeTo', $event, get($listener));
	}
}

return [
	EventDispatcherInterface::class => $dispatcher,
];
