<?php

namespace App\Web\Responses\Adapters;

use FigTree\Framework\Support\Json;
use Psr\Http\Message\ResponseInterface;

class JsonAdapter extends AbstractAdapter
{
	public function create($data, int $options = 0, int $depth = 512): ResponseInterface
	{
		$body = Json::encode($data, $options, $depth);

		$stream = $this->streamFactory->createStream($body);

		return $this->responseFactory
			->createResponse(200)
			->withAddedHeader('Content-Type', 'application/json')
			->withBody($stream);
	}
}
