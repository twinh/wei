<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class MaxLengthTest extends TestCase
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

    public function providerForMaxLength()
    {
        return [
            [7],
            [8],
            [200],
        ];
    }

    public function providerForNotMaxLength()
    {
        return [
            [6],
            [1],
            [-1],
        ];
    }
}
