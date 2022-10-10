<?php

/**
 * @property    Wei\Apcu $apcu A cache service that stored data in PHP APCu
 * @method      mixed apcu($key, $value = null, $expire = 0) Retrieve or store an item
 */
class ApcuMixin
{
}

/**
 * @property    Wei\App $app A service to build an MVC application
 * @method      Wei\App app($options = []) Startup an MVC application
 */
class AppMixin
{
}

/**
 * @property    Wei\ArrayCache $arrayCache A cache service that stored data in PHP array
 * @method      mixed arrayCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class ArrayCacheMixin
{
}

/**
 * @property    Wei\Asset $asset A service to generate assets' URL
 * @method      string asset($file, $version = true) Returns the asset or concat URL by specified file
 */
class AssetMixin
{
}

/**
 * @property    Wei\Bicache $bicache A two-level cache service
 * @method      mixed bicache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class BicacheMixin
{
}

/**
 * @property    Wei\Block $block A service that stores view content for template inheritance
 * @method      string block($name, $type = 'append') Start to capture a block
 */
class BlockMixin
{
}

/**
 * @property    Wei\Cache $cache A cache service proxy
 * @method      mixed cache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class CacheMixin
{
}

/**
 * @property    Wei\ClassMap $classMap Generate class map from specified directory and pattern
 */
class ClassMapMixin
{
}

/**
 * @property    Wei\Cls $cls The class util service
 */
class ClsMixin
{
}

/**
 * @property    Wei\Config $config A service to manage service container configurations
 */
class ConfigMixin
{
}

/**
 * @property    Wei\Cookie $cookie A service that handles the HTTP request and response cookies
 * @method      mixed cookie($key, $value = null, $options = []) Get request cookie or set response cookie
 */
class CookieMixin
{
}

/**
 * @property    Wei\Counter $counter A counter service
 */
class CounterMixin
{
}

/**
 * @property    Wei\Db $db A database service inspired by Doctrine DBAL
 * @method      Record db($table = null) Create a new instance of a SQL query builder with specified table name
 */
class DbMixin
{
}

/**
 * @property    Wei\DbCache $dbCache A cache service that stored data in databases
 * @method      mixed dbCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class DbCacheMixin
{
}

/**
 * @property    Wei\E $e Context specific methods for use in secure output escaping
 * @method      string e($string, $type = 'html') Escapes a string by specified type for secure output
 */
class EMixin
{
}

/**
 * @property    Wei\Env $env A service to detect the environment name and load configuration by environment name
 * @method      string env() Returns the environment name
 */
class EnvMixin
{
}

/**
 * @property    Wei\Error $error A service that handles exception and display pretty exception message
 * @method      Wei\Error error($fn) Attach a handler to exception error
 */
class ErrorMixin
{
}

/**
 * @property    Wei\Event $event An event dispatch service
 */
class EventMixin
{
}

/**
 * @property    Wei\FileCache $fileCache A cache service that stored data in files
 * @method      mixed fileCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class FileCacheMixin
{
}

/**
 * @property    Wei\Gravatar $gravatar A service that generates a Gravatar URL for a specified email address
 * @method      string gravatar($email, $size = null, $default = null, $rating = null) Generate a Gravatar URL for a specified email address
 */
class GravatarMixin
{
}

/**
 * @property    Wei\Http $http An HTTP client that inspired by jQuery Ajax
 * @method      Wei\Http http($url = null, $options = []) Create a new HTTP object and execute
 */
class HttpMixin
{
}

/**
 * @property    Wei\IsAll $isAll Check if all of the element in the input is valid by all specified rules
 * @method      bool isAll($input, $rules = []) Check if all of the element in the input is valid by all specified rules
 */
class IsAllMixin
{
}

/**
 * @property    Wei\IsAllOf $isAllOf Check if the input is valid by all of the rules
 * @method      bool isAllOf($input, $rules = [], $atLeast = null) Check if the input is valid by all of the rules
 */
class IsAllOfMixin
{
}

/**
 * @property    Wei\IsAllow $isAllow Ignore the remaining rules of current field if input value is in the specified values
 * @method      mixed isAllow($input, $values)
 */
class IsAllowMixin
{
}

/**
 * @property    Wei\IsAllowEmpty $isAllowEmpty Ignore the remaining rules of current field if input value is empty string or null
 * @method      bool isAllowEmpty($input) Validate the input value
 */
class IsAllowEmptyMixin
{
}

/**
 * @property    Wei\IsAlnum $isAlnum Check if the input contains letters (a-z) and digits (0-9)
 * @method      bool isAlnum($input, $pattern = null) Returns whether the $input value is valid
 */
class IsAlnumMixin
{
}

/**
 * @property    Wei\IsAlpha $isAlpha Check if the input contains only letters (a-z)
 * @method      bool isAlpha($input, $pattern = null) Returns whether the $input value is valid
 */
