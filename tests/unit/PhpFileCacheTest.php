<?php

namespace WeiTest;

/**
 * @internal
 */
final class PhpFileCacheTest extends FileCacheTest
{
    public function providerForGetterAndSetter()
    {
        return [
            [[],  'array'],
            [true,     'bool'],
            [1.2,      'float'],
            [1,        'int'],
            [1,        'integer'],
            [null,     'null'],
            ['1',      'numeric'],
        ];
    }
}
