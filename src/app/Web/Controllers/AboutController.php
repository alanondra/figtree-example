<?php

namespace App\Web\Controllers;

use Psr\Http\Message\ResponseInterface;

class AboutController extends AbstractController
{
	public function index(): ResponseInterface
	{
		return $this->twig->create('pages/about.html.twig');
	}
}
