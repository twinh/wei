<?php

/**
 * @property    Wei\Apc $apc A cache service that stored data in PHP APC
 * @method      mixed apc($key, $value = null, $expire = 0) Retrieve or store an item
 */
class ApcMixin {
}

/**
 * @property    Wei\App $app A service to build an MVC application
 * @method      Wei\App app($options = []) Startup an MVC application
 */
class AppMixin {
}

/**
 * @property    Wei\ArrayCache $arrayCache A cache service that stored data in PHP array
 * @method      mixed arrayCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class ArrayCacheMixin {
}

/**
 * @property    Wei\Asset $asset A service to generate assets' URL
 * @method      string asset($file, $version = true) Returns the asset or concat URL by specified file
 */
class AssetMixin {
}

/**
 * @property    Wei\Base $base The base class for all services
 */
class BaseMixin {
}

/**
 * @property    Wei\BaseCache $baseCache The base class for cache services
 * @method      mixed baseCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class BaseCacheMixin {
}

/**
 * @property    Wei\BaseController $baseController The base class for controller
 */
class BaseControllerMixin {
}

/**
 * @property    Wei\Bicache $bicache A two-level cache service
 * @method      mixed bicache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class BicacheMixin {
}

/**
 * @property    Wei\Block $block A service that stores view content for template inheritance
 * @method      string block($name, $type = 'append') Start to capture a block
 */
class BlockMixin {
}

/**
 * @property    Wei\Cache $cache A cache service proxy
 * @method      mixed cache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class CacheMixin {
}

/**
 * @property    Wei\ClassMap $classMap Generate class map from specified directory and pattern
 */
class ClassMapMixin {
}

/**
 * @property    Wei\Config $config A service to manage service container configurations
 */
class ConfigMixin {
}

/**
 * @property    Wei\Cookie $cookie A service that handles the HTTP request and response cookies
 * @method      mixed cookie($key, $value = null, $options = []) Get request cookie or set response cookie
 */
class CookieMixin {
}

/**
 * @property    Wei\Couchbase $couchbase A cache service that stored data in Couchbase
 * @method      mixed couchbase($key, $value = null, $expire = 0) Retrieve or store an item
 */
class CouchbaseMixin {
}

/**
 * @property    Wei\Counter $counter A counter service
 */
class CounterMixin {
}

/**
 * @property    Wei\Db $db A database service inspired by Doctrine DBAL
 * @method      Record db($table = null) Create a new instance of a SQL query builder with specified table name
 */
class DbMixin {
}

/**
 * @property    Wei\DbCache $dbCache A cache service that stored data in databases
 * @method      mixed dbCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class DbCacheMixin {
}

/**
 * @property    Wei\E $e Context specific methods for use in secure output escaping
 * @method      string e($string, $type = 'html') Escapes a string by specified type for secure output
 */
class EMixin {
}

/**
 * @property    Wei\Env $env A service to detect the environment name and load configuration by environment name
 * @method      string env() Returns the environment name
 */
class EnvMixin {
}

/**
 * @property    Wei\Error $error A service that handles exception and display pretty exception message
 * @method      Wei\Error error($fn) Attach a handler to exception error
 */
class ErrorMixin {
}

/**
 * @property    Wei\Event $event An event dispatch service
 */
class EventMixin {
}

/**
 * @property    Wei\FileCache $fileCache A cache service that stored data in files
 * @method      mixed fileCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class FileCacheMixin {
}

/**
 * @property    Wei\Gravatar $gravatar A service that generates a Gravatar URL for a specified email address
 * @method      string gravatar($email, $size = null, $default = null, $rating = null) Generate a Gravatar URL for a specified email address
 */
class GravatarMixin {
}

/**
 * @property    Wei\Http $http An HTTP client that inspired by jQuery Ajax
 * @method      Wei\Http http($url = null, $options = []) Create a new HTTP object and execute
 */
class HttpMixin {
}

/**
 * @property    Wei\Lock $lock A service that provide the functionality of exclusive Lock
 * @method      bool lock($key, $expire = null) Acquire a lock key
 */
class LockMixin {
}

/**
 * @property    Wei\Logger $logger A logger service, which is inspired by Monolog
 * @method      bool logger($level, $message, $context = []) Logs with an arbitrary level
 */
