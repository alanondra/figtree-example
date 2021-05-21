<?php

namespace App\Exceptions\Strategies;

use Throwable;
use Psr\Http\Message\ResponseInterface;
use FigTree\Framework\Support\Str;
use FigTree\Framework\Exceptions\Contracts\ExceptionResponseStrategyInterface;
use FigTree\Framework\Web\Exceptions\Contracts\HttpExceptionInterface;
use FigTree\Framework\Web\Concerns\UsesServerRequest;
use FigTree\Framework\Web\Contracts\ServerRequestAwareInterface;
use League\Route\Http\Exception\HttpExceptionInterface as RouterHttpExceptionInterface;
use App\Web\Responses\Adapters\TwigAdapter as TwigResponseAdapter;
use FigTree\Framework\Web\Exceptions\InternalServerErrorException;

class TwigStrategy implements
	ExceptionResponseStrategyInterface,
	ServerRequestAwareInterface
{
	use UsesServerRequest;

	public function __construct(protected TwigResponseAdapter $responseAdapter)
	{
		//
	}

	public function matches(Throwable $exception): bool
	{
		return $this->acceptsHtml() && $this->canRender($exception);
	}

	public function process(Throwable $exception): ResponseInterface
	{
		$statusCode = $this->getStatusCode($exception);

		$data = [
			'error' => $exception,
		];

		$view = $this->getStatusView($statusCode);

		try {
			if (!$this->responseAdapter->exists($view)) {
				throw new InternalServerErrorException(sprintf('Missing error view for HTTP Status %d', $statusCode), 0, $exception);
			}

			$response = $this->responseAdapter->create($view, $data);
		} catch (Throwable $exc) {
			$data['error'] = $exc;

			$view = $this->getStatusView(500);

			$response = $this->responseAdapter->create($view, $data);
		}

		return $response->withStatus($statusCode);
	}

	protected function acceptsHtml(): bool
	{
		$accepted = Str::lower($this->serverRequest->getHeaderLine('Accept'));

		return (str_contains($accepted, 'text/html') || str_contains($accepted, '*/*'));
	}

	protected function canRender(Throwable $exception)
	{
		$statusCode = $this->getStatusCode($exception);

		$view = $this->getStatusView($statusCode);

		if ($this->responseAdapter->exists($view)) {
			return true;
		} elseif ($statusCode == 500) {
			return false;
		}

		$view = $this->getStatusView(500);

		return $this->responseAdapter->exists($view);
	}

	protected function getStatusCode(Throwable $exception)
	{
		if ($exception instanceof HttpExceptionInterface) {
			$statusCode = $exception->getStatus();
		} elseif ($exception instanceof RouterHttpExceptionInterface) {
			$statusCode = $exception->getStatusCode();
		}

		return $statusCode ?? 500;
	}

	protected function getStatusView(int $statusCode)
	{
		return sprintf('errors/%d.html.twig', $statusCode);
	}
}
