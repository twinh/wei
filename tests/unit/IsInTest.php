<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsInTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIn
     * @param mixed $input
     * @param mixed $array
     * @param mixed $case
     */
    public function testIn($input, $array, $case = false)
    {
        $this->assertTrue($this->isIn($input, $array, $case));
    }

    /**
     * @dataProvider providerForNotIn
     * @param mixed $input
     * @param mixed $array
     * @param mixed $case
     */
    public function testNotIn($input, $array, $case = false)
    {
        $this->assertFalse($this->isIn($input, $array, $case));
    }

    public function testUnexpectedType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->isIn('abc', 'bcd');
    }

    public function providerForIn()
    {
        return [
            ['apple', ['apple', 'pear']],
            ['apple', new \ArrayObject(['apple', 'pear'])],
            ['', [null]],
        ];
    }

    public function providerForNotIn()
    {
        return [
            ['', [null], true],
        ];
    }
}
