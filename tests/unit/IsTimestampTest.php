<?php

namespace WeiTest;

/**
 * @internal
 * @mixin \IsTimestampMixin
 */
final class IsTimestampTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForTimestamp
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testTimestamp($input, $format = null)
    {
        $this->assertTrue($this->isTimestamp($input, $format));
    }

    /**
     * @dataProvider providerForNotTimestamp
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testNotTimestamp($input, $format = null)
    {
        $this->assertFalse($this->isTimestamp($input, $format));
    }

    public static function providerForTimestamp()
    {
        return [
            ['2012-02-29 23:59:59'],
            ['1970-01-01 00:00:01'],
            ['2038-01-19 03:14:07'],
        ];
    }

    public static function providerForNotTimestamp()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['2013-02-29 24:00:00'],
            ['2013-01-32 23:60:00'],
            ['2013-00-00 23:59:61'],
            ['2012 61:00'],
            ['2013-02-29 24:00:00'], // date_get_last_errors() => warning_count = 1,  The parsed date was invalid
            ['1970-01-01 00:00:00'],
            ['2038-01-19 03:14:08'],
        ];
    }
}
