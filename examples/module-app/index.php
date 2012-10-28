<?php

require_once __DIR__ . '/../../library/autoload.php';

$app = new Lite\Application();

$app->match('/foo', function() {
    $this->get('/', function() {
        echo 'foo -> index';
    });
    $this->get('/baz', function() {
        echo 'foo -> baz';
    });
    $this->run();
});

$app->match('/bar', function() {
    $this->get('/', function() {
        echo 'bar -> index';
    });
    $this->get('/baz', function() {
        echo 'bar -> baz';
    });
    $this->run();
});

