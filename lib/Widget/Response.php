<?php
/**
 * Widget Library
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * Response
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 * @property    \Widget\Header $header The reponse header
 * @property    \Widget\Cookie $cookie The cookie widget
 * @property    \Widget\Logger $logger The logger widget
 */
class Response extends AbstractWidget
{
    /**
     * Common use http status code and text
     *
     * @var array
     * @todo other status codes
     */
    protected static $codeTexts = array(
        200 => 'OK',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    );

    /**
     * The http version, current is 1.0 or 1.1
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
     * Whether response content has been sent
     *
     * @var bool
     */
    protected $isSent = false;

    /**
     * The response content
     *
     * @var string
     */
    protected $content;

    /**
     * Send response header and content
     *
     * @param  string         $content
     * @param  int            $status
     * @param  array          $options
     * @return \Widget\Response
     */
    public function __invoke($content = '', $status = 200, array $options = array())
    {
        $this->isSent = true;

        $this->content = $content;

        $this->setStatusCode($status);
        
        $this->sendHeader();

        $this->sendContent();

        return $this;
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
     */
    public function sendContent()
    {
        echo $this->content;
    }

    /**
     * @see \Widget\Response::__invoke
     */
    public function send($content = '', $status = 200)
    {
        return $this->__invoke($content, $status);
    }
    
    /**
     * Set the header status code
     *
     * @param  int          $code The status code
     * @param  string|null       $text The status text
     * @return Header
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
     * Set the http version
     *
     * @param  string       $version The http version
     * @return Header
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the http version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Send headers, including http status, raw headers and cookie
     *
     * @return false|Header
     */
    public function sendHeader()
    {
        $file = $line = null;
        $this->logger->debug(sprintf('Header has been at %s:%s', $file, $line));
        if (headers_sent($file, $line)) {
            $this->logger->debug(sprintf('Header has been at %s:%s', $file, $line));

            return false;
        }

        // Send status
        header(sprintf('HTTP/%s %d %s', $this->version, $this->statusCode, $this->statusText));

        // Send headers
        foreach ($this->header->toArray() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        // Send cookie
        $this->cookie->send();

        return $this;
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
