<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsDateTimeTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForDateTime
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testDateTime($input, $format = null)
    {
        $this->assertTrue($this->isDateTime($input, $format));
    }

    /**
     * @dataProvider providerForNotDateTime
     * @param mixed $input
     * @param mixed|null $format
     */
    public function testNotDateTime($input, $format = null)
    {
        $this->assertFalse($this->isDateTime($input, $format));
    }

    public function providerForDateTime()
    {
        return [
            ['1000-01-01 00:00:00'],
            ['3000-01-01 00:00:50'],
            ['2012-02-29 23:59:59'],
        ];
    }

    public function providerForNotDateTime()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['2013-02-29 24:00:00', 'Y-m-d H:i:s'],
            ['2013-01-32 23:60:00'],
            ['2013-00-00 23:59:61'],
            ['2012 61:00'],
            ['2013-02-29 24:00:00'], // date_get_last_errors() => warning_count = 1,  The parsed date was invalid
        ];
    }

    public function testBeforeAndAfter()
    {
        $this->assertTrue($this->isDate('2013-02-19', [
            'before' => '2013-03-01',
            'after' => '2013-01-01',
        ]));

        $this->assertFalse($this->isDate('2013-02-19', [
            'before' => '2013-01-01',
        ]));

        $this->assertFalse($this->isDate('2013-02-19', [
            'after' => '2013-03-01',
        ]));
    }
}
