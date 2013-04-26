#Widget v0.9.2-beta [![Build Status](https://travis-ci.org/twinh/widget.png?branch=master)](https://travis-ci.org/twinh/widget)
Widget is a micro-framework provided a new way to write PHP for faster and easier web development.

Installation - Composer
-----------------------
Define the following requirement in your `composer.json` file and run `php composer.phar install` to install
```json
{
    "require": {
        "widget/widget": "0.9.2-beta"
    }
}
```

Getting started
---------------
```php
// Require the widget manager class
require 'path/to/widget/lib/Widget/Widget.php';

// Create the default widget manager instance
$widget = \Widget\Widget::create();

// Invoke the query widget to receive the URL query parameter
$id = $widget->query('id');
```

Testing
-------

To run the tests:

    $ phpunit

License
-------
Widget is an open-source project released Apache 2.0 license. See the LICENSE file for details.
