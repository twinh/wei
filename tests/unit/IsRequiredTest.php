<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsRequiredTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForRequired
     * @param mixed $input
     */
    public function testRequired($input)
    {
        $this->assertTrue($this->isRequired($input));
    }

    /**
     * @dataProvider providerForNotRequired
     * @param mixed $input
     */
    public function testNotRequired($input)
    {
        $this->assertFalse($this->isRequired($input));
    }

    public function providerForRequired()
    {
        return [
            ['123'],
            ['false'],
            ['off'],
            ['on'],
            ['true'],
            [0],
            ['0'],
            [0.0],
            [true],
            ['string'],
            [' '],
            ["\r\n"],
        ];
    }

    public function providerForNotRequired()
    {
        return [
            [[]],
            [false],
            [''],
            [null],
        ];
    }
}
