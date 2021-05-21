<?php

namespace App\Web\Responses\Adapters;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment as Twig;

class TwigAdapter extends AbstractAdapter
{
	public function __construct(protected Twig $twig)
	{
		//
	}

	public function create(string $name, array $data = []): ResponseInterface
	{
		$body = $this->twig->render($name, $data);

		$stream = $this->streamFactory->createStream($body);

		return $this->responseFactory
			->createResponse(200)
			->withBody($stream);
	}

	public function exists(string $name): bool
	{
		return $this->twig->getLoader()->exists($name);
	}
}
