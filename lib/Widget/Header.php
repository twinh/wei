<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A widget that handles the HTTP response headers
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Response $response A widget that handles the HTTP response data
 */
class Header extends AbstractWidget
{
    /**
     * The variable to store array
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->data = &$this->response->getHeaderReference();
    }

    /**
     * Get or set HTTP header
     *
     * @param  string|array $name    The header name or an associative array
     *                               that the key is header name and the value
     *                               is header value
     * @param  string|array $values  The header values, for set method only
     * @param  bool         $replace Whether replace the exists values or not, for set method only
     * @return mixed
     */
    public function __invoke($name, $values = null, $replace = true)
    {
        if (1 == func_num_args()) {
            return $this->get($name);
        } else {
            return $this->set($name, $values, $replace);
        }
    }

    /**
     * Set the header string
     *
     * @param  string       $name    The header name
     * @param  string|array $values  The header values
     * @param  bool         $replace Whether replace the exists values or not
     * @return Header
     */
    public function set($name, $values = null, $replace = true)
    {
        return $this->response->setHeader($name, $values, $replace);
    }

    /**
     * Get the header string
     *
     * @param  string $name    The header name
     * @param  mixed  $default The default value
     * @param  bool   $first   return the first element or the whole header values
     * @return mixed
     */
    public function get($name, $default = null, $first = true)
    {
        return $this->response->getHeader($name, $default, $first);
    }

    /**
     * Remove header by specified name
     *
     * @param string $name The header name
     * @return Header
     */
    public function remove($name)
    {
        $this->response->removeHeader($name);

        return $this;
    }

    /**
     * Returns response header as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->response->getHeaderString();
    }
}
