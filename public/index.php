<?php

ob_end_clean();

/**
 * @var \FigTree\Framework\Core\Application
 */
$app = (require_once '../app.php');

return $app->serve();
