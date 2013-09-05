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
 * @method   mixed      redis($key = null, $value = null, $expire = 0) Retrieve or store an item by Redis
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
 * @property Config         $config A widget to manage widget configurations
 * @property Env            $env A widget to detect the environment name and load configuration by environment name
 * @method   string         env() Returns the environment name
 * @property Error          $error A widget that handles exception and display pretty exception message
 * @method   \Widget\Error  error($fn) Attach a handler to exception error
 * @property Gravatar       $gravatar A widget that generates a Gravatar URL for a specified email address
 * @method   string         gravatar($email, $size = null, $default = null, $rating = null) Generates a Gravatar URL for a specified email address
 * @property Logger         $logger A simple logger widget, which is base on the Monolog
 * @method   bool           logger($level, $message) Logs with an arbitrary level
 * @property Pinyin         $pinyin An util widget that converts Chinese words to phonetic alphabets
 * @method   string         pinyin($word) Converts Chinese words to phonetic alphabets
 * @property Uuid           $uuid A util widget that generates a RANDOM UUID(universally unique identifier)
 * @method   string         uuid() generates a RANDOM UUID(universally unique identifier)
 * @property T              $t A translator widget
 * @method   string         t($message, array $parameters = array()) Translate the message
 *
 * Third Party
 * @property Monolog        $monolog A wrapper widget for Monolog
 * @method   bool           monolog($level = null, $message = null, array $context = array()) Get monolog logger object or add a log record
 *
 * Validation
 * @method   \Widget\Validate   validate(array $option) Create a new validator and validate by specified options
 * @property Is                 $is The validator manager, use to validate input quickly, create validator
 * @method   bool               is($rule = null, $input = null, $options = array()) Validate input by given rule
 *
 * Data type and composition
 * @property \Widget\Validator\Alnum        $isAlnum
 * @method   bool                           isAlnum($input) Check if the input contains letters (a-z) and digits (0-9)
 * @property \Widget\Validator\Alpha        $isAlpha
 * @method   bool                           isAlpha($input) Check if the input contains only letters (a-z)
 * @property \Widget\Validator\Blank        $isBlank
 * @method   bool                           isBlank($input) Check if the input is blank
 * @property \Widget\Validator\Decimal      $isDecimal
 * @method   bool                           isDecimal($input) Check if the input is decimal
 * @property \Widget\Validator\Digit        $isDigit
 * @method   bool                           isDigit($input) Check if the input contains only digits (0-9)
 * @property \Widget\Validator\DivisibleBy  $isDivisibleBy
 * @method   bool                           isDivisibleBy($input, $divisor) Check if the input could be divisible by specified divisor
 * @property \Widget\Validator\DoubleByte   $isDoubleByte
 * @method   bool                           isDoubleByte($input) Check if the input contains only double characters
 * @property \Widget\Validator\EmptyValue   $isEmpty
 * @method   bool                           isEmpty($input) Check if the input is empty
 * @property \Widget\Validator\EndsWith     $isEndsWith
 * @method   bool                           isEndsWith($input, $findMe, $case = false) Check if the input is ends with specified string
 * @property \Widget\Validator\In           $isIn
 * @method   bool                           isIn($input, array $array, $strict = false) Check if the input is in specified array
 * @property \Widget\Validator\Lowercase    $isLowercase
 * @method   bool                           isLowercase($input) Check if the input is lowercase
 * @property \Widget\Validator\Null         $isNull
 * @method   bool                           isNull($input) Check if the input is null
 * @property \Widget\Validator\Number       $isNumber
 * @method   bool                           isNumber($input) Check if the input is number
 * @property \Widget\Validator\Regex        $isRegex
 * @method   bool                           isRegex($input) Check if the input is valid by specified regular expression
 * @property \Widget\Validator\StartsWith   $isStartsWith
 * @method   bool                           isStartsWith($input, $findMe, $case = false) Check if the input is starts with specified string
 * @property \Widget\Validator\Type         $isType
 * @method   bool                           isType($input, $type) Check if the type of input is equals specified type name
 * @property \Widget\Validator\Uppercase    $isUppercase
 * @method   bool                           isUppercase($input) Check if the input is uppercase
 *
 * Length
 * @property \Widget\Validator\Length       $isLength
 * @method   bool                           isLength($input, $length, $max = null) Check if the length (or size) of input is equals specified length or in specified length range
 * @property \Widget\Validator\CharLength   $isCharLength
 * @method   bool                           isCharLength($input, $length) Check if the characters length of input is equals specified length
 * @property \Widget\Validator\MinLength    $isMinLength
 * @method   bool                           isMinLength($input, $min) Check if the length (or size) of input is greater than specified length
 * @property \Widget\Validator\MaxLength    $isMaxLength
 * @method   bool                           isMaxLength($input, $max) Check if the length (or size) of input is lower than specified length
 *
 * Date and time
 * @property \Widget\Validator\Date         $isDate
 * @method   bool                           isDate($input, $format = 'Y-m-d') Check if the input is a valid date
 * @property \Widget\Validator\DateTime     $isDateTime
 * @method   bool                           isDateTime($input, $format = null) Check if the input is a valid date
 * @property \Widget\Validator\Time         $isTime
 * @method   bool                           isTime($input, $format = 'H:i:s') Check if the input is a valid time
 *
 * File and directory
 * @property \Widget\Validator\Dir          $isDir
 * @method   bool                           isDir($input) Check if the input is existing directory
 * @property \Widget\Validator\Exists       $isExists
 * @method   bool                           isExists($input) Check if the input is existing file or directory
 * @property \Widget\Validator\File         $isFile
 * @method   bool                           isFile($input, array $options) Check if the input is valid file
 * @property \Widget\Validator\Image        $isImage
 * @method   bool                           isImage($input, array $options) Check if the input is valid image
 *
 * Network
 * @property \Widget\Validator\Email        $isEmail
 * @method   bool                           isEmail($input) Check if the input is valid email address
 * @property \Widget\Validator\Ip           $isIp
 * @method   bool                           isIp($input) Check if the input is valid IP address
 * @property \Widget\Validator\Tld          $isTld
 * @method   bool                           isTld($input) Check if the input is a valid top-level domain
 * @property \Widget\Validator\Url          $isUrl
 * @method   bool                           isUrl($input) Check if the input is valid URL address
 * @property \Widget\Validator\Uuid         $isUuid
 * @method   bool                           isUuid($input) Check if the input is valid UUID(v4)
 *
 * Region
 * @property \Widget\Validator\CreditCard   $isCreditCard
 * @method   bool                           isCreditCard($input) Check if the input is valid credit card number
 * @property \Widget\Validator\Chinese      $isChinese
 * @method   bool                           isChinese($input) Check if the input contains only Chinese characters
 * @property \Widget\Validator\IdCardCn     $isIdCardCn
 * @method   bool                           isIdCardCn($input) Check if the input is valid Chinese identity card
 * @property \Widget\Validator\IdCardHk     $isIdCardHk
 * @method   bool                           isIdCardHk($input) Check if the input is valid Hong Kong identity card
 * @property \Widget\Validator\IdCardMo     $isIdCardMo
 * @method   bool                           isIdCardMo($input) Check if the input is valid Macau identity card
 * @property \Widget\Validator\IdCardTw     $isIdCardTw
 * @method   bool                           isIdCardTw($input) Check if the input is valid Taiwan identity card
 * @property \Widget\Validator\PhoneCn      $isPhoneCn
 * @method   bool                           isPhoneCn($input) Check if the input is valid Chinese phone number
 * @property \Widget\Validator\PostcodeCn   $isPostcodeCn
 * @method   bool                           isPostcodeCn($input) Check if the input is valid Chinese postcode
 * @property \Widget\Validator\QQ           $isQQ
 * @method   bool                           isQQ($input) Check if the input is valid QQ number
 * @property \Widget\Validator\MobileCn     $isMobileCn
 * @method   bool                           isMobileCn($input) Check if the input is valid Chinese mobile number
 *
 * Group
 * @property \Widget\Validator\AllOf        $isAllOf
 * @method   bool                           isAllOf($input) Check if the input is valid by all of the rules
 * @property \Widget\Validator\NoneOf       $isNoneOf
 * @method   bool                           isNoneOf($input) Check if the input is NOT valid by all of specified rules
 * @property \Widget\Validator\OneOf        $isOneOf
 * @method   bool                           isOneOf($input) Check if the input is valid by any of the rules
 * @property \Widget\Validator\SomeOf       $isSomeOf
 * @method   bool                           isSomeOf($input) Check if the input is valid by specified number of the rules
 *
 * Comparison
 * @property \Widget\Validator\EqualTo              $isEqualTo
 * @method   bool                                   isEqualTo($input, $value) Check if the input is equals to (==) the specified value
 * @property \Widget\Validator\IdenticalTo          $identicalTo
 * @method   bool                                   isIdenticalTo($input, $value) Check if the input is equals to (==) the specified value
 * @property \Widget\Validator\GreaterThan          $isGreaterThan
 * @method   bool                                   isGreaterThan($input, $value) Check if the input is greater than (>=) the specified value
 * @property \Widget\Validator\GreaterThanOrEqual   $isGreaterThanOrEqual
 * @method   bool                                   isGreaterThanOrEqual($input, $value) Check if the input is greater than or equal to (>=) the specified value
 * @property \Widget\Validator\LessThan             $isLessThan
 * @method   bool                                   isLessThan($input, $value) Check if the input is less than (<) the specified value
 * @property \Widget\Validator\LessThanOrEqual      $isLessThanOrEqual
 * @method   bool                                   isLessThanOrEqual($input, $value) Check if the input is less than or equal to (<=) the specified value
 * @property \Widget\Validator\Between              $isBetween
 * @method   bool                                   isBetween($input, $min, $max) Check if the input is between the specified minimum and maximum value
 */
abstract class Base
{
    /**
     * The widget name dependence map
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
