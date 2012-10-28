<?php

require_once __DIR__ . '/../../library/autoload.php';

$app = new Lite\Application();

$app->get('/', function() {
    echo 'Hello World!';
});

$app->run();