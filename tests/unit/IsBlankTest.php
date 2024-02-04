<?php

namespace WeiTest;

/**
 * @todo more space test
 * @link http://en.wikipedia.org/wiki/Space_(punctuation)
 *
 * @internal
 */
final class IsBlankTest extends BaseValidatorTestCase
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

    public static function providerForBlank()
    {
        return [
            ['   '],
            [" \r\n"],
        ];
    }

    public static function providerForNotBlank()
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