class IsAlphaMixin
{
}

/**
 * @property    Wei\IsAnyDateTime $isAnyDateTime Check if the input is any valid English textual datetime
 * @method      mixed isAnyDateTime($input, $format = null)
 */
class IsAnyDateTimeMixin
{
}

/**
 * @property    Wei\IsArray $isArray Check if the input could be convert to array
 * @method      mixed isArray($input, $minLength = null, $maxLength = null)
 */
class IsArrayMixin
{
}

/**
 * @property    Wei\IsBetween $isBetween Check if the input is between the specified minimum and maximum value
 * @method      mixed isBetween($input, $min = null, $max = null)
 */
class IsBetweenMixin
{
}

/**
 * @property    Wei\IsBigInt $isBigInt Check if the input is int value and between -2^63(-9,223,372,036,854,775,808) and 2^63-1(9,223,372,036,854,775,807)
 * @method      mixed isBigInt($input, $min = null, $max = null)
 */
class IsBigIntMixin
{
}

/**
 * @property    Wei\IsBlank $isBlank Check if the input is blank
 * @method      bool isBlank($input) Validate the input value
 */
class IsBlankMixin
{
}

/**
 * @property    Wei\IsBool $isBool Check if the input is a bool value
 * @method      bool isBool($input) Validate the input value
 */
class IsBoolMixin
{
}

/**
 * @property    Wei\IsBoolable $isBoolable Check if the input is a bool value
 * @method      bool isBoolable($input) Validate the input value
 */
class IsBoolableMixin
{
}

/**
 * @property    Wei\IsCallback $isCallback Check if the input is valid by specified callback
 * @method      bool isCallback($input, $fn = null, $message = null) Check if the input is valid by specified callback
 */
class IsCallbackMixin
{
}

/**
 * @property    Wei\IsChar $isChar Check if the input is a string within the specified character lengths
 * @method      mixed isChar($input, $minLength = null, $maxLength = null)
 */
class IsCharMixin
{
}

/**
 * @property    Wei\IsChildren $isChildren Check if the input is validated by the specified V service
 * @method      mixed isChildren($input, $v = null)
 */
class IsChildrenMixin
{
}

/**
 * @property    Wei\IsChinese $isChinese Check if the input contains only Chinese characters
 * @method      bool isChinese($input, $pattern = null) Returns whether the $input value is valid
 */
class IsChineseMixin
{
}

/**
 * @property    Wei\IsColor $isColor Check if the input is valid Hex color
 * @method      bool isColor($input, $pattern = null) Returns whether the $input value is valid
 */
class IsColorMixin
{
}

/**
 * @property    Wei\IsContains $isContains Check if the input is contains the specified string or pattern
 * @method      bool isContains($input, $search = null, $regex = false) Returns whether the $input value is valid
 */
class IsContainsMixin
{
}

/**
 * @property    Wei\IsCreditCard $isCreditCard Check if the input is valid credit card number
 * @method      mixed isCreditCard($input, $type = null)
 */
class IsCreditCardMixin
{
}

/**
 * @property    Wei\IsDate $isDate Check if the input is a valid date with specific format
 * @method      mixed isDate($input, $format = null)
 */
class IsDateMixin
{
}

/**
 * @property    Wei\IsDateTime $isDateTime Check if the input is a valid datetime with specific format
 * @method      mixed isDateTime($input, $format = null)
 */
class IsDateTimeMixin
{
}

/**
 * @property    Wei\IsDecimal $isDecimal Check if the input is decimal
 * @method      bool isDecimal($input) Validate the input value
 */
class IsDecimalMixin
{
}

/**
 * @property    Wei\IsDefaultInt $isDefaultInt Check if the input is int value and between -2147483648(-2^31) and 2147483647(2^31-1) (4 Bytes)
 * @method      mixed isDefaultInt($input, $min = null, $max = null)
 */
class IsDefaultIntMixin
{
}

/**
 * @property    Wei\IsDigit $isDigit Check if the input contains only digits (0-9)
 * @method      bool isDigit($input, $pattern = null) Returns whether the $input value is valid
 */
class IsDigitMixin
{
}

/**
 * @property    Wei\IsDir $isDir Check if the input is existing directory
 * @method      bool isDir($input) Validate the input value
 */
class IsDirMixin
{
}

/**
 * @property    Wei\IsDivisibleBy $isDivisibleBy Check if the input could be divisible by specified divisor
 * @method      mixed isDivisibleBy($input, $divisor = null)
 */
class IsDivisibleByMixin
{
}

/**
 * @property    Wei\IsDoubleByte $isDoubleByte Check if the input contains only double characters
 * @method      bool isDoubleByte($input, $pattern = null) Returns whether the $input value is valid
 */
