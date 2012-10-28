<?php

require_once __DIR__ . '/../../library/autoload.php';
\Lite\Loader::addIncludePath(__DIR__ . '/application/models');
\Lite\Loader::registerAutoload();

$app = new Lite\Mvc\Application();
$app->view->setPath(__DIR__ . '/application/views');
$app->view->messages = $app->flash()->get();

$app->get('/', function() {
    $this->redirect('/todo');
});

$app->get('/todo', function() {
    $todo = new Todo();
    $this->view->todos = $todo->getList();
    $this->view->render('todo/list');
});

$app->get('/todo/new', function() {
    $this->view->render('todo/new');
});

$app->post('/todo', function() {
    $todo = new Todo();
    $todo->add($this->request->title, $this->request->description);
    $this->flash('info', 'create todo successfully!');
    $this->redirect('/todo');
});

$app->get('/todo/:id/edit', function($id) {
    $todo = new Todo();
    $item = $todo->get($id);
    if (!$item) {
        $this->page404();
    }
    $this->view->todo = $item;
    $this->view->render('/todo/edit');
});

$app->put('/todo/:id', function($id) {
    $todo = new Todo();
    $item = $todo->get($id);
    if (!$item) {
        $this->page404();
    }
    $todo->update($id, $this->request->title, $this->request->description);
    $this->flash('info', 'update todo successfully!');
    $this->redirect('/todo');
});

$app->delete('/todo/:id', function($id) {
    $todo = new Todo();
    $item = $todo->get($id);
    if (!$item) {
        $this->page404();
    }
    $todo->delete($id);
    $this->flash('info', 'delete todo successfully!');
    $this->redirect('/todo');
});

$app->run();