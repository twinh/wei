<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMinCharLengthTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMinCharLength
     * @param mixed $input
     * @param int $length
     */
    public function testMinCharLength($input, int $length)
    {
        $this->assertTrue($this->isMinCharLength($input, $length));
    }

    /**
     * @dataProvider providerForNotMinCharLength
     * @param mixed $input
     * @param int $length
     */
    public function testNotMinCharLength($input, int $length)
    {
        $this->assertFalse($this->isMinCharLength($input, $length));
    }

    public function providerForMinCharLength()
    {
        return [
            ['123', 3],
            ['我', 1],
            ['我1', 2],
        ];
    }

    public function providerForNotMinCharLength()
    {
        return [
            ['123', 4],
            ['我', 2],
            ['我1', 3],
            [[1, 2], 1],
        ];
    }
}