class LoggerMixin {
}

/**
 * @property    Wei\Memcache $memcache A cache service that stored data in Memcache
 * @method      mixed memcache($key = null, $value = null, $expire = 0) Returns the memcache object, retrieve or store an item
 */
class MemcacheMixin {
}

/**
 * @property    Wei\Memcached $memcached A cache service that stored data in Memcached
 * @method      mixed memcached($key = null, $value = null, $expire = 0) Returns the memcached object, retrieve or store an item
 */
class MemcachedMixin {
}

/**
 * @property    Wei\Migration $migration Migration
 */
class MigrationMixin {
}

/**
 * @property    Wei\MongoCache $mongoCache A cache service that stores data in MongoDB
 * @method      mixed mongoCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class MongoCacheMixin {
}

/**
 * @property    Wei\NearCache $nearCache A kind of two level cache, including a "front cache" and a "back cache"
 * @method      mixed nearCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class NearCacheMixin {
}

/**
 * @property    Wei\Password $password A wrapper class for password hashing functions
 */
class PasswordMixin {
}

/**
 * @property    Wei\PhpError $phpError A wrapper for PHP Error
 * @method      \php_error\ErrorHandler phpError() Returns PHP Error ErrorHandler object
 */
class PhpErrorMixin {
}

/**
 * @property    Wei\PhpFileCache $phpFileCache A cache service that stored data as PHP variables in files
 * @method      mixed phpFileCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class PhpFileCacheMixin {
}

/**
 * @property    Wei\Pinyin $pinyin An util wei that converts Chinese words to phonetic alphabets
 * @method      string pinyin($word) Converts Chinese words to phonetic alphabets
 */
class PinyinMixin {
}

/**
 * @property    Wei\Record $record A base database record class
 */
class RecordMixin {
}

/**
 * @property    Wei\Redis $redis A cache service that stored data in Redis
 * @method      mixed redis($key = null, $value = null, $expire = 0) Returns the redis object, retrieve or store an item
 */
class RedisMixin {
}

/**
 * @property    Wei\Req $request A service that handles the HTTP request data
 * @method      string|null request($name, $default = '') Returns a *stringify* or user defined($default) parameter value
 */
class RequestMixin {
}

/**
 * @property    Wei\Res $response A service that handles the HTTP response data
 * @method      Wei\Res response($content = null, $status = null) Send response header and content
 */
class ResponseMixin {
}

/**
 * @property    Wei\Ret $ret A service that use to build operation result
 * @method      array ret($message, $code = 1, $type = 'success') Return operation result data
 */
class RetMixin {
}

/**
 * @property    Wei\RetTrait $retTrait Add common usage result functions to service
 */
class RetTraitMixin {
}

/**
 * @property    Wei\Router $router A service that parse the URL to request data
 */
class RouterMixin {
}

/**
 * @property    Wei\SafeUrl $safeUrl Generate a URL with signature
 * @method      string safeUrl($url) Generate a URL with signature, alias of generate method
 */
class SafeUrlMixin {
}

/**
 * @property    Wei\Schema $schema A MySQL schema builder
 */
class SchemaMixin {
}

/**
 * @property    Wei\ServiceTrait $serviceTrait Add the ability to get and call other services for the class
 */
class ServiceTraitMixin {
}

/**
 * @property    Wei\Session $session A service that handles session data ($_SESSION)
 * @method      mixed session($key, $value = null) Get or set session
 */
class SessionMixin {
}

/**
 * @property    Wei\Share $share A service contains share data
 */
class ShareMixin {
}

/**
 * @property    Wei\Soap $soap A Soap client that works like HTTP service
 * @method      Wei\Soap soap($options = []) Create a new Soap service and execute
 */
class SoapMixin {
}

/**
 * @property    Wei\StatsD $statsD Sends statistics to the stats daemon over UDP
 */
class StatsDMixin {
}

/**
 * @property    Wei\T $t A translator wei
 * @method      string t($message, $parameters = []) Translate a message
 */
class TMixin {
}

/**
 * @property    Wei\TagCache $tagCache A cache service that support tagging
 * @method      Wei\TagCache tagCache($tag, $ignore1 = null, $ignore2 = null) Manager: Create a new cache service with tagging support
 */
