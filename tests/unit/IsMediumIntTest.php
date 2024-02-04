<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMediumIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMediumInt
     * @param mixed $input
     */
    public function testMediumInt($input)
    {
        $this->assertTrue($this->isMediumInt($input));
    }

    /**
     * @dataProvider providerForNotMediumInt
     * @param mixed $input
     */
    public function testNotMediumInt($input)
    {
        $this->assertFalse($this->isMediumInt($input));
    }

    public static function providerForMediumInt()
    {
        return [
            [0],
            [1],
            [-1],
            [(-2) ** 23],
            [2 ** 23 - 1],
        ];
    }

    public static function providerForNotMediumInt()
    {
        return [
            ['1.0'],
            [(-2) ** 23 - 1],
            [2 ** 23],
        ];
    }
}
