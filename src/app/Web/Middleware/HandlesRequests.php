<?php

namespace App\Web\Middleware;

use Throwable;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Http\Exception\HttpExceptionInterface;
use FigTree\Framework\Exceptions\Contracts\ExceptionHandlerInterface;
use FigTree\Framework\Web\Exceptions\InternalServerErrorException;

class HandlesRequests extends AbstractMiddleware
{
	public function __construct(protected ExceptionHandlerInterface $exceptionHandler)
	{
		//
	}

	/**
	 * Process an incoming server request.
	 *
	 * Processes an incoming server request in order to produce a response.
	 * If unable to produce the response itself, it may delegate to the provided
	 * request handler to do so.
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Server\RequestHandlerInterface $handler
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		try {
			$response = $handler->handle($request);
		} catch (HttpExceptionInterface $exc) {
			$thrown = $this->validateException($exc);
			$this->exceptionHandler->handleException($thrown);
			$response = $this->exceptionHandler->toResponse($thrown);
		} catch (Throwable $exc) {
			$this->exceptionHandler->handleException($exc);
			$response = $this->exceptionHandler->toResponse($exc);
		}

		return $response;
	}

	/**
	 * Coerce League Route exceptions into Throwables.
	 *
	 * @return \Throwable
	 */
	protected function validateException(HttpExceptionInterface $exc): Throwable
	{
		if ($exc instanceof Throwable) {
			return $exc;
		}

		return new InternalServerErrorException('%s does not implement Throwable.', get_class($exc));
	}
}
