<?php

namespace App\Exceptions\Strategies;

use Throwable;
use Psr\Http\Message\ResponseInterface;
use FigTree\Framework\Support\Str;
use FigTree\Framework\Exceptions\Contracts\ExceptionResponseStrategyInterface;
use FigTree\Framework\Web\Concerns\UsesServerRequest;
use FigTree\Framework\Web\Contracts\ServerRequestAwareInterface;
use App\Web\Responses\Adapters\JsonAdapter as JsonResponseAdapter;

class JsonStrategy implements
	ExceptionResponseStrategyInterface,
	ServerRequestAwareInterface
{
	use UsesServerRequest;

	public function __construct(protected JsonResponseAdapter $responseAdapter)
	{
		//
	}

	public function matches(Throwable $exception): bool
	{
		$accepted = $this->serverRequest->getHeaderLine('Accept');

		return str_contains(Str::lower($accepted), 'application/json');
	}

	public function process(Throwable $exception): ResponseInterface
	{
		$data = [
			'error' => [
				'type' => get_class($exception),
				'code' => $exception->getCode(),
				'message' => $exception->getMessage(),
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
			],
		];

		return $this->responseAdapter
			->create($data);
	}
}
