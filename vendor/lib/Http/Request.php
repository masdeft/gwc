<?php

namespace Gwc\Lib\Http;

class Request
{

    /**
     * @var string|HttpUri
     */
    protected $uri = null;

    /**
     * Returns the named GET parameter value.
     * If the GET parameter does not exist, the second parameter to this method will be returned.
     *
     * @param string $name the GET parameter name
     * @param mixed $defaultValue the default parameter value if the GET parameter does not exist.
     * @return mixed the GET parameter value
     */
    public function getQuery($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
    }

    /**
     * Returns the named POST parameter value.
     * If the POST parameter does not exist, the second parameter to this method will be returned.
     *
     * @param string $name the POST parameter name
     * @param mixed $defaultValue the default parameter value if the POST parameter does not exist.
     * @return mixed the POST parameter value
     */
    public function getPost($name, $defaultValue = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
    }

    /**
     * Returns the request URI portion for the currently requested URL.
     * This refers to the portion that is after the {@link hostInfo host info} part.
     * It includes the {@link queryString query string} part if any.
     * The implementation of this method referenced Zend_Controller_Request_Http in Zend Framework.
     *
     * @return string the request URI portion for the currently requested URL.
     * @throws \Exception if the request URI cannot be determined due to improper server configuration
     */
    public function getRequestUri()
    {
        if ($this->uri === null) {
            if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
                $this->uri = $_SERVER['HTTP_X_REWRITE_URL'];

            } elseif (isset($_SERVER['REQUEST_URI'])) {
                $this->uri = $_SERVER['REQUEST_URI'];
                if (isset($_SERVER['HTTP_HOST'])) {
                    if (strpos($this->uri, $_SERVER['HTTP_HOST']) !== false) {
                        $this->uri = preg_replace('/^\w+:\/\/[^\/]+/', '', $this->uri);
                    }
                } else {
                    $this->uri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $this->uri);
                }

            } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
                $this->uri = $_SERVER['ORIG_PATH_INFO'];
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $this->uri .= '?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                throw new \Exception('Request is unable to determine the request URI.');
            }
        }

        return $this->uri;
    }
}
