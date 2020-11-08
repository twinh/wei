<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class MaxCharLengthTest extends TestCase
{
    /**
     * @dataProvider providerForMaxCharLength
     * @param mixed $input
     * @param int $length
     */
    public function testMaxCharLength($input, int $length)
    {
        $this->assertTrue($this->isMaxCharLength($input, $length));
    }

    /**
     * @dataProvider providerForNotMaxCharLength
     * @param mixed $input
     * @param int $length
     */
    public function testNotMaxCharLength($input, int $length)
    {
        $this->assertFalse($this->isMaxCharLength($input, $length));
    }

    public function providerForMaxCharLength()
    {
        return [
            ['123', 3],
            ['我我', 2],
            ['我我12', 4],
        ];
    }

    public function providerForNotMaxCharLength()
    {
        return [
            ['123', 2],
            ['我我', 1],
            ['我我12', 3],
        ];
    }
}
