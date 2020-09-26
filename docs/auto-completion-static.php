<?php

namespace Wei;

class Apc
{
}

class App
{
}

class ArrayCache
{
}

class Asset
{
}

class Base
{
}

class BaseCache
{
}

class BaseController
{
}

class Bicache
{
}

class Block
{
}

class Cache
{
}

class ClassMap
{
}

class Config
{
}

class Cookie
{
}

class Couchbase
{
}

class Counter
{
}

class Db
{
}

class DbCache
{
}

class E
{
}

class Env
{
}

class Error
{
}

class Event
{
    /**
     * Trigger an event
     *
     * @param  string $name The name of event
     * @param  array $args The arguments pass to the handle
     * @param bool $halt
     * @return array|mixed
     * @see Event::trigger
     */
    public static function trigger($name, $args = [], $halt = false)
    {
    }

    /**
     * Trigger an event until the first non-null response is returned
     *
     * @param string $name
     * @param array $args
     * @return mixed
     * @link https://github.com/laravel/framework/blob/5.1/src/Illuminate/Events/Dispatcher.php#L161
     * @see Event::until
     */
    public static function until($name, $args = [])
    {
    }

    /**
     * Attach a handler to an event
     *
     * @param string|array $name The name of event, or an associative array contains event name and event handler pairs
     * @param callable $fn The event handler
     * @param int $priority The event priority
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return $this
     * @see Event::on
     */
    public static function on($name, $fn = null, $priority = 0)
    {
    }

    /**
     * Remove event handlers by specified name
     *
     * @param string $name The name of event
     * @return $this
     * @see Event::off
     */
    public static function off($name)
    {
    }

    /**
     * Check if has the given name of event handlers
     *
     * @param  string $name
     * @return bool
     * @see Event::has
     */
    public static function has($name)
    {
    }

    /**
     * Returns the name of last triggered event
     *
     * @return string
     * @see Event::getCurName
     */
    public static function getCurName()
    {
    }
}

class FileCache
{
}

class Gravatar
{
}

class Http
{
}

class Lock
{
}

class Logger
{
}

class Memcache
{
}

class Memcached
{
}

class Migration
{
    /**
     * @param OutputInterface $output
     * @return $this
     * @see Migration::setOutput
     */
    public static function setOutput(\Symfony\Component\Console\Output\OutputInterface $output)
    {
    }

    /**
     * @see Migration::migrate
     */
    public static function migrate()
    {
    }

    /**
     * Rollback the last migration or to the specified target migration ID
     *
     * @param array $options
     * @see Migration::rollback
     */
    public static function rollback($options = [])
    {
    }

    /**
     * @param array $options
     * @throws \ReflectionException
     * @throws \Exception
     * @see Migration::create
     */
    public static function create($options)
    {
    }
}

class MongoCache
{
}

class NearCache
{
}

class Password
{
    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @return string|false the hashed password, or false on error
     * @throws \InvalidArgumentException
     * @see Password::hash
     */
    public static function hash($password)
    {
    }

    /**
     * Get information about the password hash. Returns an array of the information
     * that was used to generate the password hash.
     *
     * array(
     *    'algo' => 1,
     *    'algoName' => 'bcrypt',
     *    'options' => array(
     *        'cost' => 10,
     *    ),
     * )
     *
     * @param string $hash The password hash to extract info from
     * @return array the array of information about the hash
     * @see Password::getInfo
     */
    public static function getInfo($hash)
    {
    }

    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash The hash to test
     * @return bool true if the password needs to be rehashed
     * @see Password::needsRehash
     */
    public static function needsRehash($hash)
    {
    }

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash The hash to verify against
     * @return bool If the password matches the hash
     * @see Password::verify
     */
    public static function verify($password, $hash)
    {
    }
}

class PhpError
{
}

class PhpFileCache
{
}

class Pinyin
{
}

class Record
{
}

class Redis
{
}

class Req
{
}

class Request
{
}

class Res
{
}

class Response
{
}

class Ret
{
}

class Router
{
}

class SafeUrl
{
}

class Schema
{
}

class Session
{
}

class Share
{
    /**
     * @param string $title
     * @return $this
     * @see Share::setTitle
     */
    public static function setTitle($title)
    {
    }

    /**
     * @return string
     * @see Share::getTitle
     */
    public static function getTitle()
    {
    }

    /**
     * @param string $image
     * @return $this
     * @see Share::setImage
     */
    public static function setImage($image)
    {
    }

