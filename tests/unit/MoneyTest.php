<?php

namespace WeiTest;

use Wei\Money;

function money($value, $options = []): Money
{
    return Money::new($value, $options);
}

class MoneyTest extends TestCase
{
    public function testCreate()
    {
        $money = money(1);
        $this->assertInstanceOf(Money::class, $money);
    }

    public function testCreateFromInt()
    {
        $money = money(1);
        $this->assertSame('1', $money->toString());
    }

    public function testCreateFromFloat()
    {
        $money = money(1.23);
        $this->assertSame('1.23', $money->toString());
    }

    public function testCreateFromString()
    {
        $money = money('1.23');
        $this->assertSame('1.23', $money->toString());
    }

    public function testCreateFromMoneyInstance()
    {
        $money = money(money('1.23'));
        $this->assertSame('1.23', $money->toString());
    }

    public function testCreateFromInvalidString()
    {
        $money = money(money('abc3'));
        $this->assertSame('0', $money->toString());
    }

    public function testCreateFromNegativeString()
    {
        $money = money(money('-1.23'));
        $this->assertSame('-1.23', $money->toString());
    }

    public function testNewInstance()
    {
        $money = money('1.2');
        $money2 = $money->add('1.3');
        $this->assertNotEquals($money, $money2);
        $this->assertSame('1.2', $money->toString());
        $this->assertSame('2.5', $money2->toString());
    }

    /**
     * @param mixed $left
     * @param mixed $right
     * @param string $result
     * @dataProvider providerForAdd
     */
    public function testAdd($left, $right, string $result)
    {
        $money = money($left)->add($right);
        $this->assertSame($result, (string) $money);
    }

    public static function providerForAdd(): array
    {
        return [
            [1, 1, '2'],
            ['0.12', '0.01', '0.13'],
            [2.51, 0.01, '2.52'],
            ['1a', '1.2b', '2.2'],
            [money(0.01), money(0.02), '0.03'],
        ];
    }

    /**
     * @param mixed $left
     * @param mixed $right
     * @param string $result
     * @dataProvider providerForSub
     */
    public function testSub($left, $right, string $result)
    {
        $money = money($left)->sub($right);
        $this->assertSame($result, (string) $money);
    }

    public static function providerForSub(): array
    {
        return [
            [2, 1, '1'],
            ['0.13', '0.01', '0.12'],
            [2.52, 0.01, '2.51'],
            ['1.2a', '1b', '0.2'],
            [money(0.03), money(0.02), '0.01'],
        ];
    }

    /**
     * @param mixed $left
     * @param mixed $right
     * @param string $result
     * @dataProvider providerForMul
     */
    public function testMul($left, $right, string $result)
    {
        $money = money($left)->mul($right);
        $this->assertSame($result, (string) $money);
    }

    public static function providerForMul(): array
    {
        return [
            [2, 1, '2'],
            ['0.13', '0.91', '0.12'],
            [2.52, 1.21, '3.05'],
            ['1.2a', '1b', '1.2'],
            [money(0.14), money(0.62), '0.09'],
            [1.23, 2, '2.46'],
            [0.1, 0.2, '0.02'],
        ];
    }

    /**
     * @param mixed $left
     * @param mixed $right
     * @param string $result
     * @dataProvider providerForDiv
     */
    public function testDiv($left, $right, string $result)
    {
        $money = money($left)->div($right);
        $this->assertSame($result, (string) $money);
    }

    public static function providerForDiv(): array
    {
        return [
            [2, 1, '2'],
            ['0.91', '0.12', '7.58'],
            [2.52, 1.21, '2.08'],
            ['1.2a', '1b', '1.2'],
            [money(0.62), money(0.14), '4.43'],
            [12345.67, 10, '1234.57'],
        ];
    }

    public function testAddFloat()
    {
        $money = money(2.51)->add(0.01);
        $this->assertSame('"2.52"', json_encode($money));
        $this->assertNotSame('2.52', json_encode(2.51 + 0.01), 'not equal 2.5199999999999996');
    }

    public function testSubFloat()
    {
        $money = money(2.52)->sub(0.01);
        $this->assertSame('"2.51"', json_encode($money));
        $this->assertNotSame('2.52', json_encode(2.52 - 0.01), 'not equal 2.5100000000000002');
    }

    public function testMulFloat()
    {
        $value = money(1.23)->mul(2);
        $floatingValue = money(.1)->mul(.2);

        $this->assertSame(2.46, (float) (string) $value);
        $this->assertSame(.02, (float) (string) $floatingValue);
        $this->assertNotEquals('0.02', json_encode(.1 * .2), 'not equal 0.020000000000000004');
    }

    public function testDivFloat()
    {
        $value = money(9.87)->div(2);
        $this->assertSame(4.94, (float) (string) $value);
    }

    public function testRoundHalfUp()
    {
        $value1 = money(17.955);
        $value2 = money(17.855);
        $value3 = money(17.455);
        $stringValue1 = money('17.955');
        $stringValue2 = money('17.855');
        $stringValue3 = money('17.455');

        $this->assertSame($value1->getValue(), 17.96);
        $this->assertSame($value2->getValue(), 17.86);
        $this->assertSame($value3->getValue(), 17.46);
        $this->assertSame($stringValue1->getValue(), 17.96);
        $this->assertSame($stringValue2->getValue(), 17.86);
        $this->assertSame($stringValue3->getValue(), 17.46);
    }

