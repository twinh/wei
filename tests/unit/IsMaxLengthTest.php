<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsMaxLengthTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForMaxLength
     * @param mixed $options
     */
    public function testMaxLength($options)
    {
        $this->assertTrue($this->isMaxLength('length7', $options));
    }

    /**
     * @dataProvider providerForNotMaxLength
     * @param mixed $options
     */
    public function testNotMaxLength($options)
    {
        $this->assertFalse($this->isMaxLength('length7', $options));
    }

    public static function providerForMaxLength()
    {
        return [
            [7],
            [8],
            [200],
        ];
    }

    public static function providerForNotMaxLength()
    {
        return [
            [6],
            [1],
            [-1],
        ];
    }
}
