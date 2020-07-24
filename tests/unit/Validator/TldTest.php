<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class TldTest extends TestCase
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

    public function providerForTld()
    {
        return [
            ['com'],
            ['COM'],
            ['cn'],
            ['us'],
            ['xn--fiqs8S'], // 中国
        ];
    }

    public function providerForNotTld()
    {
        return [
            ['abc'],
            ['xn'],
            ['xn--'],
            ['xn--abc'],
        ];
    }
}
