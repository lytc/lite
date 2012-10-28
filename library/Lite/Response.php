<?php

namespace Lite;

class Response
{
    /**
     * @var Application
     */
    protected $_application;

    /**
     * @var int
     */
    protected $_status = 200;

    /**
     * @var
     */
    protected $_message;

    /**
     * @var array
     */
    protected $_headers = [
        'Content-Type'  => 'text/html'
    ];

    /**
     * @var string
     */
    protected $_body = '';

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->_application = $application;
    }

    /**
     * @param array $headers
     * @param bool [$merge=false]
     * @return Response
     */
    public function setHeaders(array $headers, $merge = false)
    {
        if ($merge) {
            $headers = array_merge($this->_headers, $headers);
        }
        $this->_headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Response
     */
    public function setHeader($name, $value = null)
    {
        if (null === $value) {
            $value = $name;
        }
        $this->_headers[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getHeader($name)
    {
        if (isset($this->_headers[$name])) {
            return $this->_headers[$name];
        }
    }

    /**
     * @param string $contentType
     * @return Response
     */
    public function setContentType($contentType)
    {
        return $this->setHeader('Content-Type', $contentType);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param $status
     * @return Response
     */
    public function setStatus($status)
    {
        $this->_status = (int) $status;
        return $this;
    }

    /**
     * @param $message
     * @return Response
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * @param mixed $body
     * @return Response
     */
    public function setJsonBody($body)
    {
        $this->setContentType('application/json');
        return $this->setBody(json_encode($body));
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    public function send()
    {
        if (!headers_sent()) {
            foreach ($this->_headers as $name => $value) {
                if ($name != $value) {
                    header("$name: $value");
                } else {
                    header("$name");
                }
            }
        }

        echo $this->_body;
        return $this;
    }
}