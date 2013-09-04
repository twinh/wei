Changelog
=========

## 0.9.5 (WIP)

## 0.9.5-RC1 (2013-09-04)

* Added support for env widget to get server IP in CLI mode
* Added key prefix for cache widgets
* Fixed MySQL cache return false when cache value not changed
* Added new function `wei`, which means 微(micro) in Chinese
* Added `getLastSql`, `getQueries` method for db widget
* Added `getUrl`, `getMethod`, `getData`, `getIp` for call widget
* Removed request widget dependence in most widgets
* Removed server, post, query, header, twig, smarty, dbal, entityManager, event widgets
* Added `getConfig` and `setConfig` for widget container, removed `config` method
* Added new `config` widget, ref #128
* Merged `map` widget into `config` widget, refs #131
* Renamed `Widget\Stdlib\AbstractCache` to `Widget\BaseCase`
* Removed `Widget\Validator\ValidatorInterface` class
* Added parameters for `db` widget `afterQuery` callback
* Fixed `Widget\Db\Record` class save method return false when no field value changed
* Changed cache widgets' `inc` and `dec` methods to `incr` and `decr`
* Added `isModified` method and `modifiedData` property for `Widget\Db\Record` class
* Added `setAliases`, `setDeps` and `isInstanced` methods for widget container
* Added support for empty where condition to `Widget\Db\QueryBuilder` class
* Changed log level priorities to adapted with `Monolog`
* Added shorthand method `widget()->redis()` to get original \Redis object
* Rename widget container callback options from `construct` & `constructed` to `beforeConstruct` & `afterConstructed`
* Added `setPrimaryKey` & `getPrimaryKey` method for `Widget\Db\Record`
* Renamed base class from `Widget\AbstractWidget` to `Widget\Base`
* Fixed `startsWith` and `endsWith` validator error when `findMe` option is int
* Merged `Widget\Validator\BaseGroupValidator` into `Widget\Validator\SomeOf` validator
* Added name parameter for rule valdiator `getMessages` and `getJoinedMessage` methods
* Added `formatLog` method for `logger` widget
* Added `isCharLength` validator
* Added expire time support for `dbCache` widget
* Renamed `equals` validator to `equalTo`, refs #134
* Added new validator widgets: `identicalTo`, `greaterThan` and `lessThan`
* Renamed `max` and `min` validators to `lessThanOrEqual` and `greaterThanOrEqual` validators

## 0.9.4 (2013-08-07)

* Changed cache widgets' `increment` and `decrement` methods to `inc` and `dec`
* Refactored dbCache, use db widget instead of dbal widget to execute SQL
* Added isNew method for record class
* Added host, port and more options for db widget, removed DSN option

## 0.9.4-RC1 (2013-08-02)

* Merged cache widget's `cached` method into `get` method
* Added `indexBy` method for query builder
* Added support that automatically create dependence map when configuration key contains ".", refs 6ca934c7fb79956f804641c3dc127a8789e03961
* Fixed query builder parameter number error when parameter value is 0 or null, refs 719aec608e748bbc36089fe20a08d56bebe54f15
* Fixed test error for PHPUnit < 3.7.0
* Used db widget instead of dbal widget as recordExists validator db provider
* Added [map](lib/Widget/Map.php) widget that handles key-value map data
* Added `jsonObject` dataType for `call` widget
* Removed `__invoke` method, `__invoke` method is optional, refs 7c7f13e3702c11bd5f35f4e9dcc74598e6cd72b3
* Added getResponseJson method for `call` widget
* Allow `null` value as validate rules and messages, refs 132b13dcda99dbd56d596cc50dff13bba8a48c38
* Added `autoload` paramter for import method
* Removed `WidgetAwareInterface` and `AbstractWidgetAware` class, use `widget()` is more convenient
* Changed session namespace default to false
* Added getResponse, getErrorStatus, getErrorException methods, disableSslVerification option for call widget, refs #86
* Added global option, connectFails callback, getUser and getPassword methods for db widget
* Fixed memcache and memcached option error

## 0.9.3 (2013-07-04)

* Fixed [WeChatApp](lib/Widget/WeChatApp.php) click event letter case typo
* Added introduction, installation, and configuration documentations
* Added new demos : using YAML/JSON as widget configuration
* Added driverOptions for db widget

## 0.9.3-RC1 (2013-06-26)

* Added `callback` widget to handle WeChat(Weixin) callback message
* Added `overwrite` option for [upload](lib/Widget/Upload.php) widget
* Added support for upload file without extension
* Added `getMulti` and `setMulti` method for cache widgets
* Added code completion supports for widgets, refs [AbstractWidget](lib/Widget/AbstractWidget.php)
* Removed all root namespace in docblock, refs a5db92949346b38adfc8818ba9aa3f70eb7cbdef
* Added new widget: [arrayCache](lib/Widget/ArrayCache.php)
* Removed `inMethod` widget
* Added new API documentation: http://twinh.github.io/widget/
* Added `getJoinedMessage` method for validators, refs #52
* Simplified [cookie](lib/Widget/Cookie.php) widget options, refs fa88083a742f7aa7e8d6d1829a34e4ca853fb50a
* Removed `inGet`, `inPost`, `inAjax` widget, use [request](lib/Widget/Request.php) widget instead
* Merged `sort` and `attr` widgets in to [arr](lib/Widget/Arr.php) widget
* Refactored [dbCache](lib/Widget/DbCache.php) widget
* Added [couchbase](lib/Widget/Couchbase.php) widget
* Changed license to MIT
* Added [isColor](lib/Widget/Validator/Color.php) validator
* Added [mongoCache](lib/Widget/MongoCache.php) widget
* Added [call](lib/Widget/Call.php) widget
* Merged validator into [validate](lib/Widget/Validate.php) widget
* Added global function `widget` to make it easy to receive widget manager
* Refactored error widget, moved `exception`, `fatal` and `notFound` event to error widget
* Removed marker widget
* Added `cached` method for cache widgets
* Renamed eventManager widget to [event](lib/Widget/Event.php)
* Renamed isPostCode to [isPostcodeCn](lib/Widget/Validator/PostcodeCn.php)
* Added `Stdlib` namespace, moved base cache, view, widget aware class to `Stdlib` namespace
* Renamed `db` widget to [dbal](lib/Widget/Dbal.php)
* Added new [db](lib/Widget/Db.php) widget with basic CURD, light Active Record and Query Builder support
* Refactored [env](lib/Widget/Env.php) widget, use server IP to detect environment name
* Added [gravatar](lib/Widget/Gravatar.php) widget
* Added support for Chinese mobile number starts with 14
* Added documentation for [widget](docs/zh-CN/widget.md) class
* Moved debug configuration to widget class
* Added [FieldExists](lib/Widget/Validator/FieldExists.php) validator
* Removed WidgetInterface, ViewInterface, CacheInterface and ArrayWidget class, make it more esay
* Integrated with [Coveralls](https://coveralls.io/‎), and [Codeship](https://www.codeship.io/)
* Refactored [cookie](lib/Widget/Cookie.php) and [response](lib/Widget/Response.php) widgets
* Added more tests and improved more documentation

## 0.9.2-beta (2013-04-14)

* Released first beta version
* Added unit test and fixed lots of error for all widgets
* Added validation component
* Added first version of API documentation

## 2012-08-30
* Moved to GitHub

## 2010-07-25
* First commit in Google Code

## 2008-07-01
* Project startup
