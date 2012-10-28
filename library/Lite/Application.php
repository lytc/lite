<?php

namespace Lite;

class Application
{
    use Observable,
        Settable;

    /**
     * @var string
     */
    protected $_env;

    /**
     * @var string
     */
    protected $_errorTemplatePath;

    /**
     * @var array
     */
    protected $_serverVars;

    /**
     * @var
     */
    protected $_baseUri = '';

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var Router
     */
    protected $_router;

    /**
     * @var View
     */
    protected $_view;

    /**
     * @var
     */
    protected $_exceptionHandler;

    /**
     * @var array
     */
    protected $_defaultConfigs = array();

    /**
     * @var array
     */
    protected static $_instances = [];

    /**
     * @param array $configs
     */
    public function __construct(array $configs = array())
    {
        $this->handleException();
        $configs = array_merge_recursive($this->_defaultConfigs, $configs);
        $this->applySetter($configs);
    }

    /**
     * @param string [$name]
     * @return Application
     */
    public static function getInstance($name = 'default')
    {
        if (!self::$_instances[$name]) {
            self::$_instances[$name] = new self();
        }
        return self::$_instances[$name];
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        if (!$this->_env) {
            $this->_env = getenv('APPLICATION_ENV');
            if (!$this->_env) {
                $this->_env = 'production';
            }
        }
        return $this->_env;
    }

    /**
     * @param $env
     * @return Application
     */
    public function setEnv($env)
    {
        $this->_env = $env;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (in_array($name, ['env', 'request', 'response', 'router', 'view'])) {
            $method = 'get' . ucfirst($name);
            return $this->{$method}();
        }

        throw new Exception("Call undefined property $name");
    }

    /**
     * @return array
     */
    public function getServerVars()
    {
        if (!$this->_serverVars) {
            $this->_serverVars = $_SERVER;
        }

        return $this->_serverVars;
    }

    /**
     * @param array $env
     * @param bool [$merge=false]
     * @return Application
     */
    public function setServerVars(array $env, $merge = false)
    {
        if ($merge) {
            $env = array_merge($this->getServerVars(), $env);
        }
        $this->_serverVars = $env;
        return $this;
    }

    /**
     * @param  $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->_baseUri = $baseUri;
    }

    /**
     * @return
     */
    public function getBaseUri()
    {
        return $this->_baseUri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $uri = $this->getRequest()->getUri();

        if ($baseUri = $this->getBaseUri()) {
            $uri = preg_replace('/' . preg_quote($baseUri, '/') . '/', '', $uri);
        }

        if (!$uri) {
            $uri = '/';
        }

        if ($uri != '/' && substr($uri, -1) == '/') {
            $uri = substr($uri, 0, -1);
        }

        return $uri;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = new Request($this);
        }