class TagCacheMixin {
}

/**
 * @property    Wei\Time $time Date time utils
 */
class TimeMixin {
}

/**
 * @property    Wei\Ua $ua A service to detect user OS, browser and device name and version
 * @method      bool ua($name) Check if in the specified browser, OS or device
 */
class UaMixin {
}

/**
 * @property    Wei\Upload $upload A service that handles the uploaded files
 * @method      bool upload($field = null, $options = []) Upload a file
 */
class UploadMixin {
}

/**
 * @property    Wei\Url $url A helper service to generate the URL
 * @method      string url($url = '', $argsOrParams = [], $params = []) Invoke the "to" method
 */
class UrlMixin {
}

/**
 * @property    Wei\Uuid $uuid A util wei that generates a RANDOM UUID(universally unique identifier)
 * @method      string uuid() Generate a RANDOM UUID(universally unique identifier)
 */
class UuidMixin {
}

/**
 * @property    Wei\V $v A chaining validator
 * @method      Wei\V v($options = []) Create a new validator
 */
class VMixin {
}

/**
 * @property    Wei\Validate $validate A validator service
 * @method      Wei\Validate validate($options = []) Create a new validator and validate by specified options
 */
class ValidateMixin {
}

/**
 * @property    Wei\View $view A service that use to render PHP template
 * @method      string view($name = null, $data = []) Render a PHP template
 */
class ViewMixin {
}

/**
 * @property    Wei\WeChatApp $weChatApp A service handles WeChat(WeiXin) callback message
 * @method      Wei\WeChatApp weChatApp() Start up WeChat application and output the matched rule message
 */
class WeChatAppMixin {
}

/**
 * @property    Wei\Wei $wei The service container
 */
class WeiMixin {
}

/**
 * @property    Wei\Validator\All $isAll Check if all of the element in the input is valid by all specified rules
 * @method      bool isAll($input, $rules = []) Check if all of the element in the input is valid by all specified rules
 */
class IsAllMixin {
}

/**
 * @property    Wei\Validator\AllOf $isAllOf Check if the input is valid by all of the rules
 * @method      bool isAllOf($input, $rules = [], $atLeast = null) Check if the input is valid by all of the rules
 */
class IsAllOfMixin {
}

/**
 * @property    Wei\Validator\Alnum $isAlnum Check if the input contains letters (a-z) and digits (0-9)
 * @method      bool isAlnum($input, $pattern = null) Returns whether the $input value is valid
 */
class IsAlnumMixin {
}

/**
 * @property    Wei\Validator\Alpha $isAlpha Check if the input contains only letters (a-z)
 * @method      bool isAlpha($input, $pattern = null) Returns whether the $input value is valid
 */
class IsAlphaMixin {
}

/**
 * @property    Wei\Validator\BaseValidator $isBaseValidator The base class of validator
 * @method      bool isBaseValidator($input) Validate the input value
 */
class IsBaseValidatorMixin {
}

/**
 * @property    Wei\Validator\Between $isBetween Check if the input is between the specified minimum and maximum value
 * @method      mixed isBetween($input, $min = null, $max = null)
 */
class IsBetweenMixin {
}

/**
 * @property    Wei\Validator\Blank $isBlank Check if the input is blank
 * @method      bool isBlank($input) Validate the input value
 */
class IsBlankMixin {
}

/**
 * @property    Wei\Validator\Callback $isCallback Check if the input is valid by specified callback
 * @method      bool isCallback($input, $fn = null, $message = null) Check if the input is valid by specified callback
 */
class IsCallbackMixin {
}

/**
 * @property    Wei\Validator\CharLength $isCharLength Check if the characters length of input is equals specified length
 * @method      mixed isCharLength($input, $min = null, $max = null)
 */
class IsCharLengthMixin {
}

/**
 * @property    Wei\Validator\Chinese $isChinese Check if the input contains only Chinese characters
 * @method      bool isChinese($input, $pattern = null) Returns whether the $input value is valid
 */
class IsChineseMixin {
}

/**
 * @property    Wei\Validator\Color $isColor Check if the input is valid Hex color
 * @method      bool isColor($input, $pattern = null) Returns whether the $input value is valid
 */
class IsColorMixin {
}