class IsDoubleByteMixin
{
}

/**
 * @property    Wei\IsEach $isEach Check if every item in the input is validated by the specified V service
 * @method      mixed isEach($input, $v = null)
 */
class IsEachMixin
{
}

/**
 * @property    Wei\IsEmail $isEmail Check if the input is valid email address
 * @method      bool isEmail($input) Validate the input value
 */
class IsEmailMixin
{
}

/**
 * @property    Wei\IsEmpty $isEmpty Check if the input is not empty
 * @method      bool isEmpty($input) Validate the input value
 */
class IsEmptyMixin
{
}

/**
 * @property    Wei\IsEndsWith $isEndsWith Check if the input is ends with specified string
 * @method      mixed isEndsWith($input, $findMe = null, $case = null)
 */
class IsEndsWithMixin
{
}

/**
 * @property    Wei\IsEqualTo $isEqualTo Check if the input is equals to (==) the specified value
 * @method      mixed isEqualTo($input, $value = null)
 */
class IsEqualToMixin
{
}

/**
 * @property    Wei\IsExists $isExists Check if the input is existing file or directory
 * @method      bool isExists($input) Validate the input value
 */
class IsExistsMixin
{
}

/**
 * @property    Wei\IsFieldExists $isFieldExists Check if the validate fields data is exists
 * @method      bool isFieldExists($input) Validate the input value
 */
class IsFieldExistsMixin
{
}

/**
 * @property    Wei\IsFile $isFile Check if the input is valid file
 * @method      mixed isFile($input, $options = [])
 */
class IsFileMixin
{
}

/**
 * @property    Wei\IsFloat $isFloat Check if the input is a float value
 * @method      bool isFloat($input) Validate the input value
 */
class IsFloatMixin
{
}

/**
 * @property    Wei\IsGreaterThan $isGreaterThan Check if the input is greater than (>=) the specified value
 * @method      mixed isGreaterThan($input, $value = null)
 */
class IsGreaterThanMixin
{
}

/**
 * @property    Wei\IsGreaterThanOrEqual $isGreaterThanOrEqual Check if the input is greater than or equal to (>=) the specified value
 * @method      mixed isGreaterThanOrEqual($input, $value = null)
 */
class IsGreaterThanOrEqualMixin
{
}

/**
 * @property    Wei\IsGt $isGt Check if the input is greater than (>=) the specified value
 * @method      mixed isGt($input, $value = null)
 */
class IsGtMixin
{
}

/**
 * @property    Wei\IsGte $isGte Check if the input is greater than or equal to (>=) the specified value
 * @method      mixed isGte($input, $value = null)
 */
class IsGteMixin
{
}

/**
 * @property    Wei\IsIdCardCn $isIdCardCn Check if the input is valid Chinese identity card
 * @method      bool isIdCardCn($input) Validate the input value
 */
class IsIdCardCnMixin
{
}

/**
 * @property    Wei\IsIdCardHk $isIdCardHk Check if the input is valid Hong Kong identity card
 * @method      bool isIdCardHk($input) Validate the input value
 */
class IsIdCardHkMixin
{
}

/**
 * @property    Wei\IsIdCardMo $isIdCardMo Check if the input is valid Macau identity card
 * @method      bool isIdCardMo($input, $pattern = null) Returns whether the $input value is valid
 */
class IsIdCardMoMixin
{
}

/**
 * @property    Wei\IsIdCardTw $isIdCardTw Check if the input is valid Taiwan identity card
 * @method      bool isIdCardTw($input) Validate the input value
 */
class IsIdCardTwMixin
{
}

/**
 * @property    Wei\IsIdenticalTo $isIdenticalTo Check if the input is identical to (===) specified value
 * @method      mixed isIdenticalTo($input, $value = null)
 */
class IsIdenticalToMixin
{
}

/**
 * @property    Wei\IsImage $isImage Check if the input is valid image
 * @method      mixed isImage($input, $options = [])
 */
class IsImageMixin
{
}

/**
 * @property    Wei\IsImageUrl $isImageUrl Check if the input is valid image URL address
 * @method      mixed isImageUrl($input, $maxLength = null)
 */
class IsImageUrlMixin
{
}

/**
 * @property    Wei\IsIn $isIn Check if the input is in specified array
 * @method      mixed isIn($input, $array = [], $strict = null)
 */
class IsInMixin
{
}

/**
 * @property    Wei\IsInConst $isInConst Check if the input is one of the class const
 * @method      mixed isInConst($input, $class = '', $prefix = null)
 */
class IsInConstMixin
{
}

/**
 * @property    Wei\IsInt $isInt Check if the input could be convert to int
 * @method      mixed isInt($input, $min = null, $max = null)
 */
class IsIntMixin
{
}

/**
 * @property    Wei\IsIp $isIp Check if the input is valid IP address
 * @method      mixed isIp($input, $options = [])
 */
