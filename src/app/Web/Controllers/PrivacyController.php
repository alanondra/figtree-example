<?php

namespace App\Web\Controllers;

use Psr\Http\Message\ResponseInterface;

class PrivacyController extends AbstractController
{
	public function index(): ResponseInterface
	{
		return $this->twig->create('pages/privacy.html.twig');
	}
}