    public function testRoundNegativeHalfUp()
    {
        $value1 = money(-17.955);
        $value2 = money(-17.855);
        $value3 = money(-17.455);
        $stringValue1 = money('-17.955');
        $stringValue2 = money('-17.855');
        $stringValue3 = money('-17.455');

        $this->assertSame($value1->getValue(), -17.95);
        $this->assertSame($value2->getValue(), -17.85);
        $this->assertSame($value3->getValue(), -17.45);
        $this->assertSame($stringValue1->getValue(), -17.95);
        $this->assertSame($stringValue2->getValue(), -17.85);
        $this->assertSame($stringValue3->getValue(), -17.45);
    }

    public function testMulWithPrecision()
    {
        $value = money(1.369, ['precision' => 3])->mul(3);
        $this->assertSame(4.107, (float) (string) $value);
    }

    public function testDivWithPrecision()
    {
        $value = money(4.107, ['precision' => 3])->div(3);
        $this->assertSame(1.369, (float) (string) $value);
    }

    public function testDifferentPrecision()
    {
        $value1 = money(1.234, ['precision' => 3]);
        $value2 = money(1.234, ['precision' => 0]);

        $this->assertSame(1.234, $value1->getValue());
        $this->assertSame(1.0, $value2->getValue());
        $this->assertSame(1, $value2->toNumber());

        $this->assertSame(1234, $value1->getIntValue());
        $this->assertSame(1, $value2->getIntValue());

        $this->assertSame(5.801, $value1->add(4.567)->toNumber());
        $this->assertSame(6, $value2->add(4.567)->toNumber());

        $this->assertSame(-3.333, $value1->sub(4.567)->getValue());
        $this->assertSame(-4.0, $value2->sub(4.567)->getValue());
        $this->assertSame(-4, $value2->sub(4.567)->toNumber());

        $this->assertSame(234, $value1->getCents());
        $this->assertSame(0, $value2->getCents());
    }

    public function testUseSourcePrecision()
    {
        $value1 = money(1.23);
        $value2 = money(1.239, ['precision' => 3]);
        $this->assertSame(2.47, $value1->add($value2)->getValue());

        $value1 = money(1.23);
        $value2 = money(1.239, ['precision' => 3]);
        $this->assertSame(2.469, $value2->add($value1)->getValue());
    }

    public function testRound()
    {
        $round1 = money(1.2349);
        $round2 = money(5.6789);
        $multiply = money(10.00);
        $divide = money(0.01);

        $this->assertSame(1.23, $round1->getValue());
        $this->assertSame(5.68, $round2->getValue());
        $this->assertSame(0.01, $multiply->mul(0.001)->getValue());
        $this->assertSame(10.0, $divide->div(0.001)->getValue());
    }

    public function testIntAndRealValue()
    {
        $value1 = money(2.51)->add(.01);
        $value2 = money(2.52)->sub(.01);

        $this->assertEquals(2.52, $value1->getValue());
        $this->assertSame(252, $value1->getIntValue());

        $this->assertEquals(2.51, $value2->getValue());
        $this->assertSame(251, $value2->getIntValue());
    }

    public function testJsonEncode()
    {
        $value1 = money(1.23);
        $value2 = money(2.51)->add(0.01);
        $value3 = money(2.52)->sub(0.02);

        // TODO 不一样的
        $this->assertSame('{"value":"1.23"}', json_encode(['value' => $value1]));
        $this->assertSame('{"value":"2.52"}', json_encode(['value' => $value2]));
        $this->assertSame('{"value":"2.5"}', json_encode(['value' => $value3]));
    }

    public function testJsMaxInt()
    {
        $max1 = money(90071992547409.91);
        $max2 = money(-90071992547409.91);

        $this->assertSame(9007199254740991, $max1->getIntValue());
        $this->assertSame(-9007199254740991, $max2->getIntValue());

        // TODO determine PHP max
        // 92233720368547758.07 * 100 = 9.2233720368548E+18
        // (int)(92233720368547758.07 * 100) = -9223372036854775808
    }

    /**
     * @param mixed $money
     * @param int $int
     * @dataProvider providerForToInt
     */
    public function testToInt($money, int $int)
    {
        $value = money($money);
        $this->assertSame($value->toInt(), $int);
    }

    public static function providerForToInt(): array
    {
        return [
            [1.23, 1],
            ['1.23', 1],
            [2, 2],
            [30, 30],
            ['2.23213', 2],
        ];
    }

    /**
     * @param mixed $money
     * @param int $cents
     * @dataProvider providerForGetCents
     */
    public function testGetCents($money, int $cents)
    {
        $value = money($money);
        $this->assertSame($value->getCents(), $cents);
    }

    public static function providerForGetCents(): array
    {
        return [
            [1.23, 23],
            ['1.23', 23],
            [2, 0],
            [30, 0],
            ['2.23213', 23],
        ];
    }

    public function testNegative()
    {
        $money = money(1.32);
        $money->negative();
        $this->assertSame(-1.32, $money->getValue());

        $money = money(-1.22);
        $money->negative();
        $this->assertSame(-1.22, $money->getValue());
    }

    public function testIsZero()
    {
        $money = money(0);
        $this->assertTrue($money->isZero());

        $money = money(0.01);
        $this->assertFalse($money->isZero());
    }

    public function testIsPositive()
    {
        $money = money(0.01);
        $this->assertTrue($money->isPositive());

        $money = money(0);
        $this->assertFalse($money->isPositive());

        $money = money(-0.1);
        $this->assertFalse($money->isPositive());
    }

    public function testIsNegative()
    {
        $money = money(0.01);
        $this->assertFalse($money->isNegative());

        $money = money(0);
        $this->assertFalse($money->isNegative());

        $money = money(-0.1);
        $this->assertTrue($money->isNegative());
    }
}
