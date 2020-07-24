<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class PresentTest extends TestCase
{
    /**
     * @dataProvider providerForPresent
     * @param mixed $input
     */
    public function testPresent($input)
    {
        $this->assertTrue($this->isPresent($input));
    }

    /**
     * @dataProvider providerForNotPresent
     * @param mixed $input
     */
    public function testNotPresent($input)
    {
        $this->assertFalse($this->isPresent($input));
    }

    public function providerForPresent()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['string'],
            [' '],
            ["\r\n"],
            ["\n"],
            ["\r"],
        ];
    }

    public function providerForNotPresent()
    {
        return [
            [''],
            [false],
            [[]],
            [null],
        ];
    }
}
