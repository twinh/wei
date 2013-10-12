<?php
/**
* Widget Framework
*
* @copyright   Copyright (c) 2008-2013 Twin Huang
* @license     http://opensource.org/licenses/mit-license.php MIT License
*/

namespace Widget;

/**
 * A service to manage to assets' URL
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Request $request A service that handles the HTTP request data
 */
class Asset extends Base
{
    /**
     * The base directory of assets
     *
     * @var string
     */
    protected $dir = 'assets';

    /**
     * The version number append to the URL
     *
     * @var string
     */
    protected $version;

    /**
     * Returns the asset URL specified file
     *
     * @param string $file
     * @return string
     */
    protected function __invoke($file)
    {
        $url = $this->request->getBaseUrl() . '/' . $this->dir . '/' . $file;
        if ($this->version) {
            $url .= '?v=' . $this->version;
        }
        return $url;
    }
}