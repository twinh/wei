<?php

namespace WeiTest\Validator;

/**
 * @todo more space test
 * @link http://en.wikipedia.org/wiki/Space_(punctuation)
 *
 * @internal
 */
final class BlankTest extends TestCase
{
    /**
     * @dataProvider providerForBlank
     * @param mixed $input
     */
    public function testBlank($input)
    {
        $this->assertTrue($this->isBlank($input));
    }

    /**
     * @dataProvider providerForNotBlank
     * @param mixed $input
     */
    public function testNotBlank($input)
    {
        $this->assertFalse($this->isBlank($input));
    }

    public function providerForBlank()
    {
        return [
            ['   '],
            [" \r\n"],
        ];
    }

    public function providerForNotBlank()
    {
        return [
            ['0'],
            [0],
            [0.0],
            [' abc '],
            ['a b'],
            ['　'], // ﻿Unicode full width space
        ];
    }
}
