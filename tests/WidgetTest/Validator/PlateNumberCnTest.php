<?php

namespace WidgetTest\Validator;

class PlateNumberCnTest extends TestCase
{
    /**
     * @dataProvider providerForPlateNumberCn
     */
    public function testPlateNumberCn($input)
    {
        $this->assertTrue($this->isPlateNumberCn($input));
    }

    /**
     * @dataProvider providerForNotPlateNumberCn
     */
    public function testNotPlateNumberCn($input)
    {
        $this->assertFalse($this->isPlateNumberCn($input));
    }

    public function providerForPlateNumberCn()
    {
        return array(
            array('京A12345'),
            array('津A12345'),
            array('冀A12345'),
            array('晋A12345'),
            array('蒙A12345'),
            array('辽A12345'),
            array('吉A12345'),
            array('黑A12345'),
            array('沪A12345'),
            array('苏A12345'),
            array('浙A12345'),
            array('皖A12345'),
            array('闽A12345'),
            array('赣A12345'),
            array('鲁A12345'),
            array('豫A12345'),
            array('鄂A12345'),
            array('湘A12345'),
            array('粤A12345'),
            array('桂A12345'),
            array('琼A12345'),
            array('渝A12345'),
            array('川A12345'),
            array('贵A12345'),
            array('云A12345'),
            array('藏A12345'),
            array('陕A12345'),
            array('甘A12345'),
            array('青A12345'),
            array('宁A12345'),
            array('新A12345'),
            array('新A12345'),
            array('军A12345'),
            array('海A12345'),
            array('空A12345'),
            array('北A12345'),
            array('沈A12345'),
            array('兰A12345'),
            array('济A12345'),
            array('南A12345'),
            array('广A12345'),
            array('成A12345'),
        );
    }

    public function providerForNotPlateNumberCn()
    {
        return array(
            array('12345'),
            array('粤BBBBB'),
            array('粤123456'),
            array('中A12345'),
        );
    }
}