    /**
     * @return string
     * @see Share::getImage
     */
    public static function getImage()
    {
    }

    /**
     * @param string $description
     * @return Share
     * @see Share::setDescription
     */
    public static function setDescription($description)
    {
    }

    /**
     * @return string
     * @see Share::getDescription
     */
    public static function getDescription()
    {
    }

    /**
     * @param string $url
     * @return Share
     * @see Share::setUrl
     */
    public static function setUrl($url)
    {
    }

    /**
     * @return string
     * @see Share::getUrl
     */
    public static function getUrl()
    {
    }

    /**
     * Returns share data as JSON
     *
     * @return string
     * @see Share::toJson
     */
    public static function toJson()
    {
    }

    /**
     * Returns share data as JSON for WeChat
     *
     * @return string
     * @see Share::toWechatJson
     */
    public static function toWechatJson()
    {
    }
}

class Soap
{
}

class StatsD
{
}

class T
{
}

class TagCache
{
}

class Time
{
    /**
     * @return string
     * @see Time::now
     */
    public static function now()
    {
    }

    /**
     * @return string
     * @see Time::today
     */
    public static function today()
    {
    }
}

class Ua
{
}

class Upload
{
}

class Url
{
    /**
     * Generate the URL by specified URL and parameters
     *
     * @param string $url
     * @param array $argsOrParams
     * @param array $params
     * @return string
     * @see Url::to
     */
    public static function to($url = '', $argsOrParams = [], $params = [])
    {
    }
}

class Uuid
{
}

class V
{
    /**
     * Add name for current field
     *
     * @param string $label
     * @return $this
     * @see V::label
     */
    public static function label($label)
    {
    }

    /**
     * Add a new field
     *
     * @param string $name
     * @param string|null $label
     * @return $this
     * @see V::key
     */
    public static function key($name, $label = null)
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see V::when
     */
    public static function when($value, $callback, callable $default = null)
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see V::unless
     */
    public static function unless($value, callable $callback, callable $default = null)
    {
    }
}

class Validate
{
}

class View
{
}

class WeChatApp
{
}

class Wei
{
    /**
     * Set service's configuration
     *
     * @param string|array $name
     * @param mixed $value
     * @return $this
     * @see Wei::setConfig
     */
    public static function setConfig($name, $value = null)
    {
    }

    /**
     * Get services' configuration
     *
     * @param string $name The name of configuration
     * @param mixed $default The default value if configuration not found
     * @return mixed
     * @see Wei::getConfig
     */
    public static function getConfig($name = null, $default = null)
    {
    }
}


namespace Wei\Validator;

class All
{
}

class AllOf
{
}

class Alnum
{
}

class Alpha
{
}

class BaseValidator
{
}

class Between
{
}

class Blank
{
}

class Callback
{
}

class CharLength
{
}

class Chinese
{
}

class Color
{
}

class Contains
{
}

class CreditCard
{
}

class Date
{
}

class DateTime
{
}

class Decimal
{
}

class Digit
{
}

class Dir
{
}

class DivisibleBy
{
}

class DoubleByte
{
}

class Email
{
}

class EndsWith
{
}

class EqualTo
{
}

class Exists
{
}

class FieldExists
{
}

class File
{
}

class GreaterThan
{
}

class GreaterThanOrEqual
{
}

class IdCardCn
{
}

class IdCardHk
{
}

class IdCardMo
{
}

class IdCardTw
{
}

class IdenticalTo
{
}

class Image
{
}

class In
{
}

class Ip
{
}

class Length
{
}

class LessThan
{
}

class LessThanOrEqual
{
}

class Lowercase
{
}

class Luhn
{
}

class MaxLength
{
}

class MinLength
{
}

class MobileCn
{
}

class NaturalNumber
{
}

class NoneOf
{
}

class NullType
{
}

class Number
{
}

class OneOf
{
}

class Password
{
}

class Phone
{
}

class PhoneCn
{
}

class PlateNumberCn
{
}

class PositiveInteger
{
}

class PostcodeCn
{
}

class Present
{
}

class QQ
{
}

class RecordExists
{
}

class Regex
{
}

class Required
{
}

class SomeOf
{
}

class StartsWith
{
}

class Time
{
}

class Tld
{
}

class Type
{
}

class Uppercase
{
}

class Url
{
}

class Uuid
{
}

namespace Wei;

