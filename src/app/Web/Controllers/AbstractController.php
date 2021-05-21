<?php

namespace App\Web\Controllers;

use Psr\Http\Message\ResponseFactoryInterface;
use App\Web\Responses\Adapters\JsonAdapter;
use App\Web\Responses\Adapters\TwigAdapter;

abstract class AbstractController
{
	public function __construct(
		protected ResponseFactoryInterface $response,
		protected JsonAdapter $json,
		protected TwigAdapter $twig
	) {
		//
	}
}
