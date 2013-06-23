<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget that handles the HTTP response data
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Logger $logger The logger widget
 */
class Response extends AbstractWidget
{
    /**
     * Common use HTTP status code and text
     *
     * @var array
     */
    protected static $codeTexts = array(
        // Successful Requests
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirects
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        // Client Errors
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Errors
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    /**
     * The HTTP version, current is 1.0 or 1.1
     *
     * @var string
     */
    protected $version = '1.1';

    /**
     * The status code
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * The status text for status code
     *
     * @var string
     */
    protected $statusText = 'OK';

    /**
     * The response content
     *
     * @var string
     */
    protected $content;

    /**
     * The response headers
     *
     * @var array
     */
    protected $headers = array();

    /**
     * The response cookies
     *
     * @var array
     */
    protected $cookies = array();

    /**
     * The cookie options
     *
     * Name     | Type   | Description
     * ---------|--------|-------------
     * expires  | int    | The lifetime of cookie (seconds)
     * path     | string | The path on the server in which the cookie will be available on
     * domain   | string | The domain that the cookie is available to
     * secure   | bool   | Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * httpOnly | bool   | When TRUE the cookie will be made accessible only through the HTTP protocol
     * raw      | bool   | Whether send a cookie without urlencoding the cookie value
     *
     * @var array
     * @link http://php.net/manual/en/function.setcookie.php
     */
    protected $cookieOption = array(
        'expires'   => 864000,
        'path'      => '/',
        'domain'    => null,
        'secure'    => false,
        'httpOnly'  => false,
        'raw'       => false,
    );

    /**
     * Whether response content has been sent
     *
     * @var bool
     */
    protected $isSent = false;

    /**
     * Send response header and content
     *
     * @param  string         $content
     * @param  int            $status
     * @return Response
     */
    public function __invoke($content = null, $status = null)
    {
        return $this->send($content, $status);
    }

    /**
     * Set response content
     *
     * @param  mixed          $content
     * @return Response
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get response content
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Send response content
     *
     * @return Response
     */
    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    /**
     * @see Response::__invoke
     */
    public function send($content = null, $status = null)
    {
        $this->isSent = true;

        if (null !== $content) {
            $this->content = $content;
        }

        if (null !== $status) {
            $this->setStatusCode($status);
        }

        $this->sendHeader();

        $this->sendContent();

        return $this;
    }

    /**
     * Set the header status code
     *
     * @param  int          $code The status code
     * @param  string|null       $text The status text
     * @return Response
     */
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = (int) $code;

        if ($text) {
            $this->statusText = $text;
        } elseif (isset(static::$codeTexts[$code])) {
            $this->statusText = static::$codeTexts[$code];
        }

        return $this;
    }

    /**
     * Get the status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the HTTP version
     *
     * @param  string       $version The HTTP version
     * @return Header
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the HTTP version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Send headers, including HTTP status, raw headers and cookie
     *
     * @return false|Response
     */
    public function sendHeader()
    {
        $file = $line = null;
        if (headers_sent($file, $line)) {
            $this->logger->debug(sprintf('Header has been at %s:%s', $file, $line));
            return false;
        }

        // Send status
        header(sprintf('HTTP/%s %d %s', $this->version, $this->statusCode, $this->statusText));

        // Send headers
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        $this->sendCookie();

        return $this;
    }

    /**
     * Set response cookie
     *
     * @param  string       $key     The name of cookie
     * @param  mixed        $value   The value of cookie
     * @param  array        $options The options of cookie
     * @return Response
     */
    public function setCookie($key, $value , array $options = array())
    {
        $this->cookies[$key] = array('value' => $value) + $options;

        return $this;
    }

    /**
     * Remove response cookie
     *
     * @param string $key The name of cookie
     * @return Response
     */
    public function removeCookie($key)
    {
        return $this->setCookie($key, '', array('expires' => -1));
    }

    public function sendCookie()
    {
        $time = time();
        foreach ($this->cookies as $name => $o) {
            $o += $this->cookieOption;
            $fn = $o['raw'] ? 'setrawcookie' : 'setcookie';
            $fn($name, $o['value'], $time + $o['expire'], $o['path'], $o['domain'], $o['secure'], $o['httpOnly']);
        }
        $this->cookies = array();

        return $this;
    }

    /**
     * Returns response status, headers and content as string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('HTTP/%s %d %s', $this->version, $this->statusCode, $this->statusText) . "\r\n"
            . $this->getHeaderString() . "\r\n"
            . $this->content;
    }

    /**
     * Returns the response headers variable reference
     *
     * @return array
     */
    public function &getHeaderReference()
    {
        return $this->headers;
    }

    /**
     * Returns response header as string
     *
     * @return string
     */
    public function getHeaderString()
    {
        $string = '';
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                $string .= $name . ': ' . $value . "\r\n";
            }
        }
        return $string;
    }

    /**
     * Check if response has been sent
     *
     * @return bool
     */
    public function isSent()
    {
        return $this->isSent;
    }

    /**
     * Set response sent status
     *
     * @param  bool           $bool
     * @return Response
     */
    public function setSentStatus($bool)
    {
        $this->isSent = (bool) $bool;

        return $this;
    }
}