if (0) {
class Apc
{
}

class App
{
}

class ArrayCache
{
}

class Asset
{
}

class Base
{
}

class BaseCache
{
}

class BaseController
{
}

class Bicache
{
}

class Block
{
}

class Cache
{
}

class ClassMap
{
}

class Config
{
}

class Cookie
{
}

class Couchbase
{
}

class Counter
{
}

class Db
{
}

class DbCache
{
}

class E
{
}

class Env
{
}

class Error
{
}

class Event
{
    /**
     * Trigger an event
     *
     * @param  string $name The name of event
     * @param  array $args The arguments pass to the handle
     * @param bool $halt
     * @return array|mixed
     * @see Event::trigger
     */
    public function trigger($name, $args = [], $halt = false)
    {
    }

    /**
     * Trigger an event until the first non-null response is returned
     *
     * @param string $name
     * @param array $args
     * @return mixed
     * @link https://github.com/laravel/framework/blob/5.1/src/Illuminate/Events/Dispatcher.php#L161
     * @see Event::until
     */
    public function until($name, $args = [])
    {
    }

    /**
     * Attach a handler to an event
     *
     * @param string|array $name The name of event, or an associative array contains event name and event handler pairs
     * @param callable $fn The event handler
     * @param int $priority The event priority
     * @throws \InvalidArgumentException when the second argument is not callable
     * @return $this
     * @see Event::on
     */
    public function on($name, $fn = null, $priority = 0)
    {
    }

    /**
     * Remove event handlers by specified name
     *
     * @param string $name The name of event
     * @return $this
     * @see Event::off
     */
    public function off($name)
    {
    }

    /**
     * Check if has the given name of event handlers
     *
     * @param  string $name
     * @return bool
     * @see Event::has
     */
    public function has($name)
    {
    }

    /**
     * Returns the name of last triggered event
     *
     * @return string
     * @see Event::getCurName
     */
    public function getCurName()
    {
    }
}

class FileCache
{
}

class Gravatar
{
}

class Http
{
}

class Lock
{
}

class Logger
{
}

class Memcache
{
}

class Memcached
{
}

class Migration
{
    /**
     * @param OutputInterface $output
     * @return $this
     * @see Migration::setOutput
     */
    public function setOutput(\Symfony\Component\Console\Output\OutputInterface $output)
    {
    }

    /**
     * @see Migration::migrate
     */
    public function migrate()
    {
    }

    /**
     * Rollback the last migration or to the specified target migration ID
     *
     * @param array $options
     * @see Migration::rollback
     */
    public function rollback($options = [])
    {
    }

    /**
     * @param array $options
     * @throws \ReflectionException
     * @throws \Exception
     * @see Migration::create
     */
    public function create($options)
    {
    }
}

class MongoCache
{
}

class NearCache
{
}

class Password
{
    /**
     * Hash the password using the specified algorithm
     *
     * @param string $password The password to hash
     * @return string|false the hashed password, or false on error
     * @throws \InvalidArgumentException
     * @see Password::hash
     */
    public function hash($password)
    {
    }

    /**
     * Get information about the password hash. Returns an array of the information
     * that was used to generate the password hash.
     *
     * array(
     *    'algo' => 1,
     *    'algoName' => 'bcrypt',
     *    'options' => array(
     *        'cost' => 10,
     *    ),
     * )
     *
     * @param string $hash The password hash to extract info from
     * @return array the array of information about the hash
     * @see Password::getInfo
     */
    public function getInfo($hash)
    {
    }

    /**
     * Determine if the password hash needs to be rehashed according to the options provided
     *
     * If the answer is true, after validating the password using password_verify, rehash it.
     *
     * @param string $hash The hash to test
     * @return bool true if the password needs to be rehashed
     * @see Password::needsRehash
     */
    public function needsRehash($hash)
    {
    }

    /**
     * Verify a password against a hash using a timing attack resistant approach
     *
     * @param string $password The password to verify
     * @param string $hash The hash to verify against
     * @return bool If the password matches the hash
     * @see Password::verify
     */
    public function verify($password, $hash)
    {
    }
}

class PhpError
{
}

class PhpFileCache
{
}

class Pinyin
{
}

class Record
{
}

class Redis
{
}

class Req
{
}

class Request
{
}

class Res
{
}

class Response
{
}

class Ret
{
}

class Router
{
}

class SafeUrl
{
}

class Schema
{
}

class Session
{
}

class Share
{
    /**
     * @param string $title
     * @return $this
     * @see Share::setTitle
     */
    public function setTitle($title)
    {
    }

    /**
     * @return string
     * @see Share::getTitle
     */
    public function getTitle()
    {
    }

    /**
     * @param string $image
     * @return $this
     * @see Share::setImage
     */
    public function setImage($image)
    {
    }

    /**
     * @return string
     * @see Share::getImage
     */
    public function getImage()
    {
    }

    /**
     * @param string $description
     * @return Share
     * @see Share::setDescription
     */
    public function setDescription($description)
    {
    }

    /**
     * @return string
     * @see Share::getDescription
     */
    public function getDescription()
    {
    }

    /**
     * @param string $url
     * @return Share
     * @see Share::setUrl
     */
    public function setUrl($url)
    {
    }

    /**
     * @return string
     * @see Share::getUrl
     */
    public function getUrl()
    {
    }

    /**
     * Returns share data as JSON
     *
     * @return string
     * @see Share::toJson
     */
    public function toJson()
    {
    }

    /**
     * Returns share data as JSON for WeChat
     *
     * @return string
     * @see Share::toWechatJson
     */
    public function toWechatJson()
    {
    }
}

class Soap
{
}

class StatsD
{
}

class T
{
}

class TagCache
{
}

class Time
{
    /**
     * @return string
     * @see Time::now
     */
    public function now()
    {
    }

    /**
     * @return string
     * @see Time::today
     */
    public function today()
    {
    }
}

class Ua
{
}

class Upload
{
}

class Url
{
    /**
     * Generate the URL by specified URL and parameters
     *
     * @param string $url
     * @param array $argsOrParams
     * @param array $params
     * @return string
     * @see Url::to
     */
    public function to($url = '', $argsOrParams = [], $params = [])
    {
    }
}

class Uuid
{
}

class V
{
    /**
     * Add name for current field
     *
     * @param string $label
     * @return $this
     * @see V::label
     */
    public function label($label)
    {
    }

    /**
     * Add a new field
     *
     * @param string $name
     * @param string|null $label
     * @return $this
     * @see V::key
     */
    public function key($name, $label = null)
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see V::when
     */
    public function when($value, $callback, callable $default = null)
    {
    }

    /**
     * @param mixed $value
     * @param callable $callback
     * @param callable|null $default
     * @return $this
     * @see V::unless
     */
    public function unless($value, callable $callback, callable $default = null)
    {
    }
}

class Validate
{
}

class View
{
}

class WeChatApp
{
}

class Wei
{
    /**
     * Set service's configuration
     *
     * @param string|array $name
     * @param mixed $value
     * @return $this
     * @see Wei::setConfig
     */
    public function setConfig($name, $value = null)
    {
    }

    /**
     * Get services' configuration
     *
     * @param string $name The name of configuration
     * @param mixed $default The default value if configuration not found
     * @return mixed
     * @see Wei::getConfig
     */
    public function getConfig($name = null, $default = null)
    {
    }
}



}
namespace Wei\Validator;

