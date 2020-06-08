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
     * @param string|array $name The name of event, or an array that the key is event name and the value is event hanlder
     * @param callback $fn The event handler
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
     * @return string|false The hashed password, or false on error.
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
     * @return array The array of information about the hash.
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
     * @return boolean True if the password needs to be rehashed.
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
     * @return boolean If the password matches the hash
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

class Request
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
     * @param string|array $name The name of event, or an array that the key is event name and the value is event hanlder
     * @param callback $fn The event handler
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
     * @return string|false The hashed password, or false on error.
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
     * @return array The array of information about the hash.
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
     * @return boolean True if the password needs to be rehashed.
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
     * @return boolean If the password matches the hash
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

class Request
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
