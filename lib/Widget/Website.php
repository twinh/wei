<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * A pure configuration widget for your website
 *
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Website extends AbstractWidget
{
    /**
     * The website url
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
     * Returns the value of property
     * 
     * @param string $name The name of property
     * @return mixed
     */
    public function get($name)
    {
        return $this->getOption($name);
    }
}