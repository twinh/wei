<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsCharTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForLength
     * @param mixed $input
     * @param int|null $minLength
     * @param int|null $maxLength
     */
    public function testLength($input, int $minLength = null, int $maxLength = null)
    {
        $this->assertTrue($this->isChar($input, $minLength, $maxLength));
    }

    /**
     * @dataProvider providerForNotLength
     * @param mixed $input
     * @param int|null $minLength
     * @param int|null $maxLength
     */
    public function testNotLength($input, int $minLength = null, int $maxLength = null)
    {
        $this->assertFalse($this->isChar($input, $minLength, $maxLength));
    }

    public function providerForLength()
    {
        return [
            ['i♥u4', 0, 4],
            ['i♥u4', 2, 5],
        ];
    }

    public function providerForNotLength()
    {
        return [
            ['i♥u4', 5, 6],
            ['i♥u4', -2, -1],
        ];
    }
}