        return $this->_request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!$this->_response) {
            $this->_response = new Response($this);
        }

        return $this->_response;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        if (!$this->_router) {
            $this->_router = new Router($this);
        }

        return $this->_router;
    }

    /**
     * @return View
     */
    public function getView()
    {
        if (!$this->_view) {
            $this->_view = new View($this);
        }

        return $this->_view;
    }

    /**
     * @param string $pattern
     * @param callable $callback
     * @return Application
     */
    public function match($pattern, \Closure $callback)
    {
        if (0 === strpos($this->getUri(), $pattern)) {
            $callback = $callback->bindTo($this);
            $this->setBaseUri($this->getBaseUri() . $pattern);
            call_user_func($callback);
        }
        return $this;
    }

    /**
     * @param $pattern
     * @param callable $callback
     * @return \Lite\Route\AbstractRoute
     */
    public function map($pattern, \Closure $callback)
    {
        $pattern = (string) $pattern;

        if ($pattern[0] == '#') {
            $pattern = substr($pattern, 1);
            $routeClass = '\\Lite\\Route\\Regex';
        } else if (preg_match('/\:[\w\-]+/', $pattern)) {
            $routeClass = '\\Lite\\Route\\Named';
        } else {
            $routeClass = '\\Lite\\Route\\Uri';
        }

        $route = new $routeClass($pattern);

        return $this->getRouter()->add($route, $callback);
    }

    /**
     * @param $pattern
     * @param callable $callback
     * @return AbstractRoute
     */
    public function get($pattern, \Closure $callback)
    {
        $route = $this->map($pattern, $callback);
        $route->addCondition(function() {
            return $this->getRequest()->isGet();
        });
        return $route;
    }

    /**
     * @param $pattern
     * @param callable $callback
     * @return AbstractRoute
     */
    public function post($pattern, \Closure $callback)
    {
        $route = $this->map($pattern, $callback);
        $route->addCondition(function() {
            return $this->getRequest()->isPost();
        });
        return $route;
    }

    /**
     * @param $pattern
     * @param callable $callback
     * @return AbstractRoute
     */
    public function put($pattern, \Closure $callback)
    {
        $route = $this->map($pattern, $callback);
        $route->addCondition(function() {
            return $this->getRequest()->isPut();
        });
        return $route;
    }

    /**
     * @param $pattern
     * @param callable $callback
     * @return AbstractRoute
     */
    public function delete($pattern, \Closure $callback)
    {
        $route = $this->map($pattern, $callback);
        $route->addCondition(function() {
            return $this->getRequest()->isDelete();
        });
        return $route;
    }

    /**
     * @param $pattern
     * @param callable $callback
     * @return AbstractRoute
     */
    public function options($pattern, \Closure $callback)
    {
        $route = $this->map($pattern, $callback);
        $route->addCondition(function() {
            return $this->getRequest()->isOptions();
        });

        return $route;
    }

    /**
     * @return Application
     */
    public function cleanBuffer()
    {
        ob_clean();
        return $this;
    }

    public function stop()
    {

    }

    /**
     * @param \Closure $callback
     * @return Application
     */
    public function handleException()
    {
        if (!$this->_exceptionHandler) {
            $this->_exceptionHandler = function($exception) {
                http_response_code(500);
                $this-> _includeErrorTemplateFile('500', ['exception' => $exception]);
            };
        }

        set_exception_handler($this->_exceptionHandler->bindTo($this));
        return $this;
    }

    /**
     * @param \Closure $callback
     * @return Application
     */
    public function setExceptionHandler(\Closure $callback)
    {
        restore_error_handler();
        $this->_exceptionHandler = $callback;
        $this->handleException();
        return $this;
    }

    /**
     * @param $type
     */
    protected function _includeErrorTemplateFile($type, array $params = [])
    {
        $path = $this->_errorTemplatePath;
        if (!$path) {
            $path = __DIR__ . '/../error-templates';
        }

        $file = "$path/$type/%s.php";
        $envFile = sprintf($file, $this->getEnv());

        if (!file_exists($envFile)) {
            $envFile = sprintf($file, 'default');
        }

        extract($params);
        include $envFile;
    }

    public function page404()
    {
        http_response_code(404);
        $this->_includeErrorTemplateFile('404');
    }

    /**
     * @throws Exception\Pass
     */
    public function pass()
    {
        $this->cleanBuffer();
        throw new \Lite\Exception\Pass();
    }

    /**
     * @param string|array $env
     * @throws Exception\Pass
     */
    public function forward($env) {
        if (is_string($env)) {
            $env = ['REQUEST_URI' => $env];
        }

        $this->setServerVars($env, true);
        $this->pass();
    }

    /**
     * @param int [$status]
     * @param string [$message]
     * @param array [$headers]
     * @throws Exception\Halt
     */
    public function halt($status = null, $message = null, array $headers = [])
    {
        $this->cleanBuffer();
        if (null !== $status) {
            switch(func_num_args()) {
                case 1:
                    if (gettype($status) == 'string') {
                        $message = $status;
                        $status = null;
                    }
                    break;

                case 2:
                    if (gettype($status) == 'string') {
                        $headers = $message;
                        $message = $status;
                        $status = null;
                    }
                    break;
            }
            $response = $this->getResponse();
            if ($status) {
                $response->setStatus($status);
            }

            if (null !== $message) {
                $response->setMessage($message);
            }

            if ($headers) {
                $response->setHeaders($headers, true);
            }
        }

        throw new \Lite\Exception\Halt();
    }

    /**
     * @param string $url
     */
    public function redirect($url, $status = 302, array $params = [])
    {
        if ($status) {
            if (is_array($status)) {
                $params = $status;
                $status = 302;
            }
        }
        $this->getResponse()->setHeader('Location', $url);
        if ($params) {
            \Lite\Session\Flash::getInstance()->set($params);
        }
        $this->halt($status);
    }

    /**
     * @param string [$name]
     * @param mixed [$value]
     * @return Application|FlashMessenger
     */
    public function flash($name = null, $value = null)
    {
        $flashMessenger = FlashMessenger::getInstance();
        if (!$name) {
            return $flashMessenger;
        }

        $flashMessenger->set($name, $value);
        return $this;
    }

    /**
     * @return Application
     */
    public function run()
    {
        try {
            try {
                $this->notifyEvent('run.before');

                $this->getRouter()->dispatch();

                if ($this->_view) {
                    $this->getResponse()->setBody($this->_view->render());
                }
                $this->getResponse()->send();
                $this->notifyEvent('run');
            } catch(\Lite\Exception\NotFound $exception) {
                $this->page404();
            }
        } catch (\Lite\Exception\Halt $exception) {
            $this->getResponse()->send();
        }

        return $this;
    }
}