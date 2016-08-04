<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2016 Twin Huang
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
     * Returns the Minify concat URL for list of files
     *
     * @param array $files
     * @return string
     * @link https://github.com/mrclay/minify
     */
    public function concat(array $files)
    {
        $baseUrl = trim($this->baseUrl, '/');
        $url = $this->concatUrl . '?f=' . implode(',', $files);
        return $baseUrl ? $url . '&b=' . trim($this->baseUrl, '/') : $url;
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

    /**
     * Generate url for falling back from cdn to local host when asset loaded fail
     *
     * @param string $url
     * @return string
     */
    public function fallback($url)
    {
        $parts = parse_url($url);
        return $parts['path'] . ($parts['query'] ? '?' . $parts['query'] : '');
    }
}