/**
 * @property    Wei\Validator\Contains $isContains Check if the input is contains the specified string or pattern
 * @method      bool isContains($input, $search = null, $regex = false) Returns whether the $input value is valid
 */
class IsContainsMixin {
}

/**
 * @property    Wei\Validator\CreditCard $isCreditCard Check if the input is valid credit card number
 * @method      mixed isCreditCard($input, $type = null)
 */
class IsCreditCardMixin {
}

/**
 * @property    Wei\Validator\Date $isDate Check if the input is a valid date
 * @method      mixed isDate($input, $format = null)
 */
class IsDateMixin {
}

/**
 * @property    Wei\Validator\DateTime $isDateTime Check if the input is a valid datetime
 * @method      mixed isDateTime($input, $format = null)
 */
class IsDateTimeMixin {
}

/**
 * @property    Wei\Validator\Decimal $isDecimal Check if the input is decimal
 * @method      bool isDecimal($input) Validate the input value
 */
class IsDecimalMixin {
}

/**
 * @property    Wei\Validator\Digit $isDigit Check if the input contains only digits (0-9)
 * @method      bool isDigit($input, $pattern = null) Returns whether the $input value is valid
 */
class IsDigitMixin {
}

/**
 * @property    Wei\Validator\Dir $isDir Check if the input is existing directory
 * @method      bool isDir($input) Validate the input value
 */
class IsDirMixin {
}

/**
 * @property    Wei\Validator\DivisibleBy $isDivisibleBy Check if the input could be divisible by specified divisor
 * @method      mixed isDivisibleBy($input, $divisor = null)
 */
class IsDivisibleByMixin {
}

/**
 * @property    Wei\Validator\DoubleByte $isDoubleByte Check if the input contains only double characters
 * @method      bool isDoubleByte($input, $pattern = null) Returns whether the $input value is valid
 */
class IsDoubleByteMixin {
}

/**
 * @property    Wei\Validator\Email $isEmail Check if the input is valid email address
 * @method      bool isEmail($input) Validate the input value
 */
class IsEmailMixin {
}

/**
 * @property    Wei\Validator\EndsWith $isEndsWith Check if the input is ends with specified string
 * @method      mixed isEndsWith($input, $findMe = null, $case = null)
 */
class IsEndsWithMixin {
}

/**
 * @property    Wei\Validator\EqualTo $isEqualTo Check if the input is equals to (==) the specified value
 * @method      mixed isEqualTo($input, $value = null)
 */
class IsEqualToMixin {
}

/**
 * @property    Wei\Validator\Exists $isExists Check if the input is existing file or directory
 * @method      bool isExists($input) Validate the input value
 */
class IsExistsMixin {
}

/**
 * @property    Wei\Validator\FieldExists $isFieldExists Check if the validate fields data is exists
 * @method      bool isFieldExists($input) Validate the input value
 */
class IsFieldExistsMixin {
}

/**
 * @property    Wei\Validator\File $isFile Check if the input is valid file
 * @method      mixed isFile($input, $options = [])
 */
class IsFileMixin {
}

/**
 * @property    Wei\Validator\GreaterThan $isGreaterThan Check if the input is greater than (>=) the specified value
 * @method      mixed isGreaterThan($input, $value = null)
 */
class IsGreaterThanMixin {
}

/**
 * @property    Wei\Validator\GreaterThanOrEqual $isGreaterThanOrEqual Check if the input is greater than or equal to (>=) the specified value
 * @method      mixed isGreaterThanOrEqual($input, $value = null)
 */
class IsGreaterThanOrEqualMixin {
}

/**
 * @property    Wei\Validator\IdCardCn $isIdCardCn Check if the input is valid Chinese identity card
 * @method      bool isIdCardCn($input) Validate the input value
 */
class IsIdCardCnMixin {
}

/**
 * @property    Wei\Validator\IdCardHk $isIdCardHk Check if the input is valid Hong Kong identity card
 * @method      bool isIdCardHk($input) Validate the input value
 */
class IsIdCardHkMixin {
}

/**
 * @property    Wei\Validator\IdCardMo $isIdCardMo Check if the input is valid Macau identity card
 * @method      bool isIdCardMo($input, $pattern = null) Returns whether the $input value is valid
 */
class IsIdCardMoMixin {
}

