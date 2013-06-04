<?php

namespace WidgetTest\DbTest;

class User extends Record
{
    protected $table;

    protected $fullTable;

    protected $scopes;

    // default value
    protected $data = array(
        'age' => 0,
    );
}