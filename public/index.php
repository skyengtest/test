<?php

require_once __DIR__ . '/../application/bootstrap.php';

$config = new components\Config([
    CONFIGS_PATH . DS . 'base.php',
    CONFIGS_PATH . DS . 'application.php'
]);
$router = new components\Router();
$app    = new components\Application($config, $router);
$app->run();