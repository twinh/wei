<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class DivisibleByTest extends TestCase
{
    /**
     * @dataProvider providerForDivisibleBy
     * @param mixed $input
     * @param mixed $divisor
     */
    public function testDivisibleBy($input, $divisor)
    {
        $this->assertTrue($this->isDivisibleBy($input, $divisor));
    }

    /**
     * @dataProvider providerForNotDivisibleBy
     * @param mixed $input
     * @param mixed $divisor
     */
    public function testNotDivisibleBy($input, $divisor)
    {
        $this->assertFalse($this->isDivisibleBy($input, $divisor));
    }

    public function providerForDivisibleBy()
    {
        return [
            ['10', '5'],
            ['2', '1'],
            [2.5, 0.5],
        ];
    }

    public function providerForNotDivisibleBy()
    {
        return [
            [10, 3],
            [11, 2],
            [0.7, 0.2],
            [5, 0.1], // Returns 0.1
        ];
    }
}