class IsIpMixin
{
}

/**
 * @property    Wei\IsLength $isLength Check if the length (or size) of input is equals specified length or in
 * @method      mixed isLength($input, $min = null, $max = null)
 */
class IsLengthMixin
{
}

/**
 * @property    Wei\IsLessThan $isLessThan Check if the input is less than (<) the specified value
 * @method      mixed isLessThan($input, $value = null)
 */
class IsLessThanMixin
{
}

/**
 * @property    Wei\IsLessThanOrEqual $isLessThanOrEqual Check if the input is less than or equal to (<=) the specified value
 * @method      mixed isLessThanOrEqual($input, $value = null)
 */
class IsLessThanOrEqualMixin
{
}

/**
 * @property    Wei\IsLowercase $isLowercase Check if the input is lowercase
 * @method      bool isLowercase($input) Validate the input value
 */
class IsLowercaseMixin
{
}

/**
 * @property    Wei\IsLt $isLt Check if the input is less than (<) the specified value
 * @method      mixed isLt($input, $value = null)
 */
class IsLtMixin
{
}

/**
 * @property    Wei\IsLte $isLte Check if the input is less than or equal to (<=) the specified value
 * @method      mixed isLte($input, $value = null)
 */
class IsLteMixin
{
}

/**
 * @property    Wei\IsLuhn $isLuhn Check if the input is valid by the Luhn algorithm
 * @method      bool isLuhn($input) Validate the input value
 */
class IsLuhnMixin
{
}

/**
 * @property    Wei\IsMaxAccuracy $isMaxAccuracy Check if the number of digits after the decimal point of the input is lower than specified length
 * @method      mixed isMaxAccuracy($input, $max = null)
 */
class IsMaxAccuracyMixin
{
}

/**
 * @property    Wei\IsMaxCharLength $isMaxCharLength Check if the character length of input is lower than specified length
 * @method      mixed isMaxCharLength($input, $max = null, $ignore = null)
 */
class IsMaxCharLengthMixin
{
}

/**
 * @property    Wei\IsMaxLength $isMaxLength Check if the length (or size) of input is lower than specified length
 * @method      mixed isMaxLength($input, $max = null, $ignore = null)
 */
class IsMaxLengthMixin
{
}

/**
 * @property    Wei\IsMediumInt $isMediumInt Check if the input is int value and between -8388608(-2^23) and 8388607(2^23-1) (3 Bytes)
 * @method      mixed isMediumInt($input, $min = null, $max = null)
 */
class IsMediumIntMixin
{
}

/**
 * @property    Wei\IsMediumText $isMediumText Check if the input is a string of 16777215(16Mb-1) bytes or less
 * @method      mixed isMediumText($input, $minLength = null, $maxLength = null)
 */
class IsMediumTextMixin
{
}

/**
 * @property    Wei\IsMinCharLength $isMinCharLength Check if the character length of input is greater than specified length
 * @method      mixed isMinCharLength($input, $min = null, $ignore = null)
 */
class IsMinCharLengthMixin
{
}

/**
 * @property    Wei\IsMinLength $isMinLength Check if the length (or size) of input is greater than specified length
 * @method      mixed isMinLength($input, $min = null, $ignore = null)
 */
class IsMinLengthMixin
{
}

/**
 * @property    Wei\IsMobileCn $isMobileCn Check if the input is valid Chinese mobile number
 * @method      bool isMobileCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsMobileCnMixin
{
}

/**
 * @property    Wei\IsNaturalNumber $isNaturalNumber Check if the input is a natural number (integer that greater than or equals 0)
 * @method      bool isNaturalNumber($input) Validate the input value
 */
class IsNaturalNumberMixin
{
}

/**
 * @property    Wei\IsNoneOf $isNoneOf Check if the input is NOT valid by all of specified rules
 * @method      mixed isNoneOf($input, $rules = [], $ignore = null)
 */
class IsNoneOfMixin
{
}

/**
 * @property    Wei\IsNullType $isNullType Check if the input is null
 * @method      bool isNullType($input) Validate the input value
 */
class IsNullTypeMixin
{
}

/**
 * @property    Wei\IsNumber $isNumber Check if the input is number
 * @method      mixed isNumber($input, $precision = null, $scale = null)
 */
class IsNumberMixin
{
}

/**
 * @property    Wei\IsObject $isObject Check if the input is an object
 * @method      mixed isObject($input, $max = null)
 */
class IsObjectMixin
{
}

/**
 * @property    Wei\IsOneOf $isOneOf Check if the input is valid by any of the rules
 * @method      mixed isOneOf($input, $rules = [], $atLeast = null)
 */
class IsOneOfMixin
{
}

/**
 * @property    Wei\IsPassword $isPassword Check if the input password is secure enough
 * @method      mixed isPassword($input, $options = [])
 */
