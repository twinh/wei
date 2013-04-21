<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * The widget to detect user browser name and version
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    Server $server The server widget
 */
class Browser extends AbstractWidget
{
    /**
     * Whether in chrome browser
     *
     * @var bool
     */
    public $chrome = false;

    /**
     * Whether in webkit browser
     *
     * @var bool
     */
    public $webkit = false;

    /**
     * Whether in opera browser
     *
     * @var bool
     */
    public $opera = false;

    /**
     * Whether in internet explorer browser
     *
     * @var bool
     */
    public $msie = false;

    /**
     * Whether in firefox browser
     *
     * @var bool
     */
    public $mozilla = false;

    /**
     * Whether in safari browser
     *
     * @var bool
     */
    public $safari = false;

    /**
     * The name of browser
     *
     * @var string
     */
    public $name;

    /**
     * The version of browser
     *
     * @var string
     */
    public $version;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        $this->detect();
    }

    /**
     * Get the name of browser
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->name;
    }

    /**
     * Detect the browser name and version
     *
     * @link http://api.jquery.com/jQuery.browser
     * @copyright Copyright 2012 jQuery Foundation and other contributors
     * @license http://jquery.org/license
     */
    public function detect()
    {
        $ua = strtolower($this->server['HTTP_USER_AGENT']);
        $matches = array();

        preg_match('/(chrome)[ \/]([\w.]+)/', $ua, $matches) ||
            preg_match('/(webkit)[ \/]([\w.]+)/', $ua, $matches) ||
            preg_match('/(opera)(?:.*version|)[ \/]([\w.]+)/', $ua, $matches) ||
            preg_match('/(msie) ([\w.]+)/', $ua, $matches) ||
            false === strpos($ua, 'compatible') && preg_match('/(mozilla)(?:.*? rv:([\w.]+)|)/', $ua, $matches);
        
        if (empty($matches)) {
            $matches = array('', '', 0);
        }

        // Ignore the first element
        list(, $this->name, $this->version) = $matches;

        $this->name && $this->{$this->name} = true;

        // Chrome is Webkit, but Webkit is also Safari.
        if ($this->chrome) {
            $this->webkit = true;
        } elseif ($this->webkit) {
            $this->safari = true;
        }
    }
}
