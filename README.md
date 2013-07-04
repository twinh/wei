# Widget 0.9.3-RC1 [![Build Status](https://travis-ci.org/twinh/widget.png?branch=master)](https://travis-ci.org/twinh/widget)

Widget is a micro-framework provided powerful but simple APIs for faster and easier PHP 5.3+ development.

## [Installation](docs/zh-CN/book/installation.md)

### Composer

Define the following requirement in your `composer.json` file and run `php composer.phar install` to install
```json
{
    "require": {
        "widget/widget": "0.9.3-RC1"
    }
}
```

### Dowload source code

* [Stable Version](https://github.com/twinh/widget/archive/0.9.3-RC1.zip)
* [Develop Version](https://github.com/twinh/widget/archive/master.zip)

## Getting started

```php
// Include the widget manager class
require 'path/to/widget/lib/Widget/Widget.php';

// Create the default widget manager instance
$widget = widget();

// Invoke the query widget to receive the URL query parameter
$id = $widget->query('id');
```

## Resources

* [Documentation](docs/zh-CN)
* [Demo](demos)

## API Overview

### Cache

```php
// Available cache widgets
$widget->apc;
$widget->arrayCache;
$widget->couchbase;
$widget->dbCache;
$widget->fileCache;
$widget->memcache;
$widget->memcached;
$widget->redis;
$widget->cache;

$cache = $widget->memcached;

// Cache APIs 
$cache->get('key');
$cache->set('key', 'value', 60);
$cache->remove('key', 'value');
$cache->exists('key');
$cache->add('key', 'value');
$cache->replace('key', 'value');
$cache->increment('key', 1);
$cache->decrement('key', 1);
$cache->getMulti(array('key', 'key2'));
$cache->setMulti(array('key' => 'value', 'key2' => 'value2'));
$cache->clear();

// ...
```

### Db

```php
$db = $widget->db;

// Basic CRUD and Active Recrod support
$db->query();
$db->insert();
$db->update();
$db->delete();
$db->select();
$db->selectAll();
$db->fetch();
$db->fetchAll();
$db->fetchColumn();
$db->find();
$db->findAll();
$db->execute();

// Using QueryBuilder to build SQL
$qb = $db->createQueryBuilder();

$qb
    ->select()
    ->addSelect()
    ->update()
    ->delete()
    ->from()
    ->where()
    ->andWhere()
    ->orWhere()
    ->groupBy()
    ->addGroupBy()
    ->having()
    ->andHaving()
    ->orHaving()
    ->orderBy()
    ->addOrderBy()
    ->offset()
    ->limit()
    ->page();

$qb->find();
$qb->findAll();
$qb->fetch();
$qb->fetchAll();
$qb->fetchColumn();
$qb->count();
$qb->execute();
$qb->getSql();

// ...
```

### Validator

```php
// Available validator widgets

// Data type and composition
$widget->isAlnum($input);
$widget->isAlpha($input);
$widget->isBlank($input);
$widget->isDecimal($input);
$widget->isDigit($input);
$widget->isDivisibleBy($input);
$widget->isDoubleByte($input);
$widget->isEmpty($input);
$widget->isEndsWith($input);
$widget->isEquals($input);
$widget->isIn($input);
$widget->isLowercase($input);
$widget->isNull($input);
$widget->isNumber($input);
$widget->isRegex($input);
$widget->isRequired($input);
$widget->isStartsWith($input);
$widget->isType($input);
$widget->isUppercase($input);

// Length and size
$widget->isBetween($input);
$widget->isLength($input);
$widget->isMax($input);
$widget->isMaxLength($input);
$widget->isMin($input);
$widget->isMinLength($input);

// Date and time
$widget->isDate($input);
$widget->isDateTime($input);
$widget->isTime($input);

// File and directory
$widget->isDir($input);
$widget->isExists($input);
$widget->isFile($input);
$widget->isImage($input);

// Network
$widget->isEmail($input);
$widget->isIp($input);
$widget->isTld($input);
$widget->isUrl($input);
$widget->isUuid($input);

// Region: All
$widget->isCreditCard($input);

// Region: Chinese
$widget->isChinese($input);
$widget->isIdCardCn($input);
$widget->isIdCardHk($input);
$widget->isIdCardMo($input);
$widget->isIdCardTw($input);
$widget->isPhoneCn($input);
$widget->isPostcodeCn($input);
$widget->isQQ($input);
$widget->isMobileCn($input);

// Group
$widget->isAllOf($input);
$widget->isNoneOf($input);
$widget->isOneOf($input);
$widget->isSomeOf($input);

// Others
$widget->isAll($input);
$widget->isCallback($input);
$widget->isColor($input);

// Validate and get error message
if (!$widget->isDigit('abc')) {
    print_r($widget->isDigit->getMessages());
}

// ...
```

### [More](docs/zh-CN#api)

```php
$widget->request;
$widget->cookie;
$widget->post;
$widget->query;
$widget->server;
$widget->session;
$widget->ua;
$widget->upload;
$widget->response;
$widget->header;
$widget->escape;
$widget->logger;
$widget->call;
$widget->env;
$widget->error;

// ...
```

## Testing

To run the tests:

```sh
$ phpunit
```

## License

Widget is an open-source project released MIT license. See the LICENSE file for details.