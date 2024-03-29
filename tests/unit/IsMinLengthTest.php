<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMinLengthTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMinLength
     * @param mixed $options
     */
    public function testMinLength($options)
    {
        $this->assertTrue($this->isMinLength('length7', $options));
    }

    /**
     * @dataProvider providerForNotMinLength
     * @param mixed $options
     */
    public function testNotMinLength($options)
    {
        $this->assertFalse($this->isMinLength('length7', $options));
    }

    public static function providerForMinLength()
    {
        return [
            [6],
            [1],
            [-1],
        ];
    }

    public static function providerForNotMinLength()
    {
        return [
            [8],
            [200],
        ];
    }
}