if (0) {
class All
{
}

class AllOf
{
}

class Alnum
{
}

class Alpha
{
}

class BaseValidator
{
}

class Between
{
}

class Blank
{
}

class Callback
{
}

class CharLength
{
}

class Chinese
{
}

class Color
{
}

class Contains
{
}

class CreditCard
{
}

class Date
{
}

class DateTime
{
}

class Decimal
{
}

class Digit
{
}

class Dir
{
}

class DivisibleBy
{
}

class DoubleByte
{
}

class Email
{
}

class EndsWith
{
}

class EqualTo
{
}

class Exists
{
}

class FieldExists
{
}

class File
{
}

class GreaterThan
{
}

class GreaterThanOrEqual
{
}

class IdCardCn
{
}

class IdCardHk
{
}

class IdCardMo
{
}

class IdCardTw
{
}

class IdenticalTo
{
}

class Image
{
}

class In
{
}

class Ip
{
}

class Length
{
}

class LessThan
{
}

class LessThanOrEqual
{
}

class Lowercase
{
}

class Luhn
{
}

class MaxLength
{
}

class MinLength
{
}

class MobileCn
{
}

class NaturalNumber
{
}

class NoneOf
{
}

class NullType
{
}

class Number
{
}

class OneOf
{
}

class Password
{
}

class Phone
{
}

class PhoneCn
{
}

class PlateNumberCn
{
}

class PositiveInteger
{
}

class PostcodeCn
{
}

class Present
{
}

class QQ
{
}

class RecordExists
{
}

class Regex
{
}

class Required
{
}

class SomeOf
{
}

class StartsWith
{
}

class Time
{
}

class Tld
{
}

class Type
{
}

class Uppercase
{
}

class Url
{
}

class Uuid
{
}
}
