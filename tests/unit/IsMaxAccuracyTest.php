<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMaxAccuracyTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMaxAccuracy
     * @param mixed $input
     * @param mixed $max
     */
    public function testMaxAccuracy($input, $max)
    {
        $this->assertTrue($this->isMaxAccuracy($input, $max));
    }

    /**
     * @dataProvider providerForNotMaxAccuracy
     * @param mixed $input
     * @param mixed $max
     */
    public function testNotMaxAccuracy($input, $max)
    {
        $this->assertFalse($this->isMaxAccuracy($input, $max));
    }

    public function providerForMaxAccuracy()
    {
        return [
            ['1.0', 1],
            ['0.1', 2],
            ['0.12', 2],
            ['1.234', 3],
            ['-1', 0],
            [(0.1 + 0.7) * 10, 0],
            [1.1E-10, 11],
            [\NAN, 0],
        ];
    }

    public function providerForNotMaxAccuracy()
    {
        return [
            ['1.234', 2],
            ['1.234', 0],
            [1.1E-10, 10],
        ];
    }
}
