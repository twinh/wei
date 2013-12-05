<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service that handles the HTTP response data
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Logger $logger The logger wei
 */
class Response extends Base
{
    /**
     * Common use HTTP status code and text
     *
     * @var array
     */
    protected $codeTexts = array(
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
     * The sent response headers
     *
     * @var array
     */
    protected $sentHeaders = array();

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
     * The download options
     *
     * Name        | Type   | Description
     * ------------|--------|-------------
     * type        | string | The HTTP content type
     * disposition | string | The type of disposition, could be "attachment" or "inline",
     * filename    | string | The file name to display in download dialog
     *
     * When disposition is "inline", the browser will try to open file within
     * the browser, while "attachment" will force it to download
     *
     * @var array
     * @link http://stackoverflow.com/questions/1395151/content-dispositionwhat-are-the-differences-between-inline-and-attachment
     */
    protected $downloadOption = array(
        'type'          => 'application/x-download',
        'disposition'   => 'attachment',
        'filename'      => null,
    );

    /**
     * Whether response content has been sent
     *
     * @var bool
     */
    protected $isSent = false;

    /**
     * Whether in unit test mode
     *
     * @var bool
     */
    protected $unitTest = false;

    /**
     * The custom redirect view file
     *
     * @var string
     */
    protected $redirectView;

    /**
     * The seconds to wait before redirect
     *
     * @var int
     */
    protected $redirectWait = 0;

    /**
     * The default view content
     *
     * @var string
     */
    protected $redirectHtml = '<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="%d;url=%2$s">
    <title>Redirect to %s</title>
  </head>
  <body>
    <h1>Redirecting to <a href="%2$s">%2$s</a></h1>
  </body>
</html>';

    /**
     * The callback executes *before* send response
     *
     * @var callable
     */
    protected $beforeSend;

    /**
     * The callback executes *after* sent response
     *
     * @var callable
     */
    protected $afterSend;

    /**
     * Send response header and content
     *
     * @param  string         $content
     * @param  int            $status
     * @return $this
     */
    public function __invoke($content = null, $status = null)
    {
        return $this->send($content, $status);
    }

    /**
     * Send response header and content
     *
     * @param  string         $content
     * @param  int            $status
     * @return $this
     */
    public function send($content = null, $status = null)
    {
        $this->isSent = true;

        // Render json when content is array
        if (is_array($content)) {
            return $this->json($content);
        } elseif (null !== $content) {
            $this->setContent($content);
        }

        if (null !== $status) {
            $this->setStatusCode($status);
        }

        // Trigger the after send callback
        $this->beforeSend && call_user_func($this->beforeSend, $this, $content);

        $this->sendHeader();
        $this->sendContent();

        // Trigger the after send callback
        $this->afterSend && call_user_func($this->afterSend, $this);

        return $this;
    }

    /**
     * Set response content
     *
     * @param  mixed          $content
     * @return $this
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
     * @return $this
     */
    public function sendContent()
    {
        echo $this->content;
        return $this;
    }

    /**
     * Set the header status code
     *
     * @param  int          $code The status code
     * @param  string|null       $text The status text
     * @return $this
     */
    public function setStatusCode($code, $text = null)
    {
        $this->statusCode = (int) $code;

        if ($text) {
            $this->statusText = $text;
        } elseif (isset($this->codeTexts[$code])) {
            $this->statusText = $this->codeTexts[$code];
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
     * @return $this
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
     * Set the header string
     *
     * @param  string       $name    The header name
     * @param  string|array $values  The header values
     * @param  bool         $replace Whether replace the exists values or not
     * @return $this
     */
    public function setHeader($name, $values = null, $replace = true)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->setHeader($key, $value);
            }
            return $this;
        }

        $values = (array) $values;
        if (true === $replace || !isset($this->headers[$name])) {
            $this->headers[$name] = $values;
        } else {
            $this->headers[$name] = array_merge($this->headers[$name], $values);
        }

        return $this;
    }

    /**
     * Get the header string
     *
     * @param  string $name    The header name
     * @param  mixed  $default The default value
     * @param  bool   $first   Return the first element or the whole header values
     * @return mixed
     */
    public function getHeader($name, $default = null, $first = true)
    {
        if (!isset($this->headers[$name])) {
            return $default;
        }

        if (is_array($this->headers[$name]) && $first) {
            return current($this->headers[$name]);
        }

        return $this->headers[$name];
    }

    /**
     * Remove header by specified name
     *
     * @param string $name The header name
     * @return $this
     */
    public function removeHeader($name)
    {
        header_remove($name);
        unset($this->headers[$name]);
        return $this;
    }

