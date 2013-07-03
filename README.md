# Widget 0.9.3-RC1 [![Build Status](https://travis-ci.org/twinh/widget.png?branch=master)](https://travis-ci.org/twinh/widget)

Widget is a micro-framework provided a new way to write PHP for faster and easier web development.

## [Installation](docs/zh-CN/installation.md)

### Composer

Define the following requirement in your `composer.json` file and run `php composer.phar install` to install
```json
{
    "require": {
        "widget/widget": "0.9.3-RC1"
    }
}
```

**Dowload source code**

* [Stable Release](https://github.com/twinh/widget/archive/0.9.3-RC1.zip)
* [Develope Release](https://github.com/twinh/widget/archive/master.zip)

### Getting started

```php
// Require the widget manager class
require 'path/to/widget/lib/Widget/Widget.php';

// Create the default widget manager instance
$widget = widget();

// Invoke the query widget to receive the URL query parameter
$id = $widget->query('id');
```

## API Overview

### Cache

```php
$widget = widget();

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
```

## Testing

To run the tests:

```sh
$ phpunit
```

## License

Widget is an open-source project released MIT license. See the LICENSE file for details.