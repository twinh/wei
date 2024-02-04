<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsTldTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForTld
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testTld($input, $format = null)
    {
        $this->assertTrue($this->isTld($input, $format));
    }

    /**
     * @dataProvider providerForNotTld
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testNotTld($input, $format = null)
    {
        $this->assertFalse($this->isTld($input, $format));
    }

    public static function providerForTld()
    {
        return [
            ['com'],
            ['COM'],
            ['cn'],
            ['us'],
            ['xn--fiqs8S'], // 中国
        ];
    }

    public static function providerForNotTld()
    {
        return [
            ['abc'],
            ['xn'],
            ['xn--'],
            ['xn--abc'],
        ];
    }
}
