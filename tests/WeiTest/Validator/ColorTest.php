<?php

namespace WeiTest\Validator;

class ColorTest extends TestCase
{
    /**
     * @dataProvider providerForColor
     */
    public function testColor($input, $options = array())
    {
        $this->assertTrue($this->isColor($input, $options));
    }

    /**
     * @dataProvider providerForNotColor
     */
    public function testNotColor($input, $options = array())
    {
        $this->assertFalse($this->isColor($input, $options));
    }

    public function providerForColor()
    {
        return array(
            array('#FFFFFF'),
            array('#FFF'),
            array('#ABCDEF'),
            array('#012345'),
            array('#abcdef'),
            array('#AABBCC'),
            array('#fff'),
        );
    }

    public function providerForNotColor()
    {
        return array(
            array('FFF'),
            array('#FGH'),
            array('#ffff'),
            array('#0123456'),
            array('AAFFFF')
        );
    }
}
