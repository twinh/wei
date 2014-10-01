<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service to generate assets' URL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Asset extends Base
{
    /**
     * The base URL of assets
     *
     * @var string
     */
    protected $baseUrl = '/';

    /**
     * The base concat URL of assets
     *
     * @var string
     */
    protected $concatUrl = '/concat';

    /**
     * The version number append to the URL
     *
     * @var string
     */
    protected $version = '1';

    /**
     * Returns the asset or concat URL by specified file
     *
     * @param string $file
     * @param bool $version Whether append version or not
     * @return string
     */
    public function __invoke($file, $version = true)
    {
        if (is_array($file)) {
            return $this->concat($file);
        } else {
            $url = $this->baseUrl . $file;
            if ($version && $this->version) {
                $url .= ((false === strpos($url, '?')) ? '?' : '&') . 'v=' . $this->version;
            }
            return $url;
        }
    }

    /**
     * Returns the concat URL for list of files
     *
     * @param array $files
     * @return string
     */
    public function concat(array $files)
    {
        return $this->concatUrl . '?b=' . trim($this->baseUrl, '/') .'&f=' . implode(',', $files);
    }

    /**
     * Returns the base url of assets
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Sets the base url of assets
     *
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
}