/**
 * @property    Wei\Validator\IdCardTw $isIdCardTw Check if the input is valid Taiwan identity card
 * @method      bool isIdCardTw($input) Validate the input value
 */
class IsIdCardTwMixin {
}

/**
 * @property    Wei\Validator\IdenticalTo $isIdenticalTo Check if the input is identical to (===) specified value
 * @method      mixed isIdenticalTo($input, $value = null)
 */
class IsIdenticalToMixin {
}

/**
 * @property    Wei\Validator\Image $isImage Check if the input is valid image
 * @method      mixed isImage($input, $options = [])
 */
class IsImageMixin {
}

/**
 * @property    Wei\Validator\In $isIn Check if the input is in specified array
 * @method      mixed isIn($input, $array = [], $strict = null)
 */
class IsInMixin {
}

/**
 * @property    Wei\Validator\Ip $isIp Check if the input is valid IP address
 * @method      mixed isIp($input, $options = [])
 */
class IsIpMixin {
}

/**
 * @property    Wei\Validator\Length $isLength Check if the length (or size) of input is equals specified length or in
 * @method      mixed isLength($input, $min = null, $max = null)
 */
class IsLengthMixin {
}

/**
 * @property    Wei\Validator\LessThan $isLessThan Check if the input is less than (<) the specified value
 * @method      mixed isLessThan($input, $value = null)
 */
class IsLessThanMixin {
}

/**
 * @property    Wei\Validator\LessThanOrEqual $isLessThanOrEqual Check if the input is less than or equal to (<=) the specified value
 * @method      mixed isLessThanOrEqual($input, $value = null)
 */
class IsLessThanOrEqualMixin {
}

/**
 * @property    Wei\Validator\Lowercase $isLowercase Check if the input is lowercase
 * @method      bool isLowercase($input) Validate the input value
 */
class IsLowercaseMixin {
}

/**
 * @property    Wei\Validator\Luhn $isLuhn Check if the input is valid by the Luhn algorithm
 * @method      bool isLuhn($input) Validate the input value
 */
class IsLuhnMixin {
}

/**
 * @property    Wei\Validator\MaxLength $isMaxLength Check if the length (or size) of input is lower than specified length
 * @method      mixed isMaxLength($input, $max = null, $ignore = null)
 */
class IsMaxLengthMixin {
}

/**
 * @property    Wei\Validator\MinLength $isMinLength Check if the length (or size) of input is greater than specified length
 * @method      mixed isMinLength($input, $min = null, $ignore = null)
 */
class IsMinLengthMixin {
}

/**
 * @property    Wei\Validator\MobileCn $isMobileCn Check if the input is valid Chinese mobile number
 * @method      bool isMobileCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsMobileCnMixin {
}

/**
 * @property    Wei\Validator\NaturalNumber $isNaturalNumber Check if the input is a natural number (integer that greater than or equals 0)
 * @method      bool isNaturalNumber($input) Validate the input value
 */
class IsNaturalNumberMixin {
}

/**
 * @property    Wei\Validator\NoneOf $isNoneOf Check if the input is NOT valid by all of specified rules
 * @method      mixed isNoneOf($input, $rules = [], $ignore = null)
 */
class IsNoneOfMixin {
}

/**
 * @property    Wei\Validator\NullType $isNullType Check if the input is null
 * @method      bool isNullType($input) Validate the input value
 */
class IsNullTypeMixin {
}

/**
 * @property    Wei\Validator\Number $isNumber Check if the input is number
 * @method      bool isNumber($input) Validate the input value
 */
class IsNumberMixin {
}

/**
 * @property    Wei\Validator\OneOf $isOneOf Check if the input is valid by any of the rules
 * @method      mixed isOneOf($input, $rules = [], $atLeast = null)
 */
class IsOneOfMixin {
}

/**
 * @property    Wei\Validator\Password $isPassword Check if the input password is secure enough
 * @method      mixed isPassword($input, $options = [])
 */
class IsPasswordMixin {
}

