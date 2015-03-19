# Wei

[![Build Status](https://img.shields.io/travis/twinh/wei/master.svg?style=flat-square)](https://travis-ci.org/twinh/wei)
[![Coverage Status](https://img.shields.io/coveralls/twinh/wei.svg?style=flat-square)](https://coveralls.io/r/twinh/wei?branch=master)
[![Latest Stable Version](http://img.shields.io/packagist/v/wei/wei.svg?style=flat-square)](https://packagist.org/packages/wei/wei)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)

Wei is a micro-framework provided powerful but simple APIs for faster and easier PHP 5.3+ development.

## Getting started

Start using Wei in 3 steps, it's easier than any frameworks you've seen before!

```php
// 1. Include the wei container class
require 'path/to/wei/lib/Wei.php';

// 2. Create the default wei container instance
$wei = wei(array(
    // Options for wei container
    'wei' => array(
        'debug' => true,
        // Other options ...
    ),
    // Options for database
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'wei',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => 'xxxxxx',
    ),
    // More options ...
));

// 3. Using "db" object to execute SQL query
$result = $wei->db->fetch("SELECT 1 + 2");
```

## Installation

### Composer

Define the following requirement in your `composer.json` file and run `php composer.phar install` to install
```json
{
    "require": {
        "wei/wei": "0.9.16"
    }
}
```

### Download source code

* [Stable Version](https://github.com/twinh/wei/archive/v0.9.16.zip)
* [Develop Version](https://github.com/twinh/wei/archive/master.zip)

## Resources

* [Documentation](docs/zh-CN) (Chinese, GitHub online markdown)
* [Documentation](http://twinh.github.io/wei/) (Chinese, single HTML file)
* [API Documentation](http://twinh.github.io/wei/apidoc/) (English)
* [Demo](demos) (English, GitHub online markdown)

## API Overview

### Cache

```php
// Available cache objects
$wei->cache;
$wei->apc;
$wei->arrayCache;
$wei->couchbase;
$wei->dbCache;
$wei->fileCache;
$wei->memcache;
$wei->memcached;
$wei->mongoCache;
$wei->redis;

$cache = $wei->memcached;

// Cache APIs
$cache->get('key');
$cache->set('key', 'value', 60);
$cache->remove('key');
$cache->exists('key');
$cache->add('key', 'value');
$cache->replace('key', 'value');
$cache->incr('key', 1);
$cache->decr('key', 1);
$cache->getMulti(array('key', 'key2'));
$cache->setMulti(array('key' => 'value', 'key2' => 'value2'));
$cache->clear();

// ...
```

### Database

```php
$db = $wei->db;

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
$db->findOne();
$db->findAll();
$db->execute();

// Using query builder to build SQL
$record = $db('table');

$record
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
    ->page()
    ->indexBy();

$record->find();
$record->findAll();
$record->fetch();
$record->fetchAll();
$record->fetchColumn();
$record->count();
$record->execute();
$record->getSql();

// ...
```

### Validator

```php
// Available validator objects

// Data type and composition
$wei->isAlnum($input);
$wei->isAlpha($input);
$wei->isBlank($input);
$wei->isDecimal($input);
$wei->isDigit($input);
$wei->isDivisibleBy($input);
$wei->isDoubleByte($input);
$wei->isEmpty($input);
$wei->isEndsWith($input);
$wei->isIn($input);
$wei->isLowercase($input);
$wei->isNull($input);
$wei->isNumber($input);
$wei->isRegex($input);
$wei->isRequired($input);
$wei->isStartsWith($input);
$wei->isType($input);
$wei->isUppercase($input);

// Length
$wei->isLength($input);
$wei->isCharLength($input);
$wei->isMaxLength($input);
$wei->isMinLength($input);

// Comparison
$wei->isEqualTo($input);
$wei->isIdenticalTo($input);
$wei->isGreaterThan($input);
$wei->isGreaterThanOrEqual($input);
$wei->isLessThan($input);
$wei->isLessThanOrEqual($input);
$wei->isBetween($input);

// Date and time
$wei->isDate($input);
$wei->isDateTime($input);
$wei->isTime($input);

// File and directory
$wei->isDir($input);
$wei->isExists($input);
$wei->isFile($input);
$wei->isImage($input);

// Network
$wei->isEmail($input);
$wei->isIp($input);
$wei->isTld($input);
$wei->isUrl($input);
$wei->isUuid($input);

// Region: All
$wei->isCreditCard($input);

// Region: Chinese
$wei->isChinese($input);
$wei->isIdCardCn($input);
$wei->isIdCardHk($input);
$wei->isIdCardMo($input);
$wei->isIdCardTw($input);
$wei->isPhoneCn($input);
$wei->isPostcodeCn($input);
$wei->isQQ($input);
$wei->isMobileCn($input);

// Group
$wei->isAllOf($input);
$wei->isNoneOf($input);
$wei->isOneOf($input);
$wei->isSomeOf($input);

// Others
$wei->isAll($input);
$wei->isCallback($input);
$wei->isColor($input);

// Validate and get error message
if (!$wei->isDigit('abc')) {
    print_r($wei->isDigit->getMessages());
}

// ...
```

### [More](docs/zh-CN#api)

```php
$wei->request;
$wei->cookie;
$wei->session;
$wei->ua;
$wei->upload;
$wei->response;
$wei->e;
$wei->logger;
$wei->call;
$wei->env;
$wei->error;

// ...
```

## Testing

To run the tests:

```sh
$ phpunit
```

## License

Wei is an open-source project released MIT license. See the LICENSE file for details.
