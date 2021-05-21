<?php

require 'vendor/autoload.php';

$context = new FigTree\Framework\Core\Context(__DIR__);

/**
 * @var \FigTree\Framework\Core\Application
 */
$app = (require 'src/bootstrap/app.php');

return $app;
