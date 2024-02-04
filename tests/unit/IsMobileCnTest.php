<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMobileCnTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMobileCn
     * @param mixed $input
     */
    public function testMobileCn($input)
    {
        $this->assertTrue($this->isMobileCn($input));
    }

    /**
     * @dataProvider providerForNotMobileCn
     * @param mixed $input
     */
    public function testNotMobileCn($input)
    {
        $this->assertFalse($this->isMobileCn($input));
    }

    public static function providerForMobileCn()
    {
        return [
            ['13112345678'],
            ['13612345678'],
            ['13800138000'],
            ['15012345678'],
            ['15812345678'],
            ['18812345678'],
            ['14012345678'],
            ['17012345678'],
            ['16000000000'],
            ['19000000000'],
        ];
    }

    public static function providerForNotMobileCn()
    {
        return [
            ['10000000000'],
            ['11000000000'],
            ['12000000000'],
            ['88888888'],
            ['not digit'],
            ['0754-8888888'],
        ];
    }
}
