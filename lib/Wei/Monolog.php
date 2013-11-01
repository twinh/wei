<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei;

use Monolog\Logger as MonologLogger;

/**
 * A wrapper wei for Monolog
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @link        https://github.com/Seldaek/monolog
 */
class Monolog extends Base
{
    /**
     * The name of channel
     *
     * @var string
     */
    protected $name = 'wei';

    /**
     * The default log level
     *
     * @var int
     */
    protected $level = MonologLogger::DEBUG;

    /**
     * The monolog handlers
     *
     * @var array
     */
    protected $handlers = array(
        'stream' => array(
            'stream' => 'log/wei.log',
            'level' => MonologLogger::DEBUG,
        ),
    );

    /**
     * The Monolog logger object
     *
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param array $options
     * @throws \InvalidArgumentException When log handlder not found
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);

        // Create a logger channel
        $logger = $this->logger = new MonologLogger($this->name);

        // Add handlers
        foreach ($this->handlers as $name => $parameters) {
            switch (true) {
                case is_array($parameters) :
                    $class = '\Monolog\Handler\\' . ucfirst($name) . 'Handler';
                    $logger->pushHandler($this->createInstance($class, $parameters));
                    break;

                case $parameters instanceof \Monolog\Handler\HandlerInterface :
                    $logger->pushHandler($parameters);
                    break;

                default :
                    throw new \InvalidArgumentException(sprintf('Log handler "%s" not found', $name));
            }
        }
    }

    /**
     * Get monolog logger object or add a log record
     *
     * @param string $level The log level
     * @param string $message The log message
     * @param array $context
     * @return \Monolog\Logger|bool Returns Logger object when $message is null, otherwise returns log result
     */
    public function __invoke($level = null, $message = null, array $context = array())
    {
        !isset($level) && $level = $this->level;

        return $message ? $this->logger->addRecord($level, $message, $context) : $this->logger;
    }

    /**
     * Instance a class
     *
     * @param  string       $class the name of class
     * @param  array        $args  the parameters to be passed to the class constructor as an array.
     * @return false|object false when class not found or a instance of the class
     * @internal
     */
    public function createInstance($class, $args = array())
    {
        if (!class_exists($class)) {
            return false;
        }

        // get class arguments
        !is_array($args) && $args = array($args);

        // instance according to the argument number
        switch (count($args)) {
            case 0:
                $object = new $class;
                break;

            case 1:
                $object = new $class(current($args));
                break;

            case 2:
                $object = new $class(current($args), next($args));
                break;

            case 3:
                $object = new $class(current($args), next($args), next($args));
                break;

            default:
                if (method_exists($class, '__construct') || method_exists($class, $class)) {
                    $reflection = new \ReflectionClass($class);
                    $object = $reflection->newInstanceArgs($args);
                } else {
                    $object = new $class;
                }
        }

        return $object;
    }
}
