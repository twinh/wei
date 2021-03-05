<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsNumberTest extends BaseValidatorTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        wei()->t->setLocale('en');
    }

    /**
     * @dataProvider providerForNumber
     * @param mixed $input
     * @param int|null $precision
     * @param int|null $scale
     */
    public function testNumber($input, int $precision = null, int $scale = null)
    {
        $this->assertTrue($this->isNumber($input, $precision, $scale));
    }

    /**
     * @dataProvider providerForNotNumber
     * @param mixed $input
     * @param int|null $precision
     * @param int|null $scale
     */
    public function testNotNumber($input, int $precision = null, int $scale = null)
    {
        $this->assertFalse($this->isNumber($input, $precision, $scale));
    }

    public function providerForNumber()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['1234567'],
            ['123456789'],
            ['1.1'],
            [2.0],
            ['1.0', null, 1],
            ['0.1', null, 2],
            ['0.12', null, 2],
            ['1.234', null, 3],
            ['-1', null, 0],
            [(0.1 + 0.7) * 10, null, 0],
            [1.1E-10, null, 11],
            [\INF, null, 0],
            [-\INF, null, 0],
            [1, 1],
            [10, 2],
            [100, 3],
            [0.1, 1, 1],
            [0.11, 2, 2],
            [1, 1, 0],
            [1.1, 2, 1],
            [10.1, 3, 1],
            [100.1, 4, 1],
        ];
    }

    public function providerForNotNumber()
    {
        return [
            ['012345-1234567890'],
            ['not number'],
            ['0.1a'],
            ['1.234', null, 2],
            ['1.234', null, 0],
            [1.1E-10, null, 10],
            [10, 1],
            [100, 2],
            [1000, 3],
            [0.1, 0, 0],
            [0.11, 2, 1],
            [1.1, 1, 1],
            [10.1, 2, 1],
            [100.1, 3, 1],
            [1000.1, 4, 1],
            [\NAN],
            [\INF, 1, null],
            [-\INF, 1, null],
            [\INF, 100, null],
            [-\INF, 100, null],
        ];
    }

    public function testInvalidArgument()
    {
        $this->expectExceptionObject(new \InvalidArgumentException('Precision must be greater than or equals scale'));

        $this->isNumber(1, 1, 2);
    }

    public function testScaleMessage()
    {
        $this->assertFalse($this->isNumber(0.11, 2, 1));
        $this->assertSame('This value can have at most 1 decimals', $this->isNumber->getFirstMessage());
    }

    public function testGreaterThanMessage()
    {
        $this->assertFalse($this->isNumber(10, 2, 1));
        $this->assertSame('This value must be less than or equal to 9.9', $this->isNumber->getFirstMessage());

        $this->assertFalse($this->isNumber(10, 1, 0));
        $this->assertSame('This value must be less than or equal to 9', $this->isNumber->getFirstMessage());
    }

    public function testLessThanMessage()
    {
        $this->assertFalse($this->isNumber(-10, 2, 1));
        $this->assertSame('This value must be greater than or equal to -9.9', $this->isNumber->getFirstMessage());

        $this->assertFalse($this->isNumber(-10, 1, 0));
        $this->assertSame('This value must be greater than or equal to -9', $this->isNumber->getFirstMessage());
    }
}
