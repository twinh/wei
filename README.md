Widget
======

[![Build Status](https://travis-ci.org/twinh/widget.png?branch=master)](https://travis-ci.org/twinh/widget)

Installation - Composer
-----------------------
Add or update your `composer.json` and run `php composer.phar install` to install
```javascript
{
    "require": {
        "twinh/widget": "*"
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
