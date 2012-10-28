<?php

namespace Lite;

class Router
{
    /**
     * @var Application
     */
    protected $_application;

    /**
     * @var array
     */
    protected $_routes = array();

    /**
     * @var array
     */
    protected $_matches;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->_application = $application;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_routes);
    }

    /**
     * @param Route\AbstractRoute $route
     * @param Closure [$callback]
     * @return AbstractRoute
     */
    public function add(\Lite\Route\AbstractRoute $route, \Closure $callback)
    {
        $this->_routes[] = ['route' => $route, 'callback' => $callback];
        return $route;
    }

    /**
     * @param Route\AbstractRoute $route
     * @param Closure [$callback]
     * @return Router
     */
    public function remove(\Lite\Route\AbstractRoute $route, \Closure $callback = null)
    {
        foreach ($this->_routes as $key => $item) {
            if ($item['route'] == $route) {
                if ($callback) {
                    if ($callback == $item['callback']) {
                        unset($this->_routes[$key]);
                    }
                } else {
                    unset($this->_routes[$key]);
                }
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function matches()
    {
        if (null === $this->_matches) {
            $matches = [];
            $uri = $this->_application->getUri();

            foreach ($this->_routes as $item) {
                $route = $item['route'];
                if (false !== ($params = $route->match($uri))) {
                    $matches[] = ['route' => $route, 'callback' => $item['callback'], 'params' => $params];
                }
            }

            $this->_matches = $matches;
        }

        return $this->_matches;
    }

    /**
     * @return Router
     * @throws Exception\NotFound
     */
    public function dispatch()
    {
        $matches = $this->matches();

        if (!$matches) {
            throw new \Lite\Exception\NotFound();
        }

        $firstMatch = array_shift($this->_matches);

        try {
            $callback = $firstMatch['callback'];
            $callback = $callback->bindTo($this->_application);
            call_user_func_array($callback, $firstMatch['params']);
        } catch (\Lite\Exception\Pass $e) {
            $this->dispatch();
        }
        return $this;
    }
}