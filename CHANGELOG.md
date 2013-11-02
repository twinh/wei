Changelog
=========

## 0.9.8 (WIP)

## 0.9.7 (2013-10-31)

* Added field parameter for record class getModifiedData to receive the old value
* Fixed call and db service "global" option logic error
* Pass weChatApp service $content variable by reference
* Added getResponseHeaders for call service
* Returns array when getResponseHeader's second parameter is false
* Added header option (equals to CURLOPT_HEADER) for call service
* Added json and jsonp method for response

## 0.9.7-RC1 (2013-10-28)

* Merged urlDebugger service into request service, added overwriteMethod and overwriteAjax options for request service
* Added isVerifyToken method for weChatApp service, close #146
* Added beforeSend callback for WeChatApp service
* Added asset service to resolve javascript and css file cache
* Make sure all WeChatApp service text rules is case insensitive
* Renamed WeChatApp service "fallback" method to "defaults"
* Simplified WeChatApp service parse XML logic
* Refactored WeChatApp service to use array instead of SimpleXMLElement to construct response content
* Removed keyword property in WeChatApp service
* Refactored class from Mongo to MongoClient, refs #129
* Refactored url service to generate URL by specified URL and parameters instead of predefined template
* Fixed cache object is null when set new driver for cache service
* Added getFirstMessage for base validator
* Added findOne for db service
* Injects app service into controller object
* Renamed service property from "deps" to "providers"
* Added exception code (1020) for store callback cache error
* Automatic loads record in query builder when called magic get/set or array access
* Added luhn validator, refs #148
* Added getModifiedData method for record class
* Decoupled db and cache service, moved get fields logic to record class
* Added skeleton application demo "new-app"
* Triggers "afterLoad" callback when calls record's reload method
* Added getAttrs method for WeChatApp service

## 0.9.6 (2013-10-09)

* Fixed record status error when record is created from query builder
* Added `getCurlInfo` method for call service
* Close the cURL session on call service destruct
* Added doc for password service and isPassword validator
* Added options parameter for datetime validators
* Renamed isEmpty validator to isPresent

## 0.9.6-RC1 (2013-10-05)

* Added ArrayAccess support for db record class
* Fixed create db record error when key name is class property name
* Added `getIterator` method for session service
* Removed `weChatApp` deprecated FuncFlag
* Added `beforeSend`/`afterSend` callback for response service
* Added `reconnect` method for db service
* Added `insertBatch` method for db service, refs #137
* Added master-salve db support for db service, refs #133
* Updated query exception to `PDOException`, while previous is `RuntimeException` and hard to catch
* Added `toJson` method for record class
* Added callbacks for record class
* Added `reload` method for record class
* Added `beforeValidate` callback for validate service
* Added `removeField` method for validator service
* Added `toArray` method for session service
* Added `exists` method for session service
* Fixed record status error after created
* Renamed all methods from `inXxx` to `isXxx`
* Updated return annotation from class name to $this for better code hint
* Added `is` method for env service to detect custom env name
* Renamed Widget::create to Widget::getContainer, add Widget::setContainer method
* Improve record class `toArray` method
* Added support to generate database table fields
* Renamed inconsistent method from getRules to getFieldRules
* Merged `is` validator into `validate` service
* Removed developer option `disableSslVerification`, use cURL option `CURLOPT_SSL_VERIFYPEER` instead
* Added support to parse response header only when CURLOPT_HEADER is true
* Added `getCurlOption` and `setCurlOption` methods for call service
* Added optional parameter $field for record class `isModified` method
* Added `throwException` option for call service
* Renamed db service globalCallbacks option to global
* Added array parameters support for logging message
* Added support for parsing and return muliti response headers
* Added arrayAccess, count, foreach support for call service
* Added `__toString` method for call service to return the response body
* Added `phone` validator
* Added `password` service to genrate secure password
* Fixed validate flow not break when required rule is invalid
* Added `password` validator

## 0.9.5 (2013-09-08)

* Added `getIterator` method for request and cookie services
* Added `fields` property for record class
* Merged flush, download service into response service, refs #135
* Added headers for redirect service when send by custom view

## 0.9.5-RC1 (2013-09-04)

* Added support for env service to get server IP in CLI mode
* Added key prefix for cache services
* Fixed MySQL cache return false when cache value not changed
* Added new function `wei`, which means 微(micro) in Chinese
* Added `getLastSql`, `getQueries` method for db service
* Added `getUrl`, `getMethod`, `getData`, `getIp` for call service
* Removed request service dependence in most services
* Removed server, post, query, header, twig, smarty, dbal, entityManager, event services
* Added `getConfig` and `setConfig` for service container, removed `config` method
* Added new `config` service, ref #128
* Merged `map` service into `config` service, refs #131
* Renamed `Widget\Stdlib\AbstractCache` to `Widget\BaseCase`
* Removed `Widget\Validator\ValidatorInterface` class
* Added parameters for `db` service `afterQuery` callback
* Fixed `Widget\Db\Record` class save method return false when no field value changed
* Changed cache services' `inc` and `dec` methods to `incr` and `decr`
* Added `isModified` method and `modifiedData` property for `Widget\Db\Record` class
* Added `setAliases`, `setDeps` and `isInstanced` methods for service container
* Added support for empty where condition to `Widget\Db\QueryBuilder` class
* Changed log level priorities to adapted with `Monolog`
* Added shorthand method `service()->redis()` to get original \Redis object
* Rename service container callback options from `construct` & `constructed` to `beforeConstruct` & `afterConstructed`
* Added `setPrimaryKey` & `getPrimaryKey` method for `Widget\Db\Record`
* Renamed base class from `Widget\AbstractWidget` to `Widget\Base`
* Fixed `startsWith` and `endsWith` validator error when `findMe` option is int
* Merged `Widget\Validator\BaseGroupValidator` into `Widget\Validator\SomeOf` validator
* Added name parameter for rule valdiator `getMessages` and `getJoinedMessage` methods
* Added `formatLog` method for `logger` service
* Added `isCharLength` validator
* Added expire time support for `dbCache` service
* Renamed `equals` validator to `equalTo`, refs #134
* Added new validator services: `identicalTo`, `greaterThan` and `lessThan`
* Renamed `max` and `min` validators to `lessThanOrEqual` and `greaterThanOrEqual` validators

