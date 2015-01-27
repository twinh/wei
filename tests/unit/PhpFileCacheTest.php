<?php

namespace WeiTest;

class PhpFileCacheTest extends FileCacheTest
{
    public function providerForGetterAndSetter()
    {
        return array(
            array(array(),  'array'),
            array(true,     'bool'),
            array(1.2,      'float'),
            array(1,        'int'),
            array(1,        'integer'),
            array(null,     'null'),
            array('1',      'numeric'),
        );
    }
}
