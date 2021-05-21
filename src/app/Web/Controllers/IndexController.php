<?php

namespace App\Web\Controllers;

use Psr\Http\Message\ResponseInterface;

class IndexController extends AbstractController
{
	public function index(): ResponseInterface
	{
		return $this->twig->create('pages/index.html.twig');
	}
}
