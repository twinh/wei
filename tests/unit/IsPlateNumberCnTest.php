<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsPlateNumberCnTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForPlateNumberCn
     * @param mixed $input
     */
    public function testPlateNumberCn($input)
    {
        $this->assertTrue($this->isPlateNumberCn($input));
    }

    /**
     * @dataProvider providerForNotPlateNumberCn
     * @param mixed $input
     */
    public function testNotPlateNumberCn($input)
    {
        $this->assertFalse($this->isPlateNumberCn($input));
    }

    public function providerForPlateNumberCn()
    {
        return [
            ['京A12345'],
            ['津A12345'],
            ['冀A12345'],
            ['晋A12345'],
            ['蒙A12345'],
            ['辽A12345'],
            ['吉A12345'],
            ['黑A12345'],
            ['沪A12345'],
            ['苏A12345'],
            ['浙A12345'],
            ['皖A12345'],
            ['闽A12345'],
            ['赣A12345'],
            ['鲁A12345'],
            ['豫A12345'],
            ['鄂A12345'],
            ['湘A12345'],
            ['粤A12345'],
            ['桂A12345'],
            ['琼A12345'],
            ['渝A12345'],
            ['川A12345'],
            ['贵A12345'],
            ['云A12345'],
            ['藏A12345'],
            ['陕A12345'],
            ['甘A12345'],
            ['青A12345'],
            ['宁A12345'],
            ['新A12345'],
            ['新A12345'],
            ['军A12345'],
            ['海A12345'],
            ['空A12345'],
            ['北A12345'],
            ['沈A12345'],
            ['兰A12345'],
            ['济A12345'],
            ['南A12345'],
            ['广A12345'],
            ['成A12345'],
        ];
    }

    public function providerForNotPlateNumberCn()
    {
        return [
            ['12345'],
            ['粤BBBBB'],
            ['粤123456'],
            ['中A12345'],
        ];
    }
}
