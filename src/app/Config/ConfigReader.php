<?php

namespace App\Config;

use FigTree\Config\ConfigReader as Reader;
use FigTree\Framework\Core\Context;

class ConfigReader extends Reader
{
	public function __construct(protected Context $context)
	{
		//
	}
}
