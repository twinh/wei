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

class IsAll
{
}

class IsAllOf
{
}

class IsAlnum
{
}

class IsAlpha
{
}

class IsArray
{
}

class IsBetween
{
}

class IsBigInt
{
}

class IsBlank
{
}

class IsBool
{
}

class IsBoolable
{
}

class IsCallback
{
}

class IsChar
{
}

class IsChildren
{
}

class IsChinese
{
}

class IsColor
{
}

class IsContains
{
}

class IsCreditCard
{
}

class IsDate
{
}

class IsDateTime
{
}

class IsDecimal
{
}

class IsDefaultInt
{
}

class IsDigit
{
}

class IsDir
{
}

class IsDivisibleBy
{
}

class IsDoubleByte
{
}

class IsEach
{
}

class IsEmail
{
}

class IsEndsWith
{
}

class IsEqualTo
{
}

class IsExists
{
}

class IsFieldExists
{
}

class IsFile
{
}

class IsFloat
{
}

class IsGreaterThan
{
}

class IsGreaterThanOrEqual
{
}

class IsGt
{
}

class IsGte
{
}

class IsIdCardCn
{
}

class IsIdCardHk
{
}

class IsIdCardMo
{
}

class IsIdCardTw
{
}

class IsIdenticalTo
{
}

class IsImage
{
}

class IsIn
{
}

class IsInt
{
}

class IsIp
{
}

class IsLength
{
}

class IsLessThan
{
}

class IsLessThanOrEqual
{
}

class IsLowercase
{
}

class IsLt
{
}

class IsLte
{
}

class IsLuhn
{
}

class IsMaxAccuracy
{
}

class IsMaxCharLength
{
}

class IsMaxLength
{
}

class IsMediumInt
{
}

class IsMediumText
{
}

class IsMinCharLength
{
}

class IsMinLength
{
}

class IsMobileCn
{
}

class IsNaturalNumber
{
}

class IsNoneOf
{
}

class IsNullType
{
}

class IsNumber
{
}

class IsOneOf
{
}

class IsPassword
{
}

class IsPhone
{
}

class IsPhoneCn
{
}

class IsPlateNumberCn
{
}

class IsPositiveInteger
{
}

class IsPostcodeCn
{
}

class IsPresent
{
}

class IsQQ
{
}

class IsRecordExists
{
}

class IsRegex
{
}

class IsRequired
{
}

class IsSmallInt
{
}

class IsSomeOf
{
}

class IsStartsWith
{
}

class IsString
{
}

class IsText
{
}

class IsTime
{
}

class IsTinyChar
{
}

class IsTinyInt
{
}

class IsTld
{
}

class IsType
{
}

class IsUBigInt
{
}

class IsUDefaultInt
{
}

class IsUMediumInt
{
}

class IsUNumber
{
}

class IsUSmallInt
{
}

class IsUTinyInt
{
}

class IsUppercase
{
}

class IsUrl
{
}

