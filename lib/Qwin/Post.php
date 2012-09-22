<?php
/**
 * Qwin Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Qwin;

/**
 * Post
 *
 * @package     Qwin
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Post extends ArrayWidget
{
    public $options = array(
        'parameters' => false,
    );
    
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        if (is_array($this->options['parameters'])) {
            $this->data = $this->options['parameters'];
        } else {
            $this->data = $_POST;
        }
    }

    /**
     * Return post parameter
     * 
     * @param string $name the parameter name
     * @param mixed $default the default parameter value if the parameter does not exist
     * @return mixed the parameter value
     */
    public function __invoke($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }
}
