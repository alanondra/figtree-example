<?php

namespace App\Web\Middleware;

use Throwable;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use FigTree\Framework\Exceptions\Contracts\ExceptionHandlerInterface;

class HandlesExceptions extends AbstractMiddleware
{
	protected Throwable $exception;

	public function __construct(protected ExceptionHandlerInterface $handler)
	{
		//
	}

	/**
	 * Set the Throwable instance.
	 *
	 * @param \Throwable $exception
	 *
	 * @return $this
	 */
	public function setException(Throwable $exception)
	{
		$this->exception = $exception;

		return $this;
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
		return $this->handler->toResponse($this->exception);
	}
}
