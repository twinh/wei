<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class PhoneCnTest extends TestCase
{
    /**
     * @dataProvider providerForPhoneCn
     * @param mixed $input
     */
    public function testPhoneCn($input)
    {
        $this->assertTrue($this->isPhoneCn($input));
    }

    /**
     * @dataProvider providerForNotPhoneCn
     * @param mixed $input
     */
    public function testNotPhoneCn($input)
    {
        $this->assertFalse($this->isPhoneCn($input));
    }

    public function providerForPhoneCn()
    {
        return [
            ['020-1234567'],
            ['0768-123456789'],
            // PhoneCn number without city code
            ['1234567'],
            ['123456789'],
        ];
    }

    public function providerForNotPhoneCn()
    {
        return [
            ['012345-1234567890'],
            ['010-1234567890'],
            ['123456'],
            ['not digit'],
        ];
    }
}
