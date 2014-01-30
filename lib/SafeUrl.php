<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * Generate a URL with signature
 *
 * @property \Wei\Request $request
 */
class SafeUrl extends Base
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $expireTime = 60;

    public function __invoke($url, $keys = array())
    {
        return $this->generate($url, $keys);
    }

    public function generate($url, $keys = array())
    {
        $time = time();
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $query);

        $query = $this->filterKeys($query, $keys);
        $query['timestamp'] = $time;

        $flag = $this->generateToken($query);

        return $url . '&timestamp=' . $time . '&flag=' . $flag;
    }

    public function generateToken($array)
    {
        return md5(implode('|', $array) . $this->token);
    }

    protected function filterKeys($query, $keys)
    {
        return $keys ? array_intersect_key($query, array_flip((array)$keys)) : $query;
    }

    public function verify($keys = array())
    {
        // 检查时间是否已经超时
        $time = $this->request->getQuery('timestamp');
        if ($this->expireTime && time() - $time > $this->expireTime) {
            return false;
        }

        // 删除time和token参数
        $query = $this->request->getParameterReference('get');
        $token = $this->request->getQuery('flag');
        unset($query['flag']);

        $timestamp = $query['timestamp'];

        $query = $this->filterKeys($query, $keys);

        $query['timestamp'] = $timestamp;


        if ($this->generateToken($query) == $token) {
            return true;
        } else {
            return false;
        }
    }
}