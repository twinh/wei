<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsTimeTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForTime
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testTime($input, $format = null)
    {
        $this->assertTrue($this->isTime($input, $format));
    }

    /**
     * @dataProvider providerForNotTime
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testNotTime($input, $format = null)
    {
        $this->assertFalse($this->isTime($input, $format));
    }

    public function providerForTime()
    {
        return [
            ['00:00:00'],
            ['00:00', 'i:s'],
            ['23:59:59', 'H:i:s'],
        ];
    }

    public function providerForNotTime()
    {
        return [
            ['24:00:00'],
            ['23:60:00'],
            ['23:59:61'],
            ['61:00', 'i:s'],
            ['01:01:01:01'],
        ];
    }
}