    /**
     * Send HTTP headers, including HTTP status, raw headers and cookies
     *
     * @return bool If the header has been seen, return false, otherwise, return true
     */
    public function sendHeader()
    {
        $file = $line = null;
        if ($this->isHeaderSent($file, $line)) {
            if ($this->wei->has('logger')) {
                $this->logger->debug(sprintf('Header has been at %s:%s', $file, $line));
            }
            return false;
        }

        // Send status
        $this->sendRawHeader(sprintf('HTTP/%s %d %s', $this->version, $this->statusCode, $this->statusText));

        // Send headers
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                $this->sendRawHeader($name . ': ' . $value);
            }
        }

        $this->sendCookie();

        return true;
    }

    /**
     * Send a raw HTTP header
     *
     * If in unit test mode, the response will store header string into
     * `sentHeaders` property without send it for testing purpose
     *
     * @param string $header
     */
    protected function sendRawHeader($header)
    {
        $this->unitTest ? ($this->sentHeaders[] = $header) : header($header, false);
    }

    /**
     * Checks if or where headers have been sent
     *
     * If NOT in unit test mode and the optional `file` and `line` parameters
     * are set, `isHeaderSent()` will put the PHP source file name and line
     * number where output started in the file and line variables
     *
     * @param string $file
     * @param int $line The line number where the output started
     * @return bool
     * @link http://php.net/manual/en/function.headers-sent.php
     */
    public function isHeaderSent(&$file = null, &$line = null)
    {
        return $this->unitTest ? (bool)$this->sentHeaders : headers_sent($file, $line);
    }

    /**
     * Get response cookie
     *
     * @param  string $key The name of cookie
     * @param  mixed  $default The default value when cookie not exists
     * @return mixed
     */
    public function getCookie($key, $default = null)
    {
        return isset($this->cookies[$key]) ? $this->cookies[$key]['value'] : $default;
    }

    /**
     * Set response cookie
     *
     * @param  string       $key     The name of cookie
     * @param  mixed        $value   The value of cookie
     * @param  array        $options The options of cookie
     * @return $this
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
     * @return $this
     */
    public function removeCookie($key)
    {
        return $this->setCookie($key, '', array('expires' => -1));
    }

    /**
     * Send cookie
     *
     * @return $this
     */
    public function sendCookie()
    {
        $time = time();
        // Anonymous function for unit test
        $setCookie = function(){};
        foreach ($this->cookies as $name => $o) {
            $o += $this->cookieOption;
            $fn = $this->unitTest ? $setCookie : ($o['raw'] ? 'setrawcookie' : 'setcookie');
            $fn($name, $o['value'], $time + $o['expires'], $o['path'], $o['domain'], $o['secure'], $o['httpOnly']);
        }
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
     * @return $this
     */
    public function setSentStatus($bool)
    {
        $this->isSent = (bool) $bool;
        return $this;
    }

    /**
     * Set redirect view file
     *
     * @param string $redirectView The view file
     * @return $this
     * @throws \RuntimeException When view file not found
     */
    public function setRedirectView($redirectView)
    {
        if (!is_file($redirectView)) {
            throw new \RuntimeException(sprintf('Redirect view file "%s" not found', $redirectView));
        }
        $this->redirectView = $redirectView;
        return $this;
    }

    /**
     * Send a redirect response
     *
     * @param  string         $url     The url redirect to
     * @param  array          $options The redirect wei options
     * @return $this
     */
    public function redirect($url = null, $options = array())
    {
        $this->setOption($options);

        // The variables for custom redirect view
        $escapedUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $wait = (int)$this->redirectWait;

        // Location header does not support delay
        if (0 === $wait) {
            $this->setHeader('Location', $url);
        }

        // Prepare response content
        if ($this->redirectView) {
            ob_start();
            require $this->redirectView;
            $content = ob_get_clean();
        } else {
            $content = sprintf($this->redirectHtml, $wait, $escapedUrl);
        }

        return $this->send($content, $this->statusCode);
    }

    /**
     * Response JSON or JSONP format string
     *
     * @param mixed $data The variable to be convert to JSON string
     * @param bool $jsonp Whether allow response json format on demand
     * @return $this
     */
    public function json($data, $jsonp = false)
    {
        $result = json_encode($data);

        if ($jsonp && $name = $this->request['callback']) {
            $this->setHeader('Content-Type', 'application/javascript');
            $jsonp = $this->escape->js((string)$name);
            $result = $jsonp . '(' . $result . ')';
        } else {
            $this->setHeader('Content-Type', 'application/json');
        }

        return $this->send($result);
    }

    /**
     * Response JSONP format string
     *
     * @param mixed $data
     * @return $this
     */
    public function jsonp($data)
    {
        return $this->json($data, true);
    }

    /**
     * Flushes content to browser immediately
     *
     * @param string $content
     * @return $this
     */
    public function flush($content = null)
    {
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', '1');
        }

        /**
         * Disable zlib to compress output
         * @link http://www.php.net/manual/en/zlib.configuration.php
         */
        if (!headers_sent() && extension_loaded('zlib')) {
            ini_set('zlib.output_compression', '0');
        }

        /**
         * Turn implicit flush on
         * @link http://www.php.net/manual/en/function.ob-implicit-flush.php
         */
        ob_implicit_flush();

        $this->send($content);

        /**
         * Send blank characters for output_buffering
         * @link http://www.php.net/manual/en/outcontrol.configuration.php
         */
        if ($length = ini_get('output_buffering')) {
            echo str_pad('', $length);
        }

        while (ob_get_level()) {
            ob_end_flush();
        }

        return $this;
    }

    /**
     * Send file download response
     *
     * @param string $file The path of file
     * @param array $downloadOptions The download options
     * @return $this
     * @throws \RuntimeException When file not found
     */
    public function download($file = null, array $downloadOptions = array())
    {
        $o = $downloadOptions + $this->downloadOption;

        if (!is_file($file)) {
            throw new \RuntimeException('File not found', 404);
        }

        $name = $o['filename'] ?: basename($file);
        $name = rawurlencode($name);

        // For IE
        $userAgent = $this->request->getServer('HTTP_USER_AGENT');
        if (preg_match('/MSIE ([\w.]+)/', $userAgent)) {
            $filename = '=' . $name;
        } else {
            $filename = "*=UTF-8''" . $name;
        }

        $this->setHeader(array(
            'Content-Description'       => 'File Transfer',
            'Content-Type'              => $o['type'],
            'Content-Disposition'       => $o['disposition'] . ';filename' . $filename,
            'Content-Transfer-Encoding' => 'binary',
            'Expires'                   => '0',
            'Cache-Control'             => 'must-revalidate',
            'Pragma'                    => 'public',
            'Content-Length'            => filesize($file),
        ));

        // Send headers
        $this->send();

        // Send file content
        readfile($file);

        return $this;
    }
}
