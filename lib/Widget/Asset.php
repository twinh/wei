<?php
/**
* Widget Framework
*
* @copyright   Copyright (c) 2008-2013 Twin Huang
* @license     http://opensource.org/licenses/mit-license.php MIT License
*/

namespace Widget;

/**
 * A service to generate assets' URL
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
    protected $dir;

    /**
     * The version number append to the URL
     *
     * @var string
     */
    protected $version = '1';

    /**
     * Returns the asset URL by specified file
     *
     * @param string $file
     * @return string
     */
    public function __invoke($file)
    {
        $url = $this->request->getBaseUrl() . '/';
        $this->dir && $url .= $this->dir . '/';
        $url .= $file;

        if ($this->version) {
            $url .= '?v=' . $this->version;
        }
        return $url;
    }
}