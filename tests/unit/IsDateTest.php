<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsDateTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForDate
     * @param mixed $input
     */
    public function testDate($input)
    {
        $this->assertTrue($this->isDate($input));
    }

    /**
     * @dataProvider providerForNotDate
     * @param mixed $input
     */
    public function testNotDate($input)
    {
        $this->assertFalse($this->isDate($input));
    }

    public static function providerForDate()
    {
        return [
            ['2013-01-13'],
            ['1000-01-01'],
            ['3000-01-01'],
            ['2012-02-29'],
        ];
    }

    public static function providerForNotDate()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['2013-02-29'],
            ['2013-01-32'],
            ['2013-00-00'],
            ['2012'],
        ];
    }
}