/**
 * @property    Wei\Validator\Phone $isPhone Check if the input is valid phone number, contains only digit, +, - and spaces
 * @method      bool isPhone($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPhoneMixin {
}

/**
 * @property    Wei\Validator\PhoneCn $isPhoneCn Check if the input is valid Chinese phone number
 * @method      bool isPhoneCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPhoneCnMixin {
}

/**
 * @property    Wei\Validator\PlateNumberCn $isPlateNumberCn Check if the input is valid Chinese plate number
 * @method      bool isPlateNumberCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPlateNumberCnMixin {
}

/**
 * @property    Wei\Validator\PositiveInteger $isPositiveInteger Check if the input is a positive integer (integer that greater than 0)
 * @method      bool isPositiveInteger($input) Validate the input value
 */
class IsPositiveIntegerMixin {
}

/**
 * @property    Wei\Validator\PostcodeCn $isPostcodeCn Check if the input is valid Chinese postcode
 * @method      bool isPostcodeCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPostcodeCnMixin {
}

/**
 * @property    Wei\Validator\Present $isPresent Check if the input is not empty
 * @method      bool isPresent($input) Validate the input value
 */
class IsPresentMixin {
}

/**
 * @property    Wei\Validator\QQ $isQQ Check if the input is valid QQ number
 * @method      bool isQQ($input, $pattern = null) Returns whether the $input value is valid
 */
class IsQQMixin {
}

/**
 * @property    Wei\Validator\RecordExists $isRecordExists Check if the input is existing table record
 * @method      bool isRecordExists($input = null, $table = null, $field = 'id') Check if the input is existing table record
 */
class IsRecordExistsMixin {
}

/**
 * @property    Wei\Validator\Regex $isRegex Check if the input is valid by specified regular expression
 * @method      bool isRegex($input, $pattern = null) Returns whether the $input value is valid
 */
class IsRegexMixin {
}

/**
 * @property    Wei\Validator\Required $isRequired Check if the input is provided
 * @method      mixed isRequired($input, $required = null)
 */
class IsRequiredMixin {
}

/**
 * @property    Wei\Validator\SomeOf $isSomeOf Check if the input is valid by specified number of the rules
 * @method      mixed isSomeOf($input, $rules = [], $atLeast = null)
 */
class IsSomeOfMixin {
}

/**
 * @property    Wei\Validator\StartsWith $isStartsWith Check if the input is starts with specified string
 * @method      mixed isStartsWith($input, $findMe = null, $case = null)
 */
class IsStartsWithMixin {
}

/**
 * @property    Wei\Validator\Time $isTime Check if the input is a valid time
 * @method      mixed isTime($input, $format = null)
 */
class IsTimeMixin {
}

/**
 * @property    Wei\Validator\Tld $isTld Check if the input is a valid top-level domain
 * @method      mixed isTld($input, $array = [], $strict = null)
 */
class IsTldMixin {
}

/**
 * @property    Wei\Validator\Type $isType Check if the type of input is equals specified type name
 * @method      mixed isType($input, $type = null)
 */
class IsTypeMixin {
}

/**
 * @property    Wei\Validator\Uppercase $isUppercase Check if the input is uppercase
 * @method      bool isUppercase($input) Validate the input value
 */
class IsUppercaseMixin {
}

/**
 * @property    Wei\Validator\Url $isUrl Check if the input is valid URL address
 * @method      string|bool isUrl($input, $options = []) Check if the input is valid URL address, options could be "path" and "query"
 */
class IsUrlMixin {
}

/**
 * @property    Wei\Validator\Uuid $isUuid Check if the input is valid UUID(v4)
 * @method      bool isUuid($input, $pattern = null) Returns whether the $input value is valid
 */
class IsUuidMixin {
}

