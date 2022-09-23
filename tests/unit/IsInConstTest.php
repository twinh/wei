<?php

namespace WeiTest;

/**
 * @internal
 * @mixin \IsInConstMixin
 */
final class IsInConstTest extends BaseValidatorTestCase
{
    public const A = 'a';

    public const C = 'c';

    public const TYPE_A = 'a';

    public const TYPE_B = 'b';

    protected function getInputTestOptions()
    {
        return [
            'class' => $this,
        ];
    }

    /**
     * @dataProvider providerForConst
     */
    public function testInConst(string $input, string $prefix)
    {
        $this->assertTrue($this->isInConst($input, $this, $prefix));
    }

    public function providerForConst(): array
    {
        return [
            ['a', ''],
            ['b', ''],
            ['c', ''],
            ['a', 'TYPE'],
            ['b', 'TYPE'],
        ];
    }

    /**
     * @dataProvider providerForNotConst
     */
    public function testNotInConst(string $input, string $prefix)
    {
        $this->assertFalse($this->isInConst($input, $this, $prefix));
    }

    public function providerForNotConst(): array
    {
        return [
            ['c', 'TYPE'],
            ['d', ''],
        ];
    }
}