## 0.9.4 (2013-08-07)

* Changed cache services' `increment` and `decrement` methods to `inc` and `dec`
* Refactored dbCache, use db service instead of dbal service to execute SQL
* Added isNew method for record class
* Added host, port and more options for db service, removed DSN option

## 0.9.4-RC1 (2013-08-02)

* Merged cache service's `cached` method into `get` method
* Added `indexBy` method for query builder
* Added support that automatically create dependence map when configuration key contains ".", refs 6ca934c7fb79956f804641c3dc127a8789e03961
* Fixed query builder parameter number error when parameter value is 0 or null, refs 719aec608e748bbc36089fe20a08d56bebe54f15
* Fixed test error for PHPUnit < 3.7.0
* Used db service instead of dbal service as recordExists validator db provider
* Added [map](lib/Widget/Map.php) service that handles key-value map data
* Added `jsonObject` dataType for `call` service
* Removed `__invoke` method, `__invoke` method is optional, refs 7c7f13e3702c11bd5f35f4e9dcc74598e6cd72b3
* Added getResponseJson method for `call` service
* Allow `null` value as validate rules and messages, refs 132b13dcda99dbd56d596cc50dff13bba8a48c38
* Added `autoload` paramter for import method
* Removed `WidgetAwareInterface` and `AbstractWidgetAware` class, use `service()` is more convenient
* Changed session namespace default to false
* Added getResponse, getErrorStatus, getErrorException methods, disableSslVerification option for call service, refs #86
* Added global option, connectFails callback, getUser and getPassword methods for db service
* Fixed memcache and memcached option error

## 0.9.3 (2013-07-04)

* Fixed [WeChatApp](lib/Widget/WeChatApp.php) click event letter case typo
* Added introduction, installation, and configuration documentations
* Added new demos : using YAML/JSON as service configuration
* Added driverOptions for db service

## 0.9.3-RC1 (2013-06-26)

* Added `callback` service to handle WeChat(Weixin) callback message
* Added `overwrite` option for [upload](lib/Widget/Upload.php) service
* Added support for upload file without extension
* Added `getMulti` and `setMulti` method for cache services
* Added code completion supports for services, refs [AbstractWidget](lib/Widget/AbstractWidget.php)
* Removed all root namespace in docblock, refs a5db92949346b38adfc8818ba9aa3f70eb7cbdef
* Added new service: [arrayCache](lib/Widget/ArrayCache.php)
* Removed `inMethod` service
* Added new API documentation: http://twinh.github.io/service/
* Added `getJoinedMessage` method for validators, refs #52
* Simplified [cookie](lib/Widget/Cookie.php) service options, refs fa88083a742f7aa7e8d6d1829a34e4ca853fb50a
* Removed `inGet`, `inPost`, `inAjax` service, use [request](lib/Widget/Request.php) service instead
* Merged `sort` and `attr` services in to [arr](lib/Widget/Arr.php) service
* Refactored [dbCache](lib/Widget/DbCache.php) service
* Added [couchbase](lib/Widget/Couchbase.php) service
* Changed license to MIT
* Added [isColor](lib/Widget/Validator/Color.php) validator
* Added [mongoCache](lib/Widget/MongoCache.php) service
* Added [call](lib/Widget/Call.php) service
* Merged validator into [validate](lib/Widget/Validate.php) service
* Added global function `service` to make it easy to receive service manager
* Refactored error service, moved `exception`, `fatal` and `notFound` event to error service
* Removed marker service
* Added `cached` method for cache services
* Renamed eventManager service to [event](lib/Widget/Event.php)
* Renamed isPostCode to [isPostcodeCn](lib/Widget/Validator/PostcodeCn.php)
* Added `Stdlib` namespace, moved base cache, view, service aware class to `Stdlib` namespace
* Renamed `db` service to [dbal](lib/Widget/Dbal.php)
* Added new [db](lib/Widget/Db.php) service with basic CURD, light Active Record and Query Builder support
* Refactored [env](lib/Widget/Env.php) service, use server IP to detect environment name
* Added [gravatar](lib/Widget/Gravatar.php) service
* Added support for Chinese mobile number starts with 14
* Added documentation for [service](docs/zh-CN/service.md) class
* Moved debug configuration to service class
* Added [FieldExists](lib/Widget/Validator/FieldExists.php) validator
* Removed WidgetInterface, ViewInterface, CacheInterface and ArrayWidget class, make it more esay
* Integrated with [Coveralls](https://coveralls.io/‎), and [Codeship](https://www.codeship.io/)
* Refactored [cookie](lib/Widget/Cookie.php) and [response](lib/Widget/Response.php) services
* Added more tests and improved more documentation

## 0.9.2-beta (2013-04-14)

* Released first beta version
* Added unit test and fixed lots of error for all services
* Added validation component
* Added first version of API documentation

## 2012-08-30
* Moved to GitHub

## 2010-07-25
* First commit in Google Code

## 2008-07-01
* Project startup
