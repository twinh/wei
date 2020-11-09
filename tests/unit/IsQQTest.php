<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsQQTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForQQ
     * @param mixed $input
     */
    public function testQQ($input)
    {
        $this->assertTrue($this->isQQ($input));
    }

    /**
     * @dataProvider providerForNotQQ
     * @param mixed $input
     */
    public function testNotQQ($input)
    {
        $this->assertFalse($this->isQQ($input));
    }

    public function providerForQQ()
    {
        return [
            ['1234567'],
            ['1234567'],
            ['123456789'],
        ];
    }

    public function providerForNotQQ()
    {
        return [
            ['1000'], // Too short
            ['011111'], // Should not start with zero
            ['134.433'],
            ['not digit'],
        ];
    }
}
