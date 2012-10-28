<?php

namespace Lite;

class Request
{
    /**
     * @var Application
     */
    protected $_application;

    /**
     * @var string
     */
    protected $_uri;

    /**
     * @var array
     */
    protected $_headers;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     * @var
     */
    protected $_method;

    /**
     * @var string
     */
    protected $_rewriteMethodParamName = '__METHOD__';

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->_application = $application;
    }


    /**
     * @return string
     */
    public function getUri()
    {
        if (!$this->_uri) {
            $this->_uri = $this->_application->getServerVars()['REQUEST_URI'];
        }
        return $this->_uri;
    }

    /**
     * @return array
     */
    function getHeaders()
    {
        if (null === $this->_headers) {
            $this->_headers = [];
            $env = $this->_application->getServerVars();
            foreach ($env as $key => $value) {
                if ('HTTP' == substr($key, 0, 4)) {
                    $this->_headers[substr($key, 5)] = $value;
                }
            }

            # try get from apache
            if (function_exists('apache_request_headers')) {
                $headers = apache_request_headers();
                foreach ($headers as $key => $value) {
                    $this->_headers[strtoupper($key)] = $value;
                }
            }
        }

        return $this->_headers;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return string
     */
    public function getHeader($name, $default = null)
    {
        $name = strtoupper($name);
        $headers = $this->getHeaders();
        return isset($headers[$name])? $headers[$name] : $default;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        if (!$this->_params) {
            $this->_params = array_merge($_GET, $_POST, $_COOKIE, \Lite\Session\Flash::getInstance()->get());
        }
        return $this->_params;
    }

    /**
     * @param array $params
     * @return AbstractRequest
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * @return Request
     */
    public function resetCacheParams()
    {
        $this->_params = null;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return AbstractRequest
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed [$default]
     * @return mixed|null
     */
    public function getParam($name, $default = null)
    {
        $params = $this->getParams();
        if (isset($params[$name])) {
            return $params[$name];
        }

        return $default;
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getParamGet($name, $default = null)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return $default;
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getParamPost($name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        return $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return AbstractRequest
     */
    public function __set($name, $value)
    {
        return $this->setParam($name, $value);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->getParam($name);
    }

    public function pickParams($args)
    {
        if (!is_array($args)) {
            $args = func_get_args();
        }

        $result = [];

        foreach ($args as $name) {
            $result[$name] = $this->getParam($name);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        if (!$this->_method) {
            $method = strtoupper($this->_application->getServerVars()['REQUEST_METHOD']);
            if ($method == 'POST' && ($methodRewrite = strtoupper($this->getParam($this->_rewriteMethodParamName)))) {
                if (in_array($methodRewrite, ['PUT', 'DELETE', 'OPTIONS'])) {
                    $method = $methodRewrite;
                }
            }
            $this->_method = $method;
        }

        return $this->_method;
    }

    /**
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() == 'GET';
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() == 'POST';
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        return $this->getMethod() == 'PUT';
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return $this->getMethod() == 'DELETE';
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        return $this->getMethod() == 'OPTIONS';
    }

    /**
     * @return bool
     */
    public function isXhr()
    {
        $header = $this->getHeader('X_REQUESTED_WITH');
        return $header && strtolower($header) == 'xmlhttprequest';
    }
}