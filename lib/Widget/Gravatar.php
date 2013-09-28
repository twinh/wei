<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A service that generates a Gravatar URL for a specified email address
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://gravatar.com/site/implement/images/
 */
class Gravatar extends Base
{
    /**
     * The default image type or URL when email do not have a Gravatar image
     *
     * The image type could be 404, mm, identicon, monsterid or wavatar
     *
     * @var string
     */
    protected $default = 'mm';

    /**
     * Whether display Gravatar in HTTPS request
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * The default size of image, from 1px up to 2048px
     *
     * @var int
     */
    protected $size = 80;

    /**
     * The image size for `small` method
     *
     * @var int
     */
    protected $smallSize = 48;

    /**
     * The image size for large
     *
     * @var int
     */
    protected $largeSize = 200;

    /**
     * Generate a Gravatar URL for a specified email address
     *
     * @param string $email The email address
     * @param int $size The image size in pixels
     * @param string $default The image type or URL when email do not have a Gravatar image
     * @param string $rating The image rating
     * @return string A image URL
     * @link http://gravatar.com/site/implement/images/php/
     */
    public function __invoke($email, $size = null, $default = null, $rating = null)
    {
        if ($this->secure) {
            $url = 'https://secure.gravatar.com/avatar/';
        } else {
            $url = 'http://www.gravatar.com/avatar/';
        }

        $url .= md5(strtolower(trim($email)));
        $url .= '?s=' . ($size ?: $this->size);
        $url .= '&d=' . ($default ?: $this->default);

        if ($rating) {
            $url .= '&r=' . $rating;
        }

        return $url;
    }

    /**
     * Generate a large size Gravatar URL for a specified email address
     *
     * @param string $email The email address
     * @param string $default The image type or URL when email do not have a Gravatar image
     * @param string $rating The image rating
     * @return string
     */
    public function large($email, $default = null, $rating = null)
    {
        return $this->__invoke($email, $this->largeSize, $default, $rating);
    }

    /**
     * Generate a small size Gravatar URL for a specified email address
     *
     * @param string $email The email address
     * @param string $default The image type or URL when email do not have a Gravatar image
     * @param string $rating The image rating
     * @return string
     */
    public function small($email, $default = null, $rating = null)
    {
        return $this->__invoke($email, $this->smallSize, $default, $rating);
    }

    /**
     * Sets the default image type or URL
     *
     * @param string $default
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = urlencode($default);
        return $this;
    }
}