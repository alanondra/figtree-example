<?php

namespace App\Config;

use FigTree\Config\Config as AbstractConfig;
use FigTree\Config\Contracts\ConfigReaderInterface;
use FigTree\Framework\Core\Concerns\UsesContext;
use FigTree\Framework\Core\Contracts\ContextAwareInterface;

class Config extends AbstractConfig implements ContextAwareInterface
{
	use UsesContext;

	/**
	 * Create a ConfigReader instance.
	 *
	 * @return \FigTree\Config\Contracts\ConfigReaderInterface
	 */
	public function createReader(): ConfigReaderInterface
	{
		return new ConfigReader($this->context);
	}
}
