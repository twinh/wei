<?php

/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2015 Twin Huang
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
     * The token to generate the signature
     *
     * @var string
     */
    protected $token = 'wei';

    /**
     * The expire seconds of signature
     *
     * @var int
     */
    protected $expireTime = 60;

    /**
     * Generate signature for specified parameter names
     *
     * @var array
     */
    protected $params = array();

    /**
     * Generate a URL with signature, alias of generate method
     *
     * @param string $url
     * @return string
     */
    public function __invoke($url)
    {
        return $this->generate($url);
    }

    /**
     * Generate a URL with signature
     *
     * @param string $url
     * @return string
     */
    public function generate($url)
    {
        $time = time();
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $query);

        $query = $this->filterKeys($query, $this->params);
        $query['timestamp'] = $time;

        $signature = $this->generateToken($query);

        return $url . '&timestamp=' . $time . '&signature=' . $signature;
    }

    /**
     * Verify whether the URL signature is OK
     *
     * @return bool
     */
    public function verify()
    {
        // Check if time is expired
        $time = $this->request->getQuery('timestamp');
        if ($this->expireTime && time() - $time > $this->expireTime) {
            return false;
        }

        // Remove signature parameters
        $query = $this->request->getParameterReference('get');
        $token = $this->request->getQuery('signature');
        unset($query['signature']);

        $timestamp = $query['timestamp'];

        $query = $this->filterKeys($query, $this->params);

        $query['timestamp'] = $timestamp;

        return $this->generateToken($query) == $token;
    }

    /**
     * Set parameter names to generate signature
     *
     * @param string|array $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = (array)$params;
        return $this;
    }

    /**
     * Generate signature by specified array
     *
     * @param array $array
     * @return string
     */
    protected function generateToken(array $array)
    {
        return md5(implode('|', $array) . $this->token);
    }

    /**
     * Removes array element by keys
     *
     * @param string $query
     * @param array $keys
     * @return array
     */
    protected function filterKeys($query, $keys)
    {
        return $keys ? array_intersect_key($query, array_flip((array)$keys)) : $query;
    }
}