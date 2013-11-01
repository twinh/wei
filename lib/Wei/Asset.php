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
        $url = $this->baseUrl . $file;
        if ($this->version) {
            $url .= '?v=' . $this->version;
        }
        return $url;
    }
}
