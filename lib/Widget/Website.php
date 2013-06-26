<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * A pure configuration widget for your website
 *
 * @author      Twin Huang <twinhuang@qq.com>
 */
class Website extends AbstractWidget
{
    /**
     * The website URL
     * 
     * @var string
     */
    protected $url;
    
    /**
     * The HTML title
     * 
     * @var string
     */
    protected $title;
    
    /**
     * The HTML meta description
     * 
     * @var string
     */
    protected $description;
    
    /**
     * The HTML charset for display only
     * 
     * @var string
     */
    protected $charset = 'utf-8';
    
    /**
     * Returns the value of website configuration
     * 
     * @param string $name The name of configuration(widget option)
     * @return mixed
     */
    public function __invoke($name)
    {
        return $this->getOption($name);
    }
    
    /**
     * Returns the value of website configuration
     * 
     * @param string $name The name of configuration(widget option)
     * @return mixed
     */
    public function get($name)
    {
        return $this->getOption($name);
    }
    
    /**
     * Set the value of website configuration
     * 
     * @param string $name The name of configuration(widget option)
     * @param mixed $value The value of configuration
     * @return Website
     */
    public function set($name, $value)
    {
        return $this->setOption($name, $value);
    }
}
