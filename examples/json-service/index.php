<?php

require_once __DIR__ . '/../../library/autoload.php';

$app = new Lite\Application();

$app->get('/', function() {
    $this->response->setJsonBody(['foo' => 'foo']);
});

$app->get('/:foo', function($foo) {
    $this->response->setJsonBody([$foo]);
});

$app->run();