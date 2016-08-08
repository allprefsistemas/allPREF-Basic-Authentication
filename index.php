<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

$app = new Silex\Application;

$app['debug'] = DEBUG;

require __DIR__ . '/registers.php';

require __DIR__ . '/routes.php';

$app->run();
