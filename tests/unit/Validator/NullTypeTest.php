<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class NullTypeTest extends TestCase
{
    /**
     * @dataProvider providerForNull
     * @param mixed $input
     */
    public function testNull($input)
    {
        $this->assertTrue($this->isNullType($input));
    }

    /**
     * @dataProvider providerForNotNull
     * @param mixed $input
     */
    public function testNotNull($input)
    {
        $this->assertFalse($this->isNullType($input));
    }

    public function providerForNull()
    {
        return [
            [null],
        ];
    }

    public function providerForNotNull()
    {
        return [
            [''],
            [false],
            [0],
            [0.0],
            [[]],
            ['0'],
        ];
    }
}
