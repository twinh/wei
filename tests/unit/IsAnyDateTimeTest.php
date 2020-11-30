<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsAnyDateTimeTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForAnyDateTime
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testAnyDateTime($input, $format = null)
    {
        $this->assertTrue($this->isAnyDateTime($input, $format));
    }

    /**
     * @dataProvider providerForNotAnyDateTime
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testNotAnyDateTime($input, $format = null)
    {
        $this->assertFalse($this->isAnyDateTime($input, $format));
    }

    public function providerForAnyDateTime()
    {
        return [
            ['2020-01-01'],
            ['20:20'],
            ['00:00'],
            ['1000-01-01 00:00:00'],
            ['3000-01-01 00:00:50'],
            ['2012-02-29 23:59:59'],
        ];
    }

    public function providerForNotAnyDateTime()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['2020-01-00'],
            ['00:61'],
            ['2013-02-29 24:00:00'],
            ['2013-01-32 23:60:00'],
            ['2013-00-00 23:59:61'],
            ['2012 61:00'],
            ['2013-02-29 24:00:00'], // date_get_last_errors() => warning_count = 1,  The parsed date was invalid
        ];
    }
}
