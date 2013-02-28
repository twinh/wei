#Widget v0.9.1-alpha [![Build Status](https://travis-ci.org/twinh/widget.png?branch=master)](https://travis-ci.org/twinh/widget)
Widget is a micro-framework that provide a new way to write PHP for faster and easier web development.

Installation - Composer
-----------------------
Define the following requirement in your `composer.json` file and run `php composer.phar install` to install
```javascript
{
    "require": {
        "twinh/widget": "dev-master"
    }
}
```

Basic Usage
-----------
```php
// Require the widget manager class
require 'path/to/widget/lib/Widget/Widget.php';

// Creates the default widget manager instance
$widget = Widget\Widget::create();
```

Testing
-------

To run the tests:

    $ phpunit

License
-------
Widget is an open-source project released Apache 2.0 license. See the LICENSE file for details.
