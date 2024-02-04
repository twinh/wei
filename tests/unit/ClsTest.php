<?php

namespace WeiTest;

use Wei\Cls;

class ClsTest extends TestCase
{
    /**
     * @param string $expected
     * @param string $actual
     * @return void
     * @dataProvider providerForBaseName
     */
    public function testBaseName(string $expected, string $actual)
    {
        $this->assertSame($expected, $actual);
    }

    public static function providerForBaseName(): array
    {
        return [
            ['ClsTest', Cls::baseName(static::class)],
            ['test', Cls::baseName('test')],
            [\stdClass::class, Cls::baseName(\stdClass::class)],
        ];
    }
}
