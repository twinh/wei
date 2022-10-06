<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsTrueTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForTrue
     * @param mixed $input
     */
    public function testTrue($input)
    {
        $this->assertTrue($this->wei->isTrue($input));
    }

    /**
     * @dataProvider providerForNotTrue
     * @param mixed $input
     */
    public function testNotTrue($input)
    {
        $this->assertFalse($this->wei->isTrue($input));
    }

    public function providerForTrue()
    {
        return [
            [true],
            [1],
            ['1'],
        ];
    }

    public function providerForNotTrue()
    {
        return [
            ['123'],
            [123],
            [1.01],
            ['y'],
            ['n'],
            ['on'],
            ['On'],
            ['off'],
            ['yes'],
            ['no'],
            [false],
            [''],
            ['0'],
            [0],
        ];
    }
}