class IsPasswordMixin
{
}

/**
 * @property    Wei\IsPhone $isPhone Check if the input is valid phone number, contains only digit, +, - and spaces
 * @method      bool isPhone($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPhoneMixin
{
}

/**
 * @property    Wei\IsPhoneCn $isPhoneCn Check if the input is valid Chinese phone number
 * @method      bool isPhoneCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPhoneCnMixin
{
}

/**
 * @property    Wei\IsPlateNumberCn $isPlateNumberCn Check if the input is valid Chinese plate number
 * @method      bool isPlateNumberCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPlateNumberCnMixin
{
}

/**
 * @property    Wei\IsPositiveInteger $isPositiveInteger Check if the input is a positive integer (integer that greater than 0)
 * @method      bool isPositiveInteger($input) Validate the input value
 */
class IsPositiveIntegerMixin
{
}

/**
 * @property    Wei\IsPostcodeCn $isPostcodeCn Check if the input is valid Chinese postcode
 * @method      bool isPostcodeCn($input, $pattern = null) Returns whether the $input value is valid
 */
class IsPostcodeCnMixin
{
}

/**
 * @property    Wei\IsPresent $isPresent Check if the input is not empty
 * @method      bool isPresent($input) Validate the input value
 */
class IsPresentMixin
{
}

/**
 * @property    Wei\IsQQ $isQQ Check if the input is valid QQ number
 * @method      bool isQQ($input, $pattern = null) Returns whether the $input value is valid
 */
class IsQQMixin
{
}

/**
 * @property    Wei\IsRecordExists $isRecordExists Check if the input is existing table record
 * @method      bool isRecordExists($input = null, $table = null, $field = 'id') Check if the input is existing table record
 */
class IsRecordExistsMixin
{
}

/**
 * @property    Wei\IsRegex $isRegex Check if the input is valid by specified regular expression
 * @method      bool isRegex($input, $pattern = null) Returns whether the $input value is valid
 */
class IsRegexMixin
{
}

/**
 * @property    Wei\IsRequired $isRequired Check if the input is provided
 * @method      mixed isRequired($input, $required = null)
 */
class IsRequiredMixin
{
}

/**
 * @property    Wei\IsSmallInt $isSmallInt Check if the input is int value and between -32768(-2^15) and 32767(2^15-1) (2 Bytes)
 * @method      mixed isSmallInt($input, $min = null, $max = null)
 */
class IsSmallIntMixin
{
}

/**
 * @property    Wei\IsSomeOf $isSomeOf Check if the input is valid by specified number of the rules
 * @method      mixed isSomeOf($input, $rules = [], $atLeast = null)
 */
class IsSomeOfMixin
{
}

/**
 * @property    Wei\IsStartsWith $isStartsWith Check if the input is starts with specified string
 * @method      mixed isStartsWith($input, $findMe = null, $case = null)
 */
class IsStartsWithMixin
{
}

/**
 * @property    Wei\IsString $isString Check if the input could be convert to string
 * @method      mixed isString($input, $minLength = null, $maxLength = null)
 */
class IsStringMixin
{
}

/**
 * @property    Wei\IsText $isText Check if the input is a string of 65535(64Kb-1) bytes or less
 * @method      mixed isText($input, $minLength = null, $maxLength = null)
 */
class IsTextMixin
{
}

/**
 * @property    Wei\IsTime $isTime Check if the input is a valid time with specific format
 * @method      mixed isTime($input, $format = null)
 */
class IsTimeMixin
{
}

/**
 * @property    Wei\IsTinyChar $isTinyChar Check if the input is a string of 255 characters or less
 * @method      mixed isTinyChar($input, $minLength = null, $maxLength = null)
 */
class IsTinyCharMixin
{
}

/**
 * @property    Wei\IsTinyInt $isTinyInt Check if the input is int value and between -128(-2^7) and 127(2^7-1) (1 Byte)
 * @method      mixed isTinyInt($input, $min = null, $max = null)
 */
class IsTinyIntMixin
{
}

/**
 * @property    Wei\IsTld $isTld Check if the input is a valid top-level domain
 * @method      mixed isTld($input, $array = [], $strict = null)
 */
class IsTldMixin
{
}

/**
 * @property    Wei\IsTrue $isTrue Check if the input is a true value
 * @method      mixed isTrue($input, $invalidMessage = null)
 */
class IsTrueMixin
{
}

/**
 * @property    Wei\IsType $isType Check if the type of input is equals specified type name
 * @method      mixed isType($input, $type = null)
 */
class IsTypeMixin
{
}

/**
 * @property    Wei\IsUBigInt $isUBigInt Check if the input is int value and between 0 and 2^64-1(18,446,744,073,709,551,615) (8 Bytes)
 * @method      mixed isUBigInt($input, $min = null, $max = null)
 */
