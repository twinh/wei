<?php
/**
 * Wei Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Wei
{
    /**
     * @see Wei\Base
     */
    require_once 'Base.php';

    /**
     * The service container
     *
     * @author      Twin Huang <twinhuang@qq.com>
     *
     * Cache
     * @property Cache      $cache A cache service proxy
     * @method   mixed      cache($key, $value = null, $expire = 0) Retrieve or store an item by cache
     * @property ArrayCache $arrayCache  A cache service that stored data in PHP array
     * @method   mixed      arrayCache($key, $value = null, $expire = 0) Retrieve or store an item by array cache
     * @property Apc        $apc A cache service that stored data in PHP APC
     * @method   mixed      apc($key, $value = null, $expire = 0) Retrieve or store an item
     * @property DbCache    $dbCache A cache service that stored data in databases
     * @method   mixed      dbCache($key, $value = null, $expire = 0) Retrieve or store an item by database cache
     * @property FileCache  $fileCache A cache service that stored data in files
     * @method   mixed      fileCache($key, $value = null, $expire = 0) Retrieve or store an item by file
     * @property Memcache   $memcache A cache service that stored data in Memcache
     * @method   mixed      memcache($key, $value = null, $expire = 0) Retrieve or store an item by Memcache
     * @property Memcached  $memcached A cache service that stored data in Memcached
     * @method   mixed      memcached($key, $value = null, $expire = 0) Retrieve or store an item by Memcached
     * @property MongoCache $mongoCache A cache service that stores data in MongoDB
     * @method   mixed      mongoCache($key, $value = null, $expire = 0) Retrieve or store an item by MongoDB
     * @property Couchbase  $couchbase A cache service base on Couchbase
     * @method   mixed      couchbase($key, $value = null, $expire = 0) Retrieve or store an item by Couchbase
     * @property Redis      $redis A cache service that stores data in Redis
     * @method   mixed      redis($key = null, $value = null, $expire = 0) Retrieve or store an item by Redis
     * @property Bicache    $bicache A two-level cache service
     * @method   mixed      bicache($key, $value = null, $expire = 0) Retrieve or store an item by two-level cache
     *
     * Database
     * @property Db             $db A database service inspired by Doctrine DBAL
     * @method   \Wei\Record    db($table = null) Create a new record object
     *
     * @property Call       $call A object handles HTTP request like jQuery Ajax
     * @method   \Wei\Call  call(array $options) Create a new call object and execute
     *
     * HTTP Request
     * @property Request    $request A service that handles the HTTP request data
     * @method   mixed      request($name, $default = null) Returns a stringify request parameter value
     * @property Cookie     $cookie A object that handles the HTTP request and response cookies
     * @method   mixed      cookie($key, $value = null, $options = array()) Get or set cookie
     * @property Session    $session A object that session parameters ($_SESSION)
     * @method   mixed      session($name, $default = null) Returns a stringify session parameter value
     * @property Ua         $ua A object to detect user OS, device and browser name and version
     * @method   bool       ua() Check if in the specified browser, OS or device
     * @property Upload     $upload A object that handles the uploaded files
     * @method   bool       upload(array $options = array()) Upload a file
     *
     * HTTP Response
     * @property Response       $response A object that handles the HTTP response data
     * @method   \Wei\Response  response($content = null, $status = null) Send response header and content
     *
     * View
     * @property View       $view A object that use to render PHP template
     * @method   string     view($name = null, $vars = array()) Returns view object or render a PHP template
     * @property Asset      $asset A service to generate assets' URL
     * @method   string     asset($file) Returns the asset URL by specified file
     * @property Escape     $escape A object to escape HTML, javascript, CSS, HTML Attribute and URL for secure output
     * @method   string     escape($string, $type = 'html') Escapes a string by specified type for secure output
     *
     * Application
     * @property App            $app An MVC application service
     * @method   \Wei\App       app(array $options = array()) Startup an MVC application
     * @property WeChatApp      $weChatApp A object handles WeChat(WeiXin) callback message
     * @method   \Wei\WeChatApp weChatApp() Start up WeChat application and output the matched rule message
     * @property Router         $router A object that build a simple REST application
     * @method   \Wei\Router    router($pathInfo = null, $method = null) Run the application
     * @property Url            $url A util object to build URL
     * @method   string         url($uri) Build URL by specified uri and parameters
     *
     * Other
     * @property Config     $config A object to manage object configurations
     * @property Counter    $counter A counter service
     * @property Env        $env A object to detect the environment name and load configuration by environment name
     * @method   string     env() Returns the environment name
     * @property Error      $error A object that handles exception and display pretty exception message
     * @method   \Wei\Error error($fn) Attach a handler to exception error
     * @property Gravatar   $gravatar A object that generates a Gravatar URL for a specified email address
     * @method   string     gravatar($email, $size = null, $default = null, $rating = null) Generates a Gravatar URL for a specified email address
     * @property Lock       $lock A service that provide the functionality of exclusive Lock
     * @method   bool       lock($key) Acquire a lock key
     * @property Logger     $logger A simple logger service, which is inspired by Monolog
     * @method   bool       logger($level, $message) Logs with an arbitrary level
     * @property Password   $password A wrapper class for password hashing functions
     * @property Pinyin     $pinyin An util object that converts Chinese words to phonetic alphabets
     * @method   string     pinyin($word) Converts Chinese words to phonetic alphabets
     * @property Uuid       $uuid A util object that generates a RANDOM UUID(universally unique identifier)
     * @method   string     uuid() generates a RANDOM UUID(universally unique identifier)
     * @property T          $t A translator object
     * @method   string     t($message, array $parameters = array()) Translate the message
     *
     * Validation
     * @method   \Wei\Validate   validate(array $option) Create a new validator and validate by specified options
     *
     * Data type and composition
     * @property Validator\Alnum            $isAlnum
     * @method   bool                       isAlnum($input) Check if the input contains letters (a-z) and digits (0-9)
     * @property Validator\Alpha            $isAlpha
     * @method   bool                       isAlpha($input) Check if the input contains only letters (a-z)
     * @property Validator\Blank            $isBlank
     * @method   bool                       isBlank($input) Check if the input is blank
     * @property Validator\Decimal          $isDecimal
     * @method   bool                       isDecimal($input) Check if the input is decimal
     * @property Validator\Digit            $isDigit
     * @method   bool                       isDigit($input) Check if the input contains only digits (0-9)
     * @property Validator\DivisibleBy      $isDivisibleBy
     * @method   bool                       isDivisibleBy($input, $divisor) Check if the input could be divisible by specified divisor
     * @property Validator\DoubleByte       $isDoubleByte
     * @method   bool                       isDoubleByte($input) Check if the input contains only double characters
     * @property Validator\Present          $isPresent
     * @method   bool                       isPresent($input) Check if the input is empty
     * @property Validator\EndsWith         $isEndsWith
     * @method   bool                       isEndsWith($input, $findMe, $case = false) Check if the input is ends with specified string
     * @property Validator\In               $isIn
     * @method   bool                       isIn($input, array $array, $strict = false) Check if the input is in specified array
     * @property Validator\Lowercase        $isLowercase
     * @method   bool                       isLowercase($input) Check if the input is lowercase
     * @property Validator\Luhn             $isLuhn
     * @method   bool                       isLuhn($input) Check if the input is valid by the Luhn algorithm
     * @property Validator\NaturalNumber    $isNaturalNumber
     * @method   bool                       isNaturalNumber($input) Check if the input is a natural number (integer that greater than or equals 0)
     * @property Validator\Null             $isNull
     * @method   bool                       isNull($input) Check if the input is null
     * @property Validator\Number           $isNumber
     * @method   bool                       isNumber($input) Check if the input is number
     * @property Validator\PositiveInteger  $isPositiveInteger
     * @method   bool                       isPositiveInteger($input) Check if the input is a positive integer (integer that greater than 0)
     * @property Validator\Regex            $isRegex
     * @method   bool                       isRegex($input, $pattern) Check if the input is valid by specified regular expression
     * @property Validator\StartsWith       $isStartsWith
     * @method   bool                       isStartsWith($input, $findMe, $case = false) Check if the input is starts with specified string
     * @property Validator\Type             $isType
     * @method   bool                       isType($input, $type) Check if the type of input is equals specified type name
     * @property Validator\Uppercase        $isUppercase
     * @method   bool                       isUppercase($input) Check if the input is uppercase
     *
     * Length
     * @property Validator\Length       $isLength
     * @method   bool                   isLength($input, $length, $max = null) Check if the length (or size) of input is equals specified length or in specified length range
     * @property Validator\CharLength   $isCharLength
     * @method   bool                   isCharLength($input, $length) Check if the characters length of input is equals specified length
     * @property Validator\MinLength    $isMinLength
     * @method   bool                   isMinLength($input, $min) Check if the length (or size) of input is greater than specified length
     * @property Validator\MaxLength    $isMaxLength
     * @method   bool                   isMaxLength($input, $max) Check if the length (or size) of input is lower than specified length
     *
     * Comparison
     * @property Validator\EqualTo              $isEqualTo
     * @method   bool                           isEqualTo($input, $value) Check if the input is equals to (==) the specified value
     * @property Validator\IdenticalTo          $identicalTo
     * @method   bool                           isIdenticalTo($input, $value) Check if the input is equals to (==) the specified value
     * @property Validator\GreaterThan          $isGreaterThan
     * @method   bool                           isGreaterThan($input, $value) Check if the input is greater than (>=) the specified value
     * @property Validator\GreaterThanOrEqual   $isGreaterThanOrEqual
     * @method   bool                           isGreaterThanOrEqual($input, $value) Check if the input is greater than or equal to (>=) the specified value
     * @property Validator\LessThan             $isLessThan
     * @method   bool                           isLessThan($input, $value) Check if the input is less than (<) the specified value
     * @property Validator\LessThanOrEqual      $isLessThanOrEqual
     * @method   bool                           isLessThanOrEqual($input, $value) Check if the input is less than or equal to (<=) the specified value
     * @property Validator\Between              $isBetween
     * @method   bool                           isBetween($input, $min, $max) Check if the input is between the specified minimum and maximum value
     *
     * Date and time
     * @property Validator\Date         $isDate
     * @method   bool                   isDate($input, $format = 'Y-m-d') Check if the input is a valid date
     * @property Validator\DateTime     $isDateTime
     * @method   bool                   isDateTime($input, $format = null) Check if the input is a valid datetime
     * @property Validator\Time         $isTime
     * @method   bool                   isTime($input, $format = 'H:i:s') Check if the input is a valid time
     *
     * File and directory
     * @property Validator\Dir          $isDir
     * @method   bool                   isDir($input) Check if the input is existing directory
     * @property Validator\Exists       $isExists
     * @method   bool                   isExists($input) Check if the input is existing file or directory
     * @property Validator\File         $isFile
     * @method   bool                   isFile($input, array $options) Check if the input is valid file
     * @property Validator\Image        $isImage
     * @method   bool                   isImage($input, array $options) Check if the input is valid image
     *
     * Network
     * @property Validator\Email        $isEmail
     * @method   bool                   isEmail($input) Check if the input is valid email address
     * @property Validator\Ip           $isIp
     * @method   bool                   isIp($input, array $options = array()) Check if the input is valid IP address
     * @property Validator\Tld          $isTld
     * @method   bool                   isTld($input) Check if the input is a valid top-level domain
     * @property Validator\Url          $isUrl
     * @method   bool                   isUrl($input, array $options = array()) Check if the input is valid URL address
     * @property Validator\Uuid         $isUuid
     * @method   bool                   isUuid($input) Check if the input is valid UUID(v4)
     *
     * Region
     * @property Validator\CreditCard   $isCreditCard
     * @method   bool                   isCreditCard($input, $type = null) Check if the input is valid credit card number
     * @property Validator\Phone        $isPhone
     * @method   bool                   isPhone($input) Check if the input is valid phone number, contains only digit, +, - and spaces
     * @property Validator\Chinese      $isChinese
     * @method   bool                   isChinese($input) Check if the input contains only Chinese characters
     * @property Validator\IdCardCn     $isIdCardCn
     * @method   bool                   isIdCardCn($input) Check if the input is valid Chinese identity card
     * @property Validator\IdCardHk     $isIdCardHk
     * @method   bool                   isIdCardHk($input) Check if the input is valid Hong Kong identity card
     * @property Validator\IdCardMo     $isIdCardMo
     * @method   bool                   isIdCardMo($input) Check if the input is valid Macau identity card
     * @property Validator\IdCardTw     $isIdCardTw
     * @method   bool                   isIdCardTw($input) Check if the input is valid Taiwan identity card
     * @property Validator\PhoneCn      $isPhoneCn
     * @method   bool                   isPhoneCn($input) Check if the input is valid Chinese phone number
     * @property Validator\PlateNumberCn $isPlateNumberCn
     * @method   bool                   isPlateNumberCn($input) Check if the input is valid Chinese plate number
     * @property Validator\PostcodeCn   $isPostcodeCn
     * @method   bool                   isPostcodeCn($input) Check if the input is valid Chinese postcode
     * @property Validator\QQ           $isQQ
     * @method   bool                   isQQ($input) Check if the input is valid QQ number
     * @property Validator\MobileCn     $isMobileCn
     * @method   bool                   isMobileCn($input) Check if the input is valid Chinese mobile number
     *
     * Group
     * @property Validator\AllOf        $isAllOf
     * @method   bool                   isAllOf($input, array $rules) Check if the input is valid by all of the rules
     * @property Validator\NoneOf       $isNoneOf
     * @method   bool                   isNoneOf($input, array $rules) Check if the input is NOT valid by all of specified rules
     * @property Validator\OneOf        $isOneOf
     * @method   bool                   isOneOf($input, array $rules) Check if the input is valid by any of the rules
     * @property Validator\SomeOf       $isSomeOf
     * @method   bool                   isSomeOf($input, array $rules, $atLeast) Check if the input is valid by specified number of the rules
     *
     * Others
     * @property Validator\RecordExists $isRecordExists
     * @method   bool                   isRecordExists($input, $table, $field = 'id') Check if the input is existing table record
     * @property Validator\All          $isAll
     * @method   bool                   isAll($input, array $rules) Check if all of the element in the input is valid by all specified rules
     * @property Validator\Callback     $isCallback
     * @method   bool                   isCallback($input, \Closure $fn, $message = null) Check if the input is valid by specified callback
     * @property Validator\Color        $isColor
     * @method   bool                   isColor($input) Check if the input is valid Hex color
     * @property Validator\Password     $isPassword
     * @method   bool                   isPassword($input, array $options = array()) Check if the input password is secure enough
     */
    class Wei extends Base
    {
        /**
         * Version
         */
        const VERSION = '0.9.8-RC1';

        /**
         * The configurations for all objects
         *
         * @var array
         */
        protected $configs = array();

        /**
         * Whether in debug mode or not
         *
         * @var bool
         */
        protected $debug = true;

        /**
         * The PHP configuration options that will be set when the service container constructing
         *
         * @var array
         * @see http://www.php.net/manual/en/ini.php
         * @see http://www.php.net/manual/en/function.ini-set.php
         */
        protected $inis = array();

        /**
         * Whether enable class autoload or not
         *
         * @var bool
         */
        protected $autoload = true;

        /**
         * The directories for autoload
         *
         * @var array
         */
        protected $autoloadMap = array();

        /**
         * The service name to class name map
         *
         * @var array
         */
        protected $aliases = array();

        /**
         * The service provider map
         *
         * @var array
         */
        protected $providers = array();

        /**
         * The import configuration
         *
         * Format:
         * array(
         *     array(
         *         'dir' => 'lib/Wei/Validator',
         *         'namespace' => 'Wei\Validator',
         *         'format' => 'is%s',
         *         'autoload' => false
         *     ),
         *     array(
         *         'dir' => 'src/MyProject/Wei',
         *         'namespace' => 'MyProject\Wei',
         *         'format' => '%s',
         *         'autoload' => true
         *     )
         * );
         * @var array
         */
        protected $import = array();

        /**
         * The callback executes *before* service constructed
         *
         * @var callable
         */
        protected $beforeConstruct;

        /**
         * The callback executes *after* service constructed
         *
         * @var callable
         */
        protected $afterConstruct;

        /**
         * The services that will be instanced after service container constructed
         *
         * @var array
         */
        protected $preload = array();

        /**
         * An array contains the instanced services
         *
         * @var Base[]
         */
        protected $services = array();

        /**
         * The current service container
         *
         * @var Wei
         */
        protected static $container;

        /**
         * Instance service container
         *
         * @param array $config
         */
        public function __construct(array $config = array())
        {
            // Set configurations for all services
            $this->setConfig($config);

            $this->set('wei', $this);
            $this->wei = $this;

            // Set all options
            $options = get_object_vars($this);
            if (isset($this->configs['wei'])) {
                $options = array_merge($options, $this->configs['wei']);
            }
            $this->setOption($options);
        }

        /**
         * Get the service container instance
         *
         * @param array $config                 The array or file configuration
         * @return $this
         * @throws \InvalidArgumentException    When the configuration parameter is not array or file
         */
        public static function getContainer($config = array())
        {
            // Most of time, it's called after instanced and without any arguments
            if (!$config && static::$container) {
                return static::$container;
            }

            switch (true) {
                case is_array($config):
                    break;

                case is_string($config) && file_exists($config):
                    $config = (array) require $config;
                    break;

                default:
                    throw new \InvalidArgumentException('Configuration should be array or file', 1010);
            }

            if (!isset(static::$container)) {
                static::$container = new static($config);
            } else {
                static::$container->setConfig($config);
            }
            return static::$container;
        }

        /**
         * Set the service container
         *
         * @param Wei $container
         */
        public static function setContainer(Wei $container = null)
        {
            static::$container = $container;
        }

        /**
         * Autoload the PSR-0 class
         *
         * @param  string $class the name of the class
         * @return bool
         */
        public function autoload($class)
        {
            $class = strtr($class, array('_' => DIRECTORY_SEPARATOR, '\\' => DIRECTORY_SEPARATOR)) . '.php';

            foreach ($this->autoloadMap as $prefix => $dir) {
                if (isset($prefix[0]) && $prefix[0] == '\\' && 0 === strpos($class, ltrim($prefix, '\\'))) {
                    $file = $dir . DIRECTORY_SEPARATOR . substr($class, strlen($prefix));
                    if (file_exists($file)) {
                        require_once $file;
                        return true;
                    }
                }

                // Allow empty class prefix
                if (!$prefix || 0 === strpos($class, $prefix)) {
                    if (file_exists($file = $dir . DIRECTORY_SEPARATOR . $class)) {
                        require_once $file;
                        return true;
                    }
                }
            }

            return false;
        }

        /**
         * Set service's configuration
         *
         * @param string|array $name
         * @param mixed $value
         * @return $this
         */
        public function setConfig($name, $value = null)
        {
            // Set array configurations
            if (is_array($name)) {
                foreach ($name as $key => $value) {
                    $this->setConfig($key, $value);
                }
                return $this;
            }

            // Set one configuration
            $names = explode(':', $name);
            $first = $names[0];
            $configs = &$this->configs;

            foreach ($names as $name) {
                if (!is_array($configs)) {
                    $configs = array();
                }
                if (!isset($configs[$name])) {
                    $configs[$name] = array();
                }
                $configs = &$configs[$name];
            }

            // Merge only first child node
            if (is_array($configs) && is_array($value)) {
                $configs = $value + $configs;
            } else {
                $configs = $value;
            }

            /**
             * Automatically create dependence map when configuration key contains "."
             *
             * $this->configs = array(
             *    'mysql.db' => array(),
             * );
             * =>
             * $this->providers['mysqlDb'] = 'mysql.db';
             */
            if (false !== strpos($first, '.')) {
                $parts = explode('.', $first, 2);
                $serviceName = $parts[0] . ucfirst($parts[1]);
                if (!isset($this->providers[$serviceName])) {
                    $this->providers[$serviceName] = $first;
                }
            }

            // Set options for existing service
            if (isset($this->services[$first])) {
                $this->services[$first]->setOption($value);
            }

            return $this;
        }

        /**
         * Get services' configuration
         *
         * @param string $name The name of configuration
         * @param mixed $default The default value if configuration not found
         * @return mixed
         */
        public function getConfig($name = null, $default = null)
        {
            if (is_null($name)) {
                return $this->configs;
            }

            if (false === strpos($name, ':')) {
                return isset($this->configs[$name]) ? $this->configs[$name] : $default;
            }

            $configs = &$this->configs;
            foreach (explode(':', $name) as $key) {
                if (is_array($configs) && isset($configs[$key])) {
                    $configs = &$configs[$key];
                } else {
                    return $default;
                }
            }
            return $configs;
        }

        /**
         * Get a service and call its "__invoke" method
         *
         * @param string $name The name of the service
         * @param array $args The arguments for "__invoke" method
         * @param array $providers The service providers map
         * @return mixed
         */
        public function invoke($name, array $args = array(), $providers = array())
        {
            $service = $this->get($name, $providers);
            return call_user_func_array(array($service, '__invoke'), $args);
        }

        /**
         * Get a service
         *
         * @param string $name The name of the service, without class prefix "Wei\"
         * @param array $options The option properties for service
         * @param array $providers The dependent configuration
         * @throws \BadMethodCallException
         * @return Base
         */
        public function get($name, array $options = array(), array $providers = array())
        {
            // Resolve the service name in dependent configuration
            if (isset($providers[$name])) {
                $name = $providers[$name];
            }

            if (isset($this->providers[$name])) {
                $name = $this->providers[$name];
            }

            if (isset($this->services[$name])) {
                return $this->services[$name];
            }

            // Resolve the real service name and the config name($full)
            $full = $name;
            if (false !== ($pos = strpos($name, '.'))) {
                $name = substr($name, $pos + 1);
            }

            // Get the service class and instance
            $class = $this->getClass($name);
            if (class_exists($class)) {
                // Trigger the before construct callback
                $this->beforeConstruct && call_user_func($this->beforeConstruct, $this, $full, $name);

                // Load the service configuration and make sure "wei" option at first
                $options = array('wei' => $this) + $options + (array)$this->getConfig($full);

                $this->services[$full] = new $class($options);

                // Trigger the after construct callback
                $this->afterConstruct && call_user_func($this->afterConstruct, $this, $full, $name, $this->services[$full]);

                return $this->services[$full];
            }

            // Build the error message
            $traces = debug_backtrace();

            // $wei->notFound()
            if (isset($traces[3]) && $name == $traces[3]['function']) {
                // For call_user_func/call_user_func_array
                $file = isset($traces[3]['file']) ? $traces[3]['file'] : $traces[4]['file'];
                $line = isset($traces[3]['line']) ? $traces[3]['line'] : $traces[4]['line'];
                throw new \BadMethodCallException(sprintf('Method "%s->%2$s" or object "%s" (class "%s") not found, called in file "%s" at line %s', $traces[3]['class'], $traces[3]['function'], $class, $file, $line), 1011);
            // $wei->notFound
            } elseif (isset($traces[1]) && '__get' == $traces[1]['function'] && $name == $traces[1]['args'][0]) {
                throw new \BadMethodCallException(sprintf('Property or object "%s" (class "%s") not found, called in file "%s" at line %s', $traces[1]['args'][0], $class, $traces[1]['file'], $traces[1]['line']), 1012);
            // $wei->get('notFound');
            } else {
                throw new \BadMethodCallException(sprintf('Property or method "%s" not found', $name), 1013);
            }
        }

        /**
         * Returns all instanced services
         *
         * @return Base[]
         */
        public function getServices()
        {
            return $this->services;
        }

        /**
         * Check if the service is instanced
         *
         * @param string $name The name of service
         * @return bool
         */
        public function isInstanced($name)
        {
            return isset($this->services[$name]);
        }

        /**
         * Initialize a new instance of service, with the specified name
         *
         * @param string $name The name of the service
         * @param array $options The option properties for service
         * @param array $providers The dependent configuration
         * @return Base The instanced service
         */
        public function newInstance($name, array $options = array(), array $providers = array())
        {
            $name .= uniqid() . '.' . $name;
            return $this->wei->get($name, $options, $providers);
        }

        /**
         * Add a service to the service container
         *
         * @param string $name The name of service
         * @param Base $service The object of service
         * @return $this
         */
        public function set($name, Base $service)
        {
            $this->services[$name] = $service;
            return $this;
        }

        /**
         * Remove the service by the specified name
         *
         * @param  string  $name The name of service
         * @return bool
         */
        public function remove($name)
        {
            if (isset($this->services[$name])) {
                unset($this->services[$name]);
                return true;
            }
            return false;
        }

        /**
         * Get the service class by the given name
         *
         * @param string $name The name of service
         * @return string
         */
        public function getClass($name)
        {
            if (isset($this->aliases[$name])) {
                $class = $this->aliases[$name];
            } elseif ('is' == substr($name, 0, 2) && strlen($name) > 2) {
                $class = 'Wei\Validator\\' . ucfirst(substr($name, 2));
            } else {
                $class = 'Wei\\' . ucfirst($name);
            }
            return $class;
        }

        /**
         * Check if the service exists by the specified name, if the service exists,
         * returns the full class name, else return false
         *
         * @param string $name The name of service
         * @return string|false
         */
        public function has($name)
        {
            $class = $this->getClass($name);
            return class_exists($class) ? $class : false;
        }

        /**
         * Set debug flag
         *
         * @param $bool
         * @return $this
         */
        public function setDebug($bool)
        {
            $this->debug = (bool) $bool;
            return $this;
        }

        /**
         * Whether in debug mode or not
         *
         * @return bool
         */
        public function isDebug()
        {
            return $this->debug;
        }

        /**
         * Whether enable autoload or not
         *
         * @param bool $enable
         * @return $this
         */
        public function setAutoload($enable)
        {
            $this->autoload = (bool) $enable;

            call_user_func(
                $enable ? 'spl_autoload_register' : 'spl_autoload_unregister',
                array($this, 'autoload')
            );

            return $this;
        }

        /**
         * Set autoload directories for autoload method
         *
         * @param array $map
         * @return $this
         */
        public function setAutoloadMap(array $map)
        {
            // Append the "\Wei" namespace to avoid class not found error
            $map['\Wei'] = __DIR__;
            $this->autoloadMap = array_map('realpath', $map);
            return $this;
        }

        /**
         * Sets the value of PHP configuration options
         *
         * @param array $inis
         * @return $this
         */
        public function setInis(array $inis)
        {
            $this->inis = $inis + $this->inis;
            foreach ($inis as $key => $value) {
                ini_set($key, $value);
            }
            return $this;
        }

        /**
         * Merge service aliases
         *
         * @param array $aliases
         * @return $this
         */
        public function setAliases(array $aliases)
        {
            $this->aliases = $aliases + $this->aliases;
            return $this;
        }

        /**
         * Set service alias
         *
         * @param string $name The name of service
         * @param string $class The class that the service reference to
         * @return $this
         */
        public function setAlias($name, $class)
        {
            $this->aliases[$name] = $class;
            return $this;
        }

        /**
         * Add a service to the service container
         *
         * @param string $name The name of service
         * @param Base $service The service service
         * @return $this
         */
        public function __set($name, Base $service)
        {
            return $this->set($name, $service);
        }

        /**
         * Import classes in the specified directory as services
         *
         * @param string $dir The directory to search class
         * @param string $namespace The prefix namespace of the class
         * @param null $format The service name format, eg 'is%s'
         * @param bool $autoload Whether add namespace and directory to `autoloadMap` or nor
         * @throws \InvalidArgumentException When the first parameter is not a directory
         * @return $this
         */
        public function import($dir, $namespace, $format = null, $autoload = false)
        {
            if (!is_dir($dir)) {
                throw new \InvalidArgumentException(sprintf('Fail to import classes from non-exists directory "%s"', $dir), 1014);
            }

            if ($autoload) {
                $this->autoloadMap[$namespace] = dirname($dir);
            }

            $files = glob($dir . '/*.php') ?: array();
            foreach ($files as $file) {
                $class = substr(basename($file), 0, -4);
                $name = $format ? sprintf($format, $class) : $class;
                $this->aliases[lcfirst($name)] = $namespace . '\\' . $class;
            }

            return $this;
        }

        /**
         * Set import services
         *
         * @param array $import
         * @return $this
         */
        protected function setImport(array $import = array())
        {
            $this->import = $import + $this->import;
            foreach ($import as $option) {
                $option += array(
                    'dir' => null,
                    'namespace' => null,
                    'format' => null,
                    'autoload' => false,
                );
                $this->import($option['dir'], $option['namespace'], $option['format'], $option['autoload']);
            }
        }

        /**
         * Merge the dependence map
         *
         * @param array $providers
         */
        protected function setProviders(array $providers)
        {
            $this->providers = $providers + $this->providers;
        }

        /**
         * Instance preload services
         *
         * @param array $preload
         */
        protected function setPreload(array $preload)
        {
            $this->preload = array_merge($this->preload, $preload);
            foreach ($preload as $service) {
                $this->set($service, $this->get($service));
            }
        }
    }
}

/**
 * Define function in global namespace
 */
namespace
{
    /**
     * Get the service container instance
     *
     * @return Wei\Wei
     */
    function wei()
    {
        return call_user_func_array(array('Wei\Wei', 'getContainer'), func_get_args());
    }

    /**
     * Get the service container instance
     *
     * @return Wei\Wei
     * @deprecated Remove in 0.9.9
     */
    function widget()
    {
        return call_user_func_array(array('Wei\Wei', 'getContainer'), func_get_args());
    }
}
