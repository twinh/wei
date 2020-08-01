<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2020 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

/**
 * A service contains share data
 */
class Share extends Base
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param string $title
     * @return $this
     * @svc
     */
    protected function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     * @svc
     */
    protected function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $image
     * @return $this
     * @svc
     */
    protected function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     * @svc
     */
    protected function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $description
     * @return Share
     * @svc
     */
    protected function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     * @svc
     */
    protected function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $url
     * @return Share
     * @svc
     */
    protected function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     * @svc
     */
    protected function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns share data as JSON
     *
     * @return string
     * @svc
     */
    protected function toJson()
    {
        return json_encode([
            'title' => $this->title,
            'image' => $this->image,
            'description' => $this->description,
            'url' => $this->url,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Returns share data as JSON for WeChat
     *
     * @return string
     * @svc
     */
    protected function toWechatJson()
    {
        return json_encode([
            'title' => $this->title,
            'desc' => $this->description,
            'link' => $this->url,
            'imgUrl' => $this->image,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
