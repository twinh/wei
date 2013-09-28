<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A service to response JSON or JSONP format string
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Json extends Response
{
    /**
     * The key name of code
     *
     * @var string
     */
    protected $code = 'code';

    /**
     * The key name of message
     *
     * @var string
     */
    protected $message = 'message';

    /**
     * Response JSON or JSONP format string
     *
     * @param string $message The message node value
     * @param int $code The code node value
     * @param array $append The array append to the root node
     * @param bool $jsonp Whether allow response json format on demand
     * @return Json
     */
    public function __invoke($message = null, $code = 1, array $append = array(), $jsonp = false)
    {
        $result = json_encode(array(
            $this->code => $code,
            $this->message => $message,
        ) + $append);

        if ($jsonp && $name = $this->request['callback']) {
            $this->setHeader('Content-Type', 'application/javascript');
            $jsonp = $this->escape->js((string)$name);
            $result = $jsonp . '(' . $result . ')';
        } else {
            $this->setHeader('Content-Type', 'application/json');
        }

        return parent::send($result);
    }
}