class IsUBigIntMixin
{
}

/**
 * @property    Wei\IsUDefaultInt $isUDefaultInt Check if the input is int value and between 0 and 4,294,967,295 (2^32-1) (4 Bytes)
 * @method      mixed isUDefaultInt($input, $min = null, $max = null)
 */
class IsUDefaultIntMixin
{
}

/**
 * @property    Wei\IsUMediumInt $isUMediumInt Check if the input is int value and between 0 and 16,777,215 (2^24-1) (3 Bytes)
 * @method      mixed isUMediumInt($input, $min = null, $max = null)
 */
class IsUMediumIntMixin
{
}

/**
 * @property    Wei\IsUNumber $isUNumber Check if the input is a unsigned number
 * @method      mixed isUNumber($input, $precision = null, $scale = null)
 */
class IsUNumberMixin
{
}

/**
 * @property    Wei\IsUSmallInt $isUSmallInt Check if the input is int value and between 0 and 65,535 (2^16-1) (2 Bytes)
 * @method      mixed isUSmallInt($input, $min = null, $max = null)
 */
class IsUSmallIntMixin
{
}

/**
 * @property    Wei\IsUTinyInt $isUTinyInt Check if the input is int value and between 0 and 255 (2^8-1) (1 Byte)
 * @method      mixed isUTinyInt($input, $min = null, $max = null)
 */
class IsUTinyIntMixin
{
}

/**
 * @property    Wei\IsUppercase $isUppercase Check if the input is uppercase
 * @method      bool isUppercase($input) Validate the input value
 */
class IsUppercaseMixin
{
}

/**
 * @property    Wei\IsUrl $isUrl Check if the input is valid URL address
 * @method      string|bool isUrl($input, $options = []) Check if the input is valid URL address, options could be "path" and "query"
 */
class IsUrlMixin
{
}

/**
 * @property    Wei\IsUuid $isUuid Check if the input is valid UUID(v4)
 * @method      bool isUuid($input, $pattern = null) Returns whether the $input value is valid
 */
class IsUuidMixin
{
}

/**
 * @property    Wei\Lock $lock A service that provide the functionality of exclusive Lock
 * @method      bool lock($key, $expire = null) Acquire a lock key
 */
class LockMixin
{
}

/**
 * @property    Wei\Logger $logger A logger service, which is inspired by Monolog
 * @method      bool logger($level, $message, $context = []) Logs with an arbitrary level
 */
class LoggerMixin
{
}

/**
 * @property    Wei\Memcache $memcache A cache service that stored data in Memcache
 * @method      mixed memcache($key = null, $value = null, $expire = 0) Returns the memcache object, retrieve or store an item
 */
class MemcacheMixin
{
}

/**
 * @property    Wei\Memcached $memcached A cache service that stored data in Memcached
 * @method      mixed memcached($key = null, $value = null, $expire = 0) Returns the memcached object, retrieve or store an item
 */
class MemcachedMixin
{
}

/**
 * @property    Wei\Migration $migration Migration
 */
class MigrationMixin
{
}

/**
 * @property    Wei\ModelTrait $modelTrait The main functions of the model, expected to be used with \Wei\BaseModel
 */
class ModelTraitMixin
{
}

/**
 * @property    Wei\MongoCache $mongoCache A cache service that stores data in MongoDB
 * @method      mixed mongoCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class MongoCacheMixin
{
}

/**
 * @property    Wei\NearCache $nearCache A kind of two level cache, including a "front cache" and a "back cache"
 * @method      mixed nearCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class NearCacheMixin
{
}

/**
 * @property    Wei\NullCache $nullCache NullCache always returns false when reading and true when writing,
 * @method      mixed nullCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class NullCacheMixin
{
}

/**
 * @property    Wei\Password $password A wrapper class for password hashing functions
 */
class PasswordMixin
{
}

/**
 * @property    Wei\PhpError $phpError A wrapper for PHP Error
 * @method      \php_error\ErrorHandler phpError() Returns PHP Error ErrorHandler object
 */
class PhpErrorMixin
{
}

/**
 * @property    Wei\PhpFileCache $phpFileCache A cache service that stored data as PHP variables in files
 * @method      mixed phpFileCache($key, $value = null, $expire = 0) Retrieve or store an item
 */
class PhpFileCacheMixin
{
}

/**
 * @property    Wei\Pinyin $pinyin An util wei that converts Chinese words to phonetic alphabets
 * @method      string pinyin($word, $separator = '') Converts Chinese words to phonetic alphabets
 */
class PinyinMixin
{
}

/**
 * @property    Wei\QueryBuilder $queryBuilder A SQL query builder class
 */
class QueryBuilderMixin
{
}

/**
 * @property    Wei\Record $record A base database record class
 */
