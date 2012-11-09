<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2012 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

use Monolog\Logger;

/**
 * The wrapper for Monolog
 *
 * @package     Widget
 * @author      Twin Huang <twinh@yahoo.cn>
 */
class Log extends WidgetProvider
{
    public $options = array(
        'name' => null,
        'level' => Logger::DEBUG,
        'handlers' => array(),
    );

    /**
     * Monolog logger object
     * 
     * @var Logger 
     */
    protected $log;

    /**
     * Constructor
     * 
     * @param array $options
     * @throws \InvalidArgumentException When log handlder not found
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
        
        // create a log channel
        $log = $this->log = new Logger($this->options['name']);

        // add hanlder
        // todo formatter
        foreach ($this->options['handlers'] as $name => $parameters) {
            switch (true) {
                case is_array($parameters) :
                    $class = '\Monolog\Handler\\' . ucfirst($name) . 'Handler';
                    $log->pushHandler($this->instance($class, $parameters));
                    break;
                
                case $parameters instanceof \Monolog\Handler\HandlerInterface :
                    $log->pushHandler($parameters);
                    break;
                
                default :
                    throw new \InvalidArgumentException(sprintf('Log handler "%s" not found', $name));
            }
        }
    }
    
    /**
     * Get monolog logger object or add a log record
     * 
     * @param string $message The log message
     * @return \Monolog\Logger|boolen Return Logger obejct when $message is null, otherwise return added result
     */
    public function __invoke($message = null)
    {
        return $message ? $this->log->addRecord($this->options['level'], $message) : $this->log;
    }
}
