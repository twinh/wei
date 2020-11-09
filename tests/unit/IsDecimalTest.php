<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsDecimalTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForDecimal
     * @param mixed $input
     */
    public function testDecimal($input)
    {
        $this->assertTrue($this->isDecimal($input));
    }

    /**
     * @dataProvider providerForNotDecimal
     * @param mixed $input
     */
    public function testNotDecimal($input)
    {
        $this->assertFalse($this->isDecimal($input));
    }

    public function providerForDecimal()
    {
        return [
            [0.0],
            ['0.123'],
            ['1.0'],
            [1.0],
            ['6.01'],
            ['-2.234'],
            [+2.2],
            [-2.2],
            [3E-3],
            [2.3E-3],
            [2.3E3],
        ];
    }

    public function providerForNotDecimal()
    {
        return [
            ['0'],
            [0],
            ['1 23456'],
            ['a bcdefg'],
            ['0.0.1'],
            ['string'],
        ];
    }
}