class RecordMixin
{
}

/**
 * @property    Wei\Redis $redis A cache service that stored data in Redis
 * @method      mixed redis($key = null, $value = null, $expire = 0) Returns the redis object, retrieve or store an item
 */
class RedisMixin
{
}

/**
 * @property    Wei\Req $req A service that handles the HTTP request data
 * @method      string|null req($name, $default = '') Returns a *stringify* or user defined($default) parameter value
 */
class ReqMixin
{
}

/**
 * @property    Wei\Request $request
 * @method      string|null request($name, $default = '') Returns a *stringify* or user defined($default) parameter value
 */
class RequestMixin
{
}

/**
 * @property    Wei\Res $res A service that handles the HTTP response data
 * @method      Wei\Res res($content = null, $status = null) Send response header and content
 */
class ResMixin
{
}

/**
 * @property    Wei\Response $response
 * @method      Wei\Response response($content = null, $status = null) Send response header and content
 */
class ResponseMixin
{
}

/**
 * @property    Wei\Ret $ret A service that use to build operation result
 * @method      Wei\Ret ret($message, $code = null, $type = null) Return operation result data
 */
class RetMixin
{
}

/**
 * @property    Wei\RetTrait $retTrait Add common usage result functions to service
 */
class RetTraitMixin
{
}

/**
 * @property    Wei\Router $router A service that parse the URL to request data
 */
class RouterMixin
{
}

/**
 * @property    Wei\SafeUrl $safeUrl Generate a URL with signature
 * @method      string safeUrl($url) Generate a URL with signature, alias of generate method
 */
class SafeUrlMixin
{
}

/**
 * @property    Wei\Schema $schema A MySQL schema builder
 */
class SchemaMixin
{
}

/**
 * @property    Wei\ServiceTrait $serviceTrait Add the ability to get and call other services for the class
 */
class ServiceTraitMixin
{
}

/**
 * @property    Wei\Session $session A service that handles session data ($_SESSION)
 * @method      mixed session($key, $value = null) Get or set session
 */
class SessionMixin
{
}

/**
 * @property    Wei\Share $share A service contains share data
 */
class ShareMixin
{
}

/**
 * @property    Wei\Soap $soap A Soap client that works like HTTP service
 * @method      Wei\Soap soap($options = []) Create a new Soap service and execute
 */
class SoapMixin
{
}

/**
 * @property    Wei\StatsD $statsD Sends statistics to the stats daemon over UDP
 */
class StatsDMixin
{
}

/**
 * @property    Wei\Str $str The string util service
 */
class StrMixin
{
}

/**
 * @property    Wei\T $t A translator wei
 * @method      string t($message, $parameters = []) Translate a message
 */
class TMixin
{
}

/**
 * @property    Wei\TagCache $tagCache A cache service that support tagging
 * @method      Wei\TagCache tagCache($tag, $ignore1 = null, $ignore2 = null) Manager: Create a new cache service with tagging support
 */
class TagCacheMixin
{
}

/**
 * @property    Wei\Time $time Date time utils
 */
class TimeMixin
{
}

/**
 * @property    Wei\Ua $ua A service to detect user OS, browser and device name and version
 * @method      bool ua($name) Check if in the specified browser, OS or device
 */
class UaMixin
{
}

/**
 * @property    Wei\Upload $upload A service that handles the uploaded files
 * @method      bool upload($field = null, $options = []) Upload a file
 */
class UploadMixin
{
}

/**
 * @property    Wei\Url $url A helper service to generate the URL
 * @method      string url($url = '', $argsOrParams = [], $params = []) Invoke the "to" method
 */
class UrlMixin
{
}

/**
 * @property    Wei\Uuid $uuid A util wei that generates a RANDOM UUID(universally unique identifier)
 * @method      string uuid() Generate a RANDOM UUID(universally unique identifier)
 */
class UuidMixin
{
}

/**
 * @property    Wei\V $v A chaining validator
 */
class VMixin
{
}

/**
 * @property    Wei\Validate $validate A validator service
 * @method      Wei\Validate validate($options = []) Create a new validator and validate by specified options
 */
class ValidateMixin
{
}

/**
 * @property    Wei\View $view A service that use to render PHP template
 * @method      string view($name = null, $data = []) Render a PHP template
 */
class ViewMixin
{
}

/**
 * @property    Wei\WeChatApp $weChatApp A service handles WeChat(WeiXin) callback message
 * @method      Wei\WeChatApp weChatApp() Start up WeChat application and output the matched rule message
 */
class WeChatAppMixin
{
}

/**
 * @property    Wei\Wei $wei The service container
 */
class WeiMixin
{
}