/**
 * @mixin ApcMixin
 * @mixin AppMixin
 * @mixin ArrayCacheMixin
 * @mixin AssetMixin
 * @mixin BaseMixin
 * @mixin BaseCacheMixin
 * @mixin BaseControllerMixin
 * @mixin BicacheMixin
 * @mixin BlockMixin
 * @mixin CacheMixin
 * @mixin ClassMapMixin
 * @mixin ConfigMixin
 * @mixin CookieMixin
 * @mixin CouchbaseMixin
 * @mixin CounterMixin
 * @mixin DbMixin
 * @mixin DbCacheMixin
 * @mixin EMixin
 * @mixin EnvMixin
 * @mixin ErrorMixin
 * @mixin EventMixin
 * @mixin FileCacheMixin
 * @mixin GravatarMixin
 * @mixin HttpMixin
 * @mixin LockMixin
 * @mixin LoggerMixin
 * @mixin MemcacheMixin
 * @mixin MemcachedMixin
 * @mixin MigrationMixin
 * @mixin MongoCacheMixin
 * @mixin NearCacheMixin
 * @mixin PasswordMixin
 * @mixin PhpErrorMixin
 * @mixin PhpFileCacheMixin
 * @mixin PinyinMixin
 * @mixin RecordMixin
 * @mixin RedisMixin
 * @mixin RequestMixin
 * @mixin ResponseMixin
 * @mixin RetMixin
 * @mixin RetTraitMixin
 * @mixin RouterMixin
 * @mixin SafeUrlMixin
 * @mixin SchemaMixin
 * @mixin ServiceTraitMixin
 * @mixin SessionMixin
 * @mixin ShareMixin
 * @mixin SoapMixin
 * @mixin StatsDMixin
 * @mixin TMixin
 * @mixin TagCacheMixin
 * @mixin TimeMixin
 * @mixin UaMixin
 * @mixin UploadMixin
 * @mixin UrlMixin
 * @mixin UuidMixin
 * @mixin VMixin
 * @mixin ValidateMixin
 * @mixin ViewMixin
 * @mixin WeChatAppMixin
 * @mixin WeiMixin
 * @mixin IsAllMixin
 * @mixin IsAllOfMixin
 * @mixin IsAlnumMixin
 * @mixin IsAlphaMixin
 * @mixin IsBaseValidatorMixin
 * @mixin IsBetweenMixin
 * @mixin IsBlankMixin
 * @mixin IsCallbackMixin
 * @mixin IsCharLengthMixin
 * @mixin IsChineseMixin
 * @mixin IsColorMixin
 * @mixin IsContainsMixin
 * @mixin IsCreditCardMixin
 * @mixin IsDateMixin
 * @mixin IsDateTimeMixin
 * @mixin IsDecimalMixin
 * @mixin IsDigitMixin
 * @mixin IsDirMixin
 * @mixin IsDivisibleByMixin
 * @mixin IsDoubleByteMixin
 * @mixin IsEmailMixin
 * @mixin IsEndsWithMixin
 * @mixin IsEqualToMixin
 * @mixin IsExistsMixin
 * @mixin IsFieldExistsMixin
 * @mixin IsFileMixin
 * @mixin IsGreaterThanMixin
 * @mixin IsGreaterThanOrEqualMixin
 * @mixin IsIdCardCnMixin
 * @mixin IsIdCardHkMixin
 * @mixin IsIdCardMoMixin
 * @mixin IsIdCardTwMixin
 * @mixin IsIdenticalToMixin
 * @mixin IsImageMixin
 * @mixin IsInMixin
 * @mixin IsIpMixin
 * @mixin IsLengthMixin
 * @mixin IsLessThanMixin
 * @mixin IsLessThanOrEqualMixin
 * @mixin IsLowercaseMixin
 * @mixin IsLuhnMixin
 * @mixin IsMaxLengthMixin
 * @mixin IsMinLengthMixin
 * @mixin IsMobileCnMixin
 * @mixin IsNaturalNumberMixin
 * @mixin IsNoneOfMixin
 * @mixin IsNullTypeMixin
 * @mixin IsNumberMixin
 * @mixin IsOneOfMixin
 * @mixin IsPasswordMixin
 * @mixin IsPhoneMixin
 * @mixin IsPhoneCnMixin
 * @mixin IsPlateNumberCnMixin
 * @mixin IsPositiveIntegerMixin
 * @mixin IsPostcodeCnMixin
 * @mixin IsPresentMixin
 * @mixin IsQQMixin
 * @mixin IsRecordExistsMixin
 * @mixin IsRegexMixin
 * @mixin IsRequiredMixin
 * @mixin IsSomeOfMixin
 * @mixin IsStartsWithMixin
 * @mixin IsTimeMixin
 * @mixin IsTldMixin
 * @mixin IsTypeMixin
 * @mixin IsUppercaseMixin
 * @mixin IsUrlMixin
 * @mixin IsUuidMixin
 */
class AutoCompletion {
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion;
}

