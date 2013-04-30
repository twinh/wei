<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 */

namespace Widget;

/**
 * @see Widget\WidgetInterface
 */
require_once 'WidgetInterface.php';

/**
 * The base class for all widgets
 *
 * @author   Twin Huang <twinhuang@qq.com>
 * @method   mixed      __invoke(mixed $mixed) The invoke method
 * @property Apc        $apc The PHP APC cache widget 
 * @method   mixed      apc($key, $value = null, $expire = 0) Retrieve or store an item
 * @property App        $app The application widget
 * @method   App        app(array $options = array()) Startup application
 * @property Arr        $arr An util widget provides some useful method to manipulation array
 * @property ArrayCache $arrayCache  A cache widget stored data in PHP array
 * @method   mixed      arrayCache($key, $value = null, $expire = 0) Retrieve or store an item by array cache
 * @property Bicache    $bicache A two-level cache widget
 * @method   mixed      bicache($key, $value = null, $expire = 0) Retrieve or store an item by two-level cache
 * @property Browser    $browser A widget to detect user browser name and version
 * @method   string     browser() Returns the name of browser
 * @property Cache      $cache A cache widget proxy
 * @method   mixed      cache($key, $value = null, $expire = 0) Retrieve or store an item by cache
 * @property Callback   $callback A widget handles WeChat(Weixin) callback message
 * @method   Callback   callback() Start up callback widget and output the matched rule message
 * @property Cookie     $cookie A widget manager the HTTP cookie
 * @method   mixed      cookie($key, $value = null, $options = array()) Get or set cookie
 * @property Db         $db A container widget for Doctrine DBAL connection object
 * @method   \Doctrine\DBAL\Connection db() Retrieve the Doctrine DBAL connection object
 * @property DbCache    $dbCache A database cache widget
 * @method   mixed      dbCache($key, $value = null, $expire = 0) Retrieve or store an item by databse cache
 * @property Download   $download A widget send file download response
 * @method   Download   download($file, $options) Send file download response
 * @property EntityManager $entityManager A container widget for Doctrine ORM entity manager object
 * @method   \Doctrine\ORM\EntityManager entity() Returns the Docrine ORM entity manager
 * @property Env        $env A widget to detect the environment and load configuration by environment
 * @method   string     env() Returns the environment name
 * @property Escape     $escape A widget to escape HTML, javascript, CSS, HTML Attribute and URL for secure output
 * @method   string     escape($string, $type = 'html') Escapes a string by specified type for secure ouput
 * @property EventManager $eventManager The event manager to add, remove and trigger events
 * @property FileCache  $fileCache A file cache widget
 * @method   mixed      fileCache($key, $value = null, $expire = 0) Retrieve or store an item by file
 * @property Flush      $fulsh A widget that flushes the content to browser immediately
 * @method   Flush      flush($content = null, $status = null) Send response content
 * @property Header     $header The response header widget
 * @method   mixed      header($name, $values = null, $replace = true) Get or set HTTP header
 * @property Is         $is The validator manager, use to validate input quickly, create validator and rule validator
 * @method   bool       is($rule = null, $input = null, $options = array()) Validate input by given rule
 * @property Pinyin     $pinyin An util widget that converts Chinese words to phonetic alphabets
 * @method   string     pinyin($word) Converts Chinese words to phonetic alphabets
 * @property Post       $post A widget that handles the HTTP request parameters ($_POST)
 * @method   string     post($name, $default = null) Returns a stringify rquest parameter value
 */
abstract class AbstractWidget implements WidgetInterface
{
    /**
     * The default dependence map
     *
     * @var array
     */
    protected $deps = array();

    /**
     * The widget manager object
     *
     * @var Widget
     */
    protected $widget;

    /**
     * Constructor
     *
     * @param  array        $options The property options
     */
    public function __construct(array $options = array())
    {
        $this->setOption($options);

        if (!isset($this->widget)) {
            $this->widget = Widget::create();
        } elseif (!$this->widget instanceof WidgetInterface) {
            throw new \InvalidArgumentException(sprintf('Option "widget" of class "%s" should be an instance of "WidgetInterface"', get_class($this)));
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value = null)
    {
        // Set options
        if (is_array($name)) {
            if (is_array($value)) {
                $name += array_intersect_key(get_object_vars($this), array_flip($value));
            }
            foreach ($name as $k => $v) {
                $this->setOption($k, $v);
            }
            return $this;
        }
        
        // Append option
        if (isset($name[0]) && '+' == $name[0]) {
            return $this->appendOption(substr($name, 1), $value);
        }
        
        if (method_exists($this, $method = 'set' . $name)) {
            return $this->$method($value);
        } else {
            $this->$name = $value;
            return $this;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOption($name = null)
    {
        // Returns all property options
        if (null === $name) {
            return get_object_vars($this);
        }

        if (method_exists($this, $method = 'get' . $name)) {
            return $this->$method();
        } else {
            return isset($this->$name) ? $this->$name : null;
        }
    }
    
    /**
     * Append property value
     * 
     * @param string $name
     * @param array $value
     * @return AbstractWidget
     */
    public function appendOption($name, array $value) 
    {
        $this->$name = (array)$this->$name + $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->$name = $this->widget->get($name, array(), $this->deps);
    }
}