/**
 * @mixin ApcuMixin
 * @mixin AppMixin
 * @mixin ArrayCacheMixin
 * @mixin AssetMixin
 * @mixin BicacheMixin
 * @mixin BlockMixin
 * @mixin CacheMixin
 * @mixin ClassMapMixin
 * @mixin ClsMixin
 * @mixin ConfigMixin
 * @mixin CookieMixin
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
 * @mixin IsAllMixin
 * @mixin IsAllOfMixin
 * @mixin IsAllowMixin
 * @mixin IsAllowEmptyMixin
 * @mixin IsAlnumMixin
 * @mixin IsAlphaMixin
 * @mixin IsAnyDateTimeMixin
 * @mixin IsArrayMixin
 * @mixin IsBetweenMixin
 * @mixin IsBigIntMixin
 * @mixin IsBlankMixin
 * @mixin IsBoolMixin
 * @mixin IsBoolableMixin
 * @mixin IsCallbackMixin
 * @mixin IsCharMixin
 * @mixin IsChildrenMixin
 * @mixin IsChineseMixin
 * @mixin IsColorMixin
 * @mixin IsContainsMixin
 * @mixin IsCreditCardMixin
 * @mixin IsDateMixin
 * @mixin IsDateTimeMixin
 * @mixin IsDecimalMixin
 * @mixin IsDefaultIntMixin
 * @mixin IsDigitMixin
 * @mixin IsDirMixin
 * @mixin IsDivisibleByMixin
 * @mixin IsDoubleByteMixin
 * @mixin IsEachMixin
 * @mixin IsEmailMixin
 * @mixin IsEmptyMixin
 * @mixin IsEndsWithMixin
 * @mixin IsEqualToMixin
 * @mixin IsExistsMixin
 * @mixin IsFieldExistsMixin
 * @mixin IsFileMixin
 * @mixin IsFloatMixin
 * @mixin IsGreaterThanMixin
 * @mixin IsGreaterThanOrEqualMixin
 * @mixin IsGtMixin
 * @mixin IsGteMixin
 * @mixin IsIdCardCnMixin
 * @mixin IsIdCardHkMixin
 * @mixin IsIdCardMoMixin
 * @mixin IsIdCardTwMixin
 * @mixin IsIdenticalToMixin
 * @mixin IsImageMixin
 * @mixin IsImageUrlMixin
 * @mixin IsInMixin
 * @mixin IsInConstMixin
 * @mixin IsIntMixin
 * @mixin IsIpMixin
 * @mixin IsLengthMixin
 * @mixin IsLessThanMixin
 * @mixin IsLessThanOrEqualMixin
 * @mixin IsLowercaseMixin
 * @mixin IsLtMixin
 * @mixin IsLteMixin
 * @mixin IsLuhnMixin
 * @mixin IsMaxAccuracyMixin
 * @mixin IsMaxCharLengthMixin
 * @mixin IsMaxLengthMixin
 * @mixin IsMediumIntMixin
 * @mixin IsMediumTextMixin
 * @mixin IsMinCharLengthMixin
 * @mixin IsMinLengthMixin
 * @mixin IsMobileCnMixin
 * @mixin IsNaturalNumberMixin
 * @mixin IsNoneOfMixin
 * @mixin IsNullTypeMixin
 * @mixin IsNumberMixin
 * @mixin IsObjectMixin
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
 * @mixin IsSmallIntMixin
 * @mixin IsSomeOfMixin
 * @mixin IsStartsWithMixin
 * @mixin IsStringMixin
 * @mixin IsTextMixin
 * @mixin IsTimeMixin
 * @mixin IsTinyCharMixin
 * @mixin IsTinyIntMixin
 * @mixin IsTldMixin
 * @mixin IsTrueMixin
 * @mixin IsTypeMixin
 * @mixin IsUBigIntMixin
 * @mixin IsUDefaultIntMixin
 * @mixin IsUMediumIntMixin
 * @mixin IsUNumberMixin
 * @mixin IsUSmallIntMixin
 * @mixin IsUTinyIntMixin
 * @mixin IsUppercaseMixin
 * @mixin IsUrlMixin
 * @mixin IsUuidMixin
 * @mixin LockMixin
 * @mixin LoggerMixin
 * @mixin MemcacheMixin
 * @mixin MemcachedMixin
 * @mixin MigrationMixin
 * @mixin ModelTraitMixin
 * @mixin MongoCacheMixin
 * @mixin NearCacheMixin
 * @mixin NullCacheMixin
 * @mixin PasswordMixin
 * @mixin PhpErrorMixin
 * @mixin PhpFileCacheMixin
 * @mixin PinyinMixin
 * @mixin QueryBuilderMixin
 * @mixin RecordMixin
 * @mixin RedisMixin
 * @mixin ReqMixin
 * @mixin RequestMixin
 * @mixin ResMixin
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
 * @mixin StrMixin
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
 */
class AutoCompletion
{
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion();
}

