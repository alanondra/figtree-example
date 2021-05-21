<?php

namespace App\Config;

use FigTree\Config\ConfigFactory as AbstractFactory;
use FigTree\Config\Contracts\ConfigInterface;
use FigTree\Framework\Core\Concerns\UsesContext;
use FigTree\Framework\Core\Contracts\ContextAwareInterface;

class ConfigFactory extends AbstractFactory implements ContextAwareInterface
{
	use UsesContext;

	/**
	 * Create a Config instance.
	 *
	 * @param array $paths
	 *
	 * @return \FigTree\Config\Contracts\ConfigInterface
	 */
	public function create(array $paths): ConfigInterface
	{
		$instance = new Config($paths);

		$instance->setContext($this->context);

		return $instance;
	}
}
