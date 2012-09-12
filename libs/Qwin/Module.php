<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

/**
 * Module
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Qwin_Module extends Qwin_Widget
{
    protected $_name;

    public $options = array(
        'key' => 'module',
        'default' => 'index',
    );

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        $options = &$this->options;

        $this->_name = $this->request($options['key'], $options['default']);
    }

    /**
     * Get module object or set module name
     *
     * @param string $name
     * @return string
     */
    public function __invoke($name = null)
    {
        if ($name) {
            $this->_name = (string)$name;
        }
        return $this->_name;
    }

    /**
     * Get module string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_name;
    }

    /**
     * Get module string
     *
     * @return string
     */
    public function toString()
    {
        return $this->_name;
    }
}
