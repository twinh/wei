<?php

namespace WeiTest;

/**
 * @internal
 * @mixin \IsUniqueMixin
 */
final class IsUniqueTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUnique
     * @param mixed $input
     */
    public function testUnique($input)
    {
        $this->assertTrue($this->isUnique($input));
    }

    /**
     * @dataProvider providerForNotUnique
     * @param mixed $input
     */
    public function testNotUnique($input)
    {
        $this->assertFalse($this->isUnique($input));
    }

    public static function providerForUnique(): array
    {
        return [
            [[]],
            [[1, 2]],
            [['1', '2']],
            [
                [
                    ['1', '2'],
                    ['1', '3'],
                ],
            ],
        ];
    }

    public static function providerForNotUnique(): array
    {
        return [
            [[1, 1]],
            [[1, '1']],
            [
                [
                    [1, 2],
                    [1, '2'],
                ],
            ],
        ];
    }
}
