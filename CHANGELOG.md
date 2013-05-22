Changelog
=========

## 0.9.3 (not released yet)

* Added [callback](lib/Widget/Callback.php) widget to handle WeChat(Weixin) callback message
* Added `overwirte` option for [upload](lib/Widget/Upload.php) widget
* Added support for upload file without extension
* Added `getMulti` and `setMulti` method for cache widgets
* Added code completion supports for widgets, refs [AbstractWidget](lib/Widget/AbstractWidget.php)
* Removed all root namespace in docblock, refs a5db92949346b38adfc8818ba9aa3f70eb7cbdef
* Added new widget: [arrayCache](lib/Widget/ArrayCache.php)
* Removed `inMethod` widget
* Added new API documentation: http://twinh.github.io/widget/
* Added `getJoinedMessage` method for validators, refs #52
* Simplfied [cookie](lib/Widget/Cookie.php) widget options, refs fa88083a742f7aa7e8d6d1829a34e4ca853fb50a
* Removed `inGet`, `inPost`, `inAjax` widget, use [request](lib/Widget/Request.php) widget instead
* Merged `sort` and `attr` widgets in to [arr](lib/Widget/Arr.php) widget
* Refactored [dbCache](lib/Widget/DbCache.php) widget
* Added [couchbase](lib/Wudget/Couchbase.php) widget

## 0.9.2-beta (2013-04-14)

* Released first beta version
* Added unit test and fixed lots of error for all widgets
* Added validation component
* Added fisrt version of API documentation

## 2012-08-30
* Moved to GitHub

## 2010-07-25
* First commit in Google Code

## 2008-07-01
* Project startup