<?php

namespace App\Web\Responses\Adapters;

use App\Web\Responses\Adapters\Contracts\ResponseAdapterInterface;
use FigTree\Framework\Web\Concerns\{
	UsesResponseFactory,
	UsesStreamFactory,
};

abstract class AbstractAdapter implements ResponseAdapterInterface
{
	use UsesResponseFactory;
	use UsesStreamFactory;
}
