<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * The base class for all widgets
 *
 * @author   Twin Huang <twinhuang@qq.com>
 *
 * Cache
 * @property Cache      $cache A cache widget proxy
 * @method   mixed      cache($key, $value = null, $expire = 0) Retrieve or store an item by cache
 * @property ArrayCache $arrayCache  A cache widget that stored data in PHP array
 * @method   mixed      arrayCache($key, $value = null, $expire = 0) Retrieve or store an item by array cache
 * @property Apc        $apc A cache widget that stored data in PHP APC
 * @method   mixed      apc($key, $value = null, $expire = 0) Retrieve or store an item
 * @property DbCache    $dbCache A cache widget that stored data in databases
 * @method   mixed      dbCache($key, $value = null, $expire = 0) Retrieve or store an item by database cache
 * @property FileCache  $fileCache A cache widget that stored data in files
 * @method   mixed      fileCache($key, $value = null, $expire = 0) Retrieve or store an item by file
 * @property Memcache   $memcache A cache widget that stored data in Memcache
 * @method   mixed      memcache($key, $value = null, $expire = 0) Retrieve or store an item by Memcache
 * @property Memcached  $memcached A cache widget that stored data in Memcached
 * @method   mixed      memcached($key, $value = null, $expire = 0) Retrieve or store an item by Memcached
 * @property MongoCache $mongoCache A cache widget that stores data in MongoDB
 * @method   mixed      mongoCache($key, $value = null, $expire = 0) Retrieve or store an item by MongoDB
 * @property Couchbase  $couchbase A cache widget base on Couchbase
 * @method   mixed      couchbase($key, $value = null, $expire = 0) Retrieve or store an item by Couchbase
 * @property Redis      $redis A cache widget that stores data in Redis
 * @method   mixed      redis($key, $value = null, $expire = 0) Retrieve or store an item by Redis
 * @property Bicache    $bicache A two-level cache widget
 * @method   mixed      bicache($key, $value = null, $expire = 0) Retrieve or store an item by two-level cache
 *
 * Database
 * @property Db                      $db A database widget
 * @method   \Widget\Db\QueryBuilder db($table = null) Create a new instance of a SQL query builder with specified table name
 *
 * @property Call           $call A widget handles HTTP request like jQuery Ajax
 * @method   \Widget\Call   call(array $options) Create a new call object and execute
 *
 * Validation
 * @method   \Widget\Validate   validate(array $option) Create a new validator and validate by specified options
 * @property Is                 $is The validator manager, use to validate input quickly, create validator
 * @method   bool               is($rule = null, $input = null, $options = array()) Validate input by given rule
 *
 * HTTP Request
 * @property Request    $request A widget that handles the HTTP request data
 * @method   mixed      request($name, $default = null) Returns a stringify request parameter value
 * @property Cookie     $cookie A widget that handles the HTTP request and response cookies
 * @method   mixed      cookie($key, $value = null, $options = array()) Get or set cookie
 * @property Session    $session A widget that session parameters ($_SESSION)
 * @method   mixed      session($name, $default = null) Returns a stringify session parameter value
 * @property Ua         $ua A widget to detect user OS, device and browser name and version
 * @method   bool       ua() Check if in the specified browser, OS or device
 * @property Upload     $upload A widget that handles the uploaded files
 * @method   bool       upload(array $options = array()) Upload a file
 *
 * HTTP Response
 * @property Response           $response A widget that handles the HTTP response data
 * @method   \Widget\Response   response($content = null, $status = null) Send response header and content
 * @property Download           $download A widget send file download response
 * @method   \Widget\Download   download($file, $options) Send file download response
 * @property Flush              $flush A widget that flushes the content to browser immediately
 * @method   \Widget\Flush      flush($content = null, $status = null) Send response content
 * @property Json               $json A widget to response json
 * @method   \Widget\Json       json($message = null, $code = 0, array $append = array(), $jsonp = false) Send JSON(P) response
 * @property Redirect           $redirect A widget that send a redirect response
 * @method   \Widget\Redirect   redirect($url = null, $status = 302, array $options = array()) Send a redirect response
 *
 * View
 * @property Escape             $escape A widget to escape HTML, javascript, CSS, HTML Attribute and URL for secure output
 * @method   string             escape($string, $type = 'html') Escapes a string by specified type for secure output
 * @property View               $view A widget that use to render PHP template
 * @method   string             view($name = null, $vars = array()) Returns view widget or render a PHP template
 *
 * Application
 * @property WeChatApp          $weChatApp A widget handles WeChat(WeiXin) callback message
 * @method   \Widget\WeChatApp  weChatApp() Start up WeChat application and output the matched rule message
 * @property App                $app The application widget
 * @method   \Widget\App        app(array $options = array()) Startup application
 * @property Router             $router A widget that build a simple REST application
 * @method   \Widget\Router     router($pathInfo = null, $method = null) Run the application
 * @property Url                $url A util widget to build URL
 * @method   string             url($uri) Build URL by specified uri and parameters
 *
 * Other
 * @property Arr            $arr An util widget provides some useful method to manipulation array
 * @property Env            $env A widget to detect the environment name and load configuration by environment name
 * @method   string         env() Returns the environment name
 * @property Error          $error A widget that handles exception and display pretty exception message
 * @method   \Widget\Error  error($fn) Attach a handler to exception error
 * @property Event          $event The event manager to add, remove and trigger events
 * @property Gravatar       $gravatar A widget that generates a Gravatar URL for a specified email address
 * @method   string         gravatar($email, $size = null, $default = null, $rating = null) Generates a Gravatar URL for a specified email address
 * @property Logger         $logger A simple logger widget, which is base on the Monolog
 * @method   bool           logger($level, $message) Logs with an arbitrary level
 * @property Map            $map A widget that handles key-value map data
 * @method   mixed          map($name, $key = null) Get map data by specified name
 * @property Pinyin         $pinyin An util widget that converts Chinese words to phonetic alphabets
 * @method   string         pinyin($word) Converts Chinese words to phonetic alphabets
 * @property Uuid           $uuid A util widget that generates a RANDOM UUID(universally unique identifier)
 * @method   string         uuid() generates a RANDOM UUID(universally unique identifier)
 * @property Config         $config A pure configuration widget for your application
 * @method   string         config($name) Returns the value of your configuration
 * @property T              $t A translator widget
 * @method   string         t($message, array $parameters = array()) Translate the message
 *
 * Third Party
 * @property Monolog        $monolog A wrapper widget for Monolog
 * @method   bool           monolog($level = null, $message = null, array $context = array()) Get monolog logger object or add a log record
 */
