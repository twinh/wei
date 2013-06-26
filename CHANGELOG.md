Changelog
=========

## 0.9.3-RC1 (2013-06-26)

* Added [callback](lib/Widget/Callback.php) widget to handle WeChat(Weixin) callback message
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
* Remoevd WidgetInterface, ViewInterface, CacheInterface and ArrayWidget class, make it more esay
* Integrated with [Coveralls](https://coveralls.io/‎),  [CircleCi](https://circleci.com/) and [Codeship](https://www.codeship.io/)
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