class IsUuid
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
     * @param string|array $name
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

    /**
     * @return $this
     * @see V::defaultOptional
     */
    public static function defaultOptional()
    {
    }

    /**
     * @return $this
     * @see V::defaultRequired
     */
    public static function defaultRequired()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public static function all(array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public static function notAll(array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public static function allOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public static function notAllOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public static function alnum($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public static function notAlnum($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public static function alpha($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public static function notAlpha($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public static function array($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public static function notArray($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public static function between($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public static function notBetween($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public static function bigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public static function notBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public static function blank()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public static function notBlank()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public static function bool($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public static function notBool($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public static function boolable()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public static function notBoolable()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public static function callback($fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public static function notCallback($fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public static function char($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public static function notChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public static function children(V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public static function notChildren(V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public static function chinese($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public static function notChinese($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public static function color($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public static function notColor($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public static function contains($search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public static function notContains($search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public static function creditCard($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public static function notCreditCard($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public static function date($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public static function notDate($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public static function dateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public static function notDateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public static function decimal($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public static function notDecimal($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public static function defaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public static function notDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public static function digit($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public static function notDigit($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public static function dir()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public static function notDir()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public static function divisibleBy($divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public static function notDivisibleBy($divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public static function doubleByte($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public static function notDoubleByte($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public static function each($v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public static function notEach($v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public static function email()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public static function notEmail()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public static function endsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public static function notEndsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public static function equalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public static function notEqualTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public static function exists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public static function notExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public static function fieldExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public static function notFieldExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public static function file($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public static function notFile($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public static function float()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public static function notFloat()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public static function greaterThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public static function notGreaterThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public static function greaterThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public static function notGreaterThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public static function gt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public static function notGt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public static function gte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public static function notGte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public static function idCardCn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public static function notIdCardCn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public static function idCardHk()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public static function notIdCardHk()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public static function idCardMo($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public static function notIdCardMo($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public static function idCardTw()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public static function notIdCardTw()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public static function identicalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public static function notIdenticalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public static function image($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public static function notImage($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public static function in($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public static function notIn($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public static function int($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public static function notInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public static function ip($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public static function notIp($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public static function length($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public static function notLength($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public static function lessThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public static function notLessThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public static function lessThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public static function notLessThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public static function lowercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public static function notLowercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public static function lt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public static function notLt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public static function lte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public static function notLte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public static function luhn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public static function notLuhn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public static function maxAccuracy($max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public static function notMaxAccuracy($max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public static function maxCharLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public static function notMaxCharLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public static function maxLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public static function notMaxLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public static function mediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public static function notMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public static function mediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public static function notMediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public static function minCharLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public static function notMinCharLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public static function minLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public static function notMinLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public static function mobileCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public static function notMobileCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public static function naturalNumber()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public static function notNaturalNumber()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public static function noneOf(array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public static function notNoneOf(array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public static function nullType()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public static function notNullType()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public static function number($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public static function notNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public static function oneOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public static function notOneOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public static function password(array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public static function notPassword(array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public static function phone($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public static function notPhone($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public static function phoneCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public static function notPhoneCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public static function plateNumberCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public static function notPlateNumberCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public static function positiveInteger()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public static function notPositiveInteger()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public static function postcodeCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public static function notPostcodeCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public static function present()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public static function notPresent()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public static function qQ($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public static function notQQ($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public static function recordExists($table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public static function notRecordExists($table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public static function regex($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public static function notRegex($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public static function required($required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public static function notRequired($required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public static function smallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public static function notSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public static function someOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public static function notSomeOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public static function startsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public static function notStartsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public static function string($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public static function notString($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public static function text($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public static function notText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public static function time($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public static function notTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public static function tinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public static function notTinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public static function tinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public static function notTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public static function tld($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public static function notTld($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public static function type($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public static function notType($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public static function uBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public static function notUBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public static function uDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public static function notUDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public static function uMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public static function notUMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public static function uNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public static function notUNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public static function uSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public static function notUSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public static function uTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public static function notUTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public static function uppercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public static function notUppercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public static function url($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public static function notUrl($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public static function uuid($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public static function notUuid($pattern = null)
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

class IsAll
{
}

class IsAllOf
{
}

class IsAlnum
{
}

class IsAlpha
{
}

class IsArray
{
}

class IsBetween
{
}

class IsBigInt
{
}

class IsBlank
{
}

class IsBool
{
}

class IsBoolable
{
}

class IsCallback
{
}

class IsChar
{
}

class IsChildren
{
}

class IsChinese
{
}

class IsColor
{
}

class IsContains
{
}

class IsCreditCard
{
}

class IsDate
{
}

class IsDateTime
{
}

class IsDecimal
{
}

class IsDefaultInt
{
}

class IsDigit
{
}

class IsDir
{
}

class IsDivisibleBy
{
}

class IsDoubleByte
{
}

class IsEach
{
}

class IsEmail
{
}

class IsEndsWith
{
}

class IsEqualTo
{
}

class IsExists
{
}

class IsFieldExists
{
}

class IsFile
{
}

class IsFloat
{
}

class IsGreaterThan
{
}

class IsGreaterThanOrEqual
{
}

class IsGt
{
}

class IsGte
{
}

class IsIdCardCn
{
}

class IsIdCardHk
{
}

class IsIdCardMo
{
}

class IsIdCardTw
{
}

class IsIdenticalTo
{
}

class IsImage
{
}

class IsIn
{
}

class IsInt
{
}

class IsIp
{
}

class IsLength
{
}

class IsLessThan
{
}

class IsLessThanOrEqual
{
}

class IsLowercase
{
}

class IsLt
{
}

class IsLte
{
}

class IsLuhn
{
}

class IsMaxAccuracy
{
}

class IsMaxCharLength
{
}

class IsMaxLength
{
}

class IsMediumInt
{
}

class IsMediumText
{
}

class IsMinCharLength
{
}

class IsMinLength
{
}

class IsMobileCn
{
}

class IsNaturalNumber
{
}

class IsNoneOf
{
}

class IsNullType
{
}

class IsNumber
{
}

class IsOneOf
{
}

class IsPassword
{
}

class IsPhone
{
}

class IsPhoneCn
{
}

class IsPlateNumberCn
{
}

class IsPositiveInteger
{
}

class IsPostcodeCn
{
}

class IsPresent
{
}

class IsQQ
{
}

class IsRecordExists
{
}

class IsRegex
{
}

class IsRequired
{
}

class IsSmallInt
{
}

class IsSomeOf
{
}

class IsStartsWith
{
}

class IsString
{
}

class IsText
{
}

class IsTime
{
}

class IsTinyChar
{
}

class IsTinyInt
{
}

class IsTld
{
}

class IsType
{
}

class IsUBigInt
{
}

class IsUDefaultInt
{
}

class IsUMediumInt
{
}

class IsUNumber
{
}

class IsUSmallInt
{
}

class IsUTinyInt
{
}

class IsUppercase
{
}

class IsUrl
{
}

class IsUuid
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
     * @param string|array $name
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

    /**
     * @return $this
     * @see V::defaultOptional
     */
    public function defaultOptional()
    {
    }

    /**
     * @return $this
     * @see V::defaultRequired
     */
    public function defaultRequired()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public function all(array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAll::__invoke
     */
    public function notAll(array $rules = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public function allOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAllOf::__invoke
     */
    public function notAllOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public function alnum($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlnum::__invoke
     */
    public function notAlnum($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public function alpha($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsAlpha::__invoke
     */
    public function notAlpha($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public function array($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsArray::__invoke
     */
    public function notArray($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public function between($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBetween::__invoke
     */
    public function notBetween($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public function bigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBigInt::__invoke
     */
    public function notBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public function blank()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBlank::__invoke
     */
    public function notBlank()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public function bool($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBool::__invoke
     */
    public function notBool($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public function boolable()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsBoolable::__invoke
     */
    public function notBoolable()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public function callback($fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCallback::__invoke
     */
    public function notCallback($fn = null, $message = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public function char($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChar::__invoke
     */
    public function notChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public function children(V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChildren::__invoke
     */
    public function notChildren(V $v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public function chinese($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsChinese::__invoke
     */
    public function notChinese($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public function color($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsColor::__invoke
     */
    public function notColor($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public function contains($search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsContains::__invoke
     */
    public function notContains($search = null, $regex = false)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public function creditCard($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsCreditCard::__invoke
     */
    public function notCreditCard($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public function date($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDate::__invoke
     */
    public function notDate($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public function dateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDateTime::__invoke
     */
    public function notDateTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public function decimal($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDecimal::__invoke
     */
    public function notDecimal($name = null, string $label = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public function defaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDefaultInt::__invoke
     */
    public function notDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public function digit($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDigit::__invoke
     */
    public function notDigit($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public function dir()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDir::__invoke
     */
    public function notDir()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public function divisibleBy($divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDivisibleBy::__invoke
     */
    public function notDivisibleBy($divisor = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public function doubleByte($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsDoubleByte::__invoke
     */
    public function notDoubleByte($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public function each($v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEach::__invoke
     */
    public function notEach($v = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public function email()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEmail::__invoke
     */
    public function notEmail()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public function endsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEndsWith::__invoke
     */
    public function notEndsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public function equalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsEqualTo::__invoke
     */
    public function notEqualTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public function exists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsExists::__invoke
     */
    public function notExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public function fieldExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFieldExists::__invoke
     */
    public function notFieldExists()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public function file($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFile::__invoke
     */
    public function notFile($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public function float()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsFloat::__invoke
     */
    public function notFloat()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public function greaterThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThan::__invoke
     */
    public function notGreaterThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public function greaterThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGreaterThanOrEqual::__invoke
     */
    public function notGreaterThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public function gt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGt::__invoke
     */
    public function notGt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public function gte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsGte::__invoke
     */
    public function notGte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public function idCardCn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardCn::__invoke
     */
    public function notIdCardCn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public function idCardHk()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardHk::__invoke
     */
    public function notIdCardHk()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public function idCardMo($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardMo::__invoke
     */
    public function notIdCardMo($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public function idCardTw()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdCardTw::__invoke
     */
    public function notIdCardTw()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public function identicalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIdenticalTo::__invoke
     */
    public function notIdenticalTo($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public function image($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsImage::__invoke
     */
    public function notImage($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public function in($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIn::__invoke
     */
    public function notIn($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public function int($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsInt::__invoke
     */
    public function notInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public function ip($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsIp::__invoke
     */
    public function notIp($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public function length($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLength::__invoke
     */
    public function notLength($min = null, $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public function lessThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThan::__invoke
     */
    public function notLessThan($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public function lessThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLessThanOrEqual::__invoke
     */
    public function notLessThanOrEqual($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public function lowercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLowercase::__invoke
     */
    public function notLowercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public function lt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLt::__invoke
     */
    public function notLt($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public function lte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLte::__invoke
     */
    public function notLte($value = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public function luhn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsLuhn::__invoke
     */
    public function notLuhn()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public function maxAccuracy($max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxAccuracy::__invoke
     */
    public function notMaxAccuracy($max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public function maxCharLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxCharLength::__invoke
     */
    public function notMaxCharLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public function maxLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMaxLength::__invoke
     */
    public function notMaxLength($max = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public function mediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumInt::__invoke
     */
    public function notMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public function mediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMediumText::__invoke
     */
    public function notMediumText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public function minCharLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinCharLength::__invoke
     */
    public function notMinCharLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public function minLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMinLength::__invoke
     */
    public function notMinLength($min = null, $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public function mobileCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsMobileCn::__invoke
     */
    public function notMobileCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public function naturalNumber()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNaturalNumber::__invoke
     */
    public function notNaturalNumber()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public function noneOf(array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNoneOf::__invoke
     */
    public function notNoneOf(array $rules = [], $ignore = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public function nullType()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNullType::__invoke
     */
    public function notNullType()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public function number($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsNumber::__invoke
     */
    public function notNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public function oneOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsOneOf::__invoke
     */
    public function notOneOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public function password(array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPassword::__invoke
     */
    public function notPassword(array $options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public function phone($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhone::__invoke
     */
    public function notPhone($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public function phoneCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPhoneCn::__invoke
     */
    public function notPhoneCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public function plateNumberCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPlateNumberCn::__invoke
     */
    public function notPlateNumberCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public function positiveInteger()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPositiveInteger::__invoke
     */
    public function notPositiveInteger()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public function postcodeCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPostcodeCn::__invoke
     */
    public function notPostcodeCn($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public function present()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsPresent::__invoke
     */
    public function notPresent()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public function qQ($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsQQ::__invoke
     */
    public function notQQ($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public function recordExists($table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRecordExists::__invoke
     */
    public function notRecordExists($table = null, $field = 'id')
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public function regex($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRegex::__invoke
     */
    public function notRegex($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public function required($required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsRequired::__invoke
     */
    public function notRequired($required = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public function smallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSmallInt::__invoke
     */
    public function notSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public function someOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsSomeOf::__invoke
     */
    public function notSomeOf(array $rules = [], $atLeast = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public function startsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsStartsWith::__invoke
     */
    public function notStartsWith($findMe = null, $case = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public function string($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsString::__invoke
     */
    public function notString($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public function text($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsText::__invoke
     */
    public function notText($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public function time($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTime::__invoke
     */
    public function notTime($name = null, string $label = null, $format = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public function tinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyChar::__invoke
     */
    public function notTinyChar($name = null, string $label = null, int $minLength = null, int $maxLength = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public function tinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTinyInt::__invoke
     */
    public function notTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public function tld($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsTld::__invoke
     */
    public function notTld($array = [], $strict = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public function type($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsType::__invoke
     */
    public function notType($type = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public function uBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUBigInt::__invoke
     */
    public function notUBigInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public function uDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUDefaultInt::__invoke
     */
    public function notUDefaultInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public function uMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUMediumInt::__invoke
     */
    public function notUMediumInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public function uNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUNumber::__invoke
     */
    public function notUNumber($name = null, string $label = null, int $precision = null, int $scale = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public function uSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUSmallInt::__invoke
     */
    public function notUSmallInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public function uTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUTinyInt::__invoke
     */
    public function notUTinyInt($name = null, string $label = null, int $min = null, int $max = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public function uppercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUppercase::__invoke
     */
    public function notUppercase()
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public function url($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUrl::__invoke
     */
    public function notUrl($options = [])
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public function uuid($pattern = null)
    {
    }

    /**
     * @return $this
     * @see \Wei\IsUuid::__invoke
     */
    public function notUuid($pattern = null)
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