abstract class Base
{
    /**
     * The default dependence map
     *
     * @var array
     */
    protected $deps = array();

    /**
     * The widget container object
     *
     * @var Widget
     */
    protected $widget;

    /**
     * Constructor
     *
     * @param array $options The property options
     * @throws \InvalidArgumentException When option "widget" is not an instance of "Widget\Widget"
     */
    public function __construct(array $options = array())
    {
        $this->setOption($options);

        if (!isset($this->widget)) {
            $this->widget = Widget::create();
        } elseif (!$this->widget instanceof Widget) {
            throw new \InvalidArgumentException(sprintf('Option "widget" of class "%s" should be an instance of "Widget\Widget"', get_class($this)));
        }
    }

    /**
     * Set option property value
     *
     * @param string|array $name
     * @param mixed $value
     * @return Base
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
            $name = substr($name, 1);
            $this->$name = (array)$this->$name + $value;
            return $this;
        }

        if (method_exists($this, $method = 'set' . $name)) {
            return $this->$method($value);
        } else {
            $this->$name = $value;
            return $this;
        }
    }

    /**
     * Returns option property value
     *
     * @param string $name The name of property
     * @return mixed
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
     * Invoke the widget by the given name
     *
     * @param string $name The name of widget
     * @param array $args The arguments for widget's __invoke method
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }

    /**
     * Get the widget object by the given name
     *
     * @param  string $name The name of widget
     * @return Base
     */
    public function __get($name)
    {
        return $this->$name = $this->widget->get($name, array(), $this->deps);
    }
}
