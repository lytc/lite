<?php

class Todo
{
    protected $_todos;

    public function __construct()
    {
        $this->_todos = &\Lite\Session::getInstance()->getNamespace('todos');
    }

    public function getList()
    {
        return $this->_todos;
    }

    public function get($id)
    {
        if (isset($this->_todos[$id])) {
            return $this->_todos[$id];
        }
    }

    public function add($title, $description)
    {
        $id = uniqid();
        $this->_todos[$id] = ['id' => $id, 'title' => $title, 'description' => $description];
        return $this;
    }

    public function update($id, $title, $description)
    {
        if (!isset($this->_todos[$id])) {
            throw new \Lite\Exception\NotFound("Todo not found");
        }

        $this->_todos[$id] = ['id' => $id, 'title' => $title, 'description' => $description];
        return $this->_todos[$id];
    }

    public function delete($id)
    {
        if (!isset($this->_todos[$id])) {
            throw new \Lite\Exception\NotFound("Todo not found");
        }

        unset($this->_todos[$id]);
    }
}