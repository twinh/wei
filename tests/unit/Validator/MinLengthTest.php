<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class MinLengthTest extends TestCase
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

    public function providerForMinLength()
    {
        return [
            [6],
            [1],
            [-1],
        ];
    }

    public function providerForNotMinLength()
    {
        return [
            [8],
            [200],
        ];
    }
